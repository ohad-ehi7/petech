<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Transaction;
use App\Models\Inventory;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;

class SaleController extends Controller
{
    public function processSale(Request $request)
    {
        try {
            // Log the incoming request data
            Log::info('Processing sale request:', $request->all());

            // Validate the request
            $request->validate([
                'total_amount' => 'required|numeric|min:0',
                'discount_amount' => 'nullable|numeric|min:0',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,ProductID',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.price' => 'required|numeric|min:0',
                'customer_name' => 'required|string|max:255'
            ]);

            DB::beginTransaction();

            // Create or find customer
            $customer = Customer::firstOrCreate(
                ['CustomerCode' => $request->customer_name],
                ['CustomerCode' => $request->customer_name]
            );

            // Create the sale record
            $sale = Sale::create([
                'SaleDate' => now(),
                'CustomerID' => $customer->CustomerID,
                'TotalAmount' => $request->total_amount,
                'DiscountAmount' => $request->discount_amount ?? 0,
                'PaymentMethod' => 'Cash',
                'ClerkID' => Auth::id()
            ]);

            // Process each item in the sale
            foreach ($request->items as $item) {
                // Verify inventory
                $inventory = Inventory::where('ProductID', $item['product_id'])->first();
                if (!$inventory || $inventory->QuantityOnHand < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product ID: {$item['product_id']}");
                }

                // Create sales item record
                $sale->salesItems()->create([
                    'ProductID' => $item['product_id'],
                    'Quantity' => $item['quantity'],
                    'PriceAtSale' => $item['price']
                ]);

                // Create transaction record
                Transaction::create([
                    'ProductID' => $item['product_id'],
                    'TransactionType' => 'Sale',
                    'QuantityChange' => -$item['quantity'],
                    'UnitPrice' => $item['price'],
                    'TotalAmount' => $item['price'] * $item['quantity'],
                    'ReferenceID' => $sale->SaleID,
                    'TransactionDate' => now()
                ]);

                // Update inventory
                $inventory->QuantityOnHand -= $item['quantity'];
                $inventory->save();
            }

            DB::commit();

            Log::info('Sale processed successfully', ['sale_id' => $sale->SaleID]);

            return response()->json([
                'success' => true,
                'sale_id' => $sale->SaleID,
                'message' => 'Sale processed successfully'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Validation error in sale processing:', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing sale:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error processing sale: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display a listing of sales with optional period filtering
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Sale::with(['customer', 'salesItems', 'clerk']);

        // Apply period filter if specified
        if ($request->has('period')) {
            $now = now();
            switch ($request->period) {
                case 'today':
                    $query->whereDate('SaleDate', $now->toDateString());
                    break;
                case 'week':
                    $query->whereBetween('SaleDate', [$now->startOfWeek(), $now->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('SaleDate', $now->month)
                          ->whereYear('SaleDate', $now->year);
                    break;
                case 'quarter':
                    $query->whereBetween('SaleDate', [
                        $now->startOfQuarter(),
                        $now->endOfQuarter()
                    ]);
                    break;
                case 'year':
                    $query->whereYear('SaleDate', $now->year);
                    break;
                case 'paid':
                    $query->where('Status', 'Paid');
                    break;
                case 'void':
                    $query->where('Status', 'Void');
                    break;
            }
        }

        $sales = $query->orderBy('SaleDate', 'desc')->get();
            
        return view('sales-transaction', compact('sales'));
    }

    public function show(Sale $sale)
    {
        $sale->load(['customer', 'salesItems.product', 'clerk']);
        return view('sales.show', compact('sale'));
    }

    /**
     * Bulk delete sales and restore inventory quantities
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkDelete(Request $request)
    {
        try {
            \Log::info('Bulk delete request received', ['saleIds' => $request->saleIds]);

            $validator = Validator::make($request->all(), [
                'saleIds' => 'required|array',
                'saleIds.*' => 'exists:sales,SaleID'
            ]);

            if ($validator->fails()) {
                \Log::error('Validation failed', ['errors' => $validator->errors()]);
                return response()->json(['message' => 'Invalid sale IDs provided'], 422);
            }

            DB::beginTransaction();

            $sales = Sale::with(['salesItems', 'transactions'])->whereIn('SaleID', $request->saleIds)->get();
            
            foreach ($sales as $sale) {
                \Log::info('Processing sale for deletion', ['saleId' => $sale->SaleID]);

                // First delete related transactions
                if ($sale->transactions) {
                    \Log::info('Deleting related transactions', ['saleId' => $sale->SaleID]);
                    $sale->transactions()->delete();
                }

                // Restore inventory quantities
                foreach ($sale->salesItems as $item) {
                    \Log::info('Restoring inventory quantity', [
                        'saleId' => $sale->SaleID,
                        'itemId' => $item->ItemID,
                        'quantity' => $item->Quantity
                    ]);

                    $product = Product::find($item->ItemID);
                    if ($product) {
                        $product->Quantity += $item->Quantity;
                        $product->save();
                    }
                }

                // Delete sales items
                $sale->salesItems()->delete();

                // Finally delete the sale
                $sale->delete();
            }

            DB::commit();
            \Log::info('Bulk delete completed successfully');

            return response()->json(['message' => 'Sales deleted successfully']);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error in bulk delete', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Error deleting sales: ' . $e->getMessage()], 500);
        }
    }
}
