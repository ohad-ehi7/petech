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
use App\Models\InventoryLog;

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
                $product = Product::findOrFail($item['product_id']);
                
                // Create sales item
                $sale->salesItems()->create([
                    'ProductID' => $item['product_id'],
                    'Quantity' => $item['quantity'],
                    'PriceAtSale' => $item['price']
                ]);

                // Update inventory
                $product->inventory()->decrement('QuantityOnHand', $item['quantity']);

                // Create inventory log for stock out
                InventoryLog::create([
                    'ProductID' => $item['product_id'],
                    'type' => 'stock_out',
                    'quantity' => $item['quantity'],
                    'notes' => 'Sale #' . $sale->SaleID,
                    'created_by' => Auth::id()
                ]);
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

        return view('transaction.sales-transaction', compact('sales'));
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

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $sale = Sale::create([
                'CustomerID' => $request->CustomerID,
                'SaleDate' => now(),
                'TotalAmount' => $request->TotalAmount,
                'PaymentMethod' => $request->PaymentMethod,
                'Status' => 'completed',
                'Notes' => $request->Notes
            ]);

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['ProductID']);
                
                // Create sale item
                $sale->salesItems()->create([
                    'ProductID' => $item['ProductID'],
                    'Quantity' => $item['Quantity'],
                    'PriceAtSale' => $item['Price'],
                    'Subtotal' => $item['Quantity'] * $item['Price']
                ]);

                // Update inventory
                $inventory = $product->inventory;
                $inventory->QuantityOnHand -= $item['Quantity'];
                $inventory->save();

                // Create inventory log
                InventoryLog::create([
                    'ProductID' => $item['ProductID'],
                    'type' => 'stock_out',
                    'quantity' => $item['Quantity'],
                    'notes' => 'Sale transaction #' . $sale->SaleID,
                    'created_by' => Auth::id()
                ]);
            }

            DB::commit();
            return response()->json(['success' => true, 'sale_id' => $sale->SaleID]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, Sale $sale)
    {
        try {
            DB::beginTransaction();

            // Update sale details
            $sale->update([
                'CustomerID' => $request->CustomerID,
                'TotalAmount' => $request->TotalAmount,
                'PaymentMethod' => $request->PaymentMethod,
                'Notes' => $request->Notes
            ]);

            // Delete existing sale items and restore inventory
            foreach ($sale->salesItems as $item) {
                $inventory = $item->product->inventory;
                $inventory->QuantityOnHand += $item->Quantity;
                $inventory->save();

                // Create inventory log for reversal
                InventoryLog::create([
                    'ProductID' => $item->ProductID,
                    'type' => 'stock_in',
                    'quantity' => $item->Quantity,
                    'notes' => 'Reversal of sale #' . $sale->SaleID,
                    'created_by' => Auth::id()
                ]);
            }
            $sale->salesItems()->delete();

            // Create new sale items
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['ProductID']);
                
                // Create sale item
                $sale->salesItems()->create([
                    'ProductID' => $item['ProductID'],
                    'Quantity' => $item['Quantity'],
                    'PriceAtSale' => $item['Price'],
                    'Subtotal' => $item['Quantity'] * $item['Price']
                ]);

                // Update inventory
                $inventory = $product->inventory;
                $inventory->QuantityOnHand -= $item['Quantity'];
                $inventory->save();

                // Create inventory log
                InventoryLog::create([
                    'ProductID' => $item['ProductID'],
                    'type' => 'stock_out',
                    'quantity' => $item['Quantity'],
                    'notes' => 'Updated sale #' . $sale->SaleID,
                    'created_by' => Auth::id()
                ]);
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy(Sale $sale)
    {
        try {
            DB::beginTransaction();

            // Restore inventory for each item
            foreach ($sale->salesItems as $item) {
                $inventory = $item->product->inventory;
                $inventory->QuantityOnHand += $item->Quantity;
                $inventory->save();

                // Create inventory log for reversal
                InventoryLog::create([
                    'ProductID' => $item->ProductID,
                    'type' => 'stock_in',
                    'quantity' => $item->Quantity,
                    'notes' => 'Cancellation of sale #' . $sale->SaleID,
                    'created_by' => Auth::id()
                ]);
            }

            // Delete sale items and the sale
            $sale->salesItems()->delete();
            $sale->delete();

            DB::commit();
            return redirect()->route('sales.index')
                ->with('success', 'Sale deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error deleting sale: ' . $e->getMessage());
        }
    }
}
