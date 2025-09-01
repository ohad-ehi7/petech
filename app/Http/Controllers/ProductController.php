<?php
/**
 * Product Controller
 *
 * Handles all product-related operations including CRUD operations,
 * image management, and inventory tracking.
 *
 * @category Controllers
 * @package  App\Http\Controllers
 * @author   ChangChang Team <team@changchang.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/changchang
 */

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\InventoryLog;
use Illuminate\Http\Request;
use App\Models\PurchaseRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\FacadesLog;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

/**
 * Class ProductController
 *
 * Manages product operations including creation, updates, deletion,
 * and inventory management.
 *
 * @category Controllers
 * @package  App\Http\Controllers
 * @author   ChangChang Team <team@changchang.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/changchang
 */
class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     *
     * @param  Request  $request  The incoming request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'suppliers', 'inventory']);

        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(
                function ($q) use ($searchTerm) {
                    $q->where('ProductName', 'like', "%{$searchTerm}%")
                      ->orWhere('SKU', 'like', "%{$searchTerm}%")
                      ->orWhere('Brand', 'like', "%{$searchTerm}%")
                      ->orWhere('Description', 'like', "%{$searchTerm}%");
                }
            );
        }

        $products = $query->get();
        return view('products.product-list', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        $lastProductId = Product::orderBy('ProductID', 'desc')->value('ProductID') ?? 0;
        return view('products.new-item', compact('categories', 'suppliers', 'lastProductId'));
    }

    /**
     * Store a newly created product in storage.
     *
     * @param Request $request The incoming request containing product data
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        Log::info('Product creation request received', $request->all());

        $validator = Validator::make($request->all(), [
            'ProductName' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    // Check if product with same name exists in the same category
                    $exists = Product::where('ProductName', $value)
                        ->where('CategoryID', $request->CategoryID)
                        ->exists();

                    if ($exists) {
                        $fail('A product with this name already exists in the selected category.');
                    }
                }
            ],
            'Unit' => 'required|string|max:50',
            'CategoryID' => 'required|exists:categories,CategoryID',
            'SupplierID' => 'nullable|exists:suppliers,SupplierID',
            'SKU' => 'required|string|unique:products,SKU',
            'Description' => 'nullable|string',
            'Brand' => 'nullable|string|max:255',
            'Weight' => 'nullable|numeric|min:0',
            'WeightUnit' => 'nullable|string|max:10',
            'SellingPrice' => 'required|numeric|min:0',
            'CostPrice' => 'required|numeric|min:0',
            'OpeningStock' => 'nullable|integer|min:0',
            'ReorderLevel' => 'required|integer|min:0',
            'IsReturnable' => 'boolean',
            'Product_Image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120' // 5MB max
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed', ['errors' => $validator->errors()->toArray()]);
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            Log::info('Starting product creation transaction');

            $data = $request->all();
            $data['IsReturnable'] = $request->has('IsReturnable');

            // Handle image upload
            if ($request->hasFile('Product_Image')) {
                Log::info('Processing image upload');
                $imagePath = $request->file('Product_Image')->store('products', 'public');
                Log::info('Image stored at path: ' . $imagePath);
                $data['Product_Image'] = $imagePath;
            }

            // Create product
            Log::info('Creating product with data', $data);
            $product = Product::create($data);
            Log::info('Product created successfully', ['product_id' => $product->ProductID]);

            // Create initial inventory record
            Log::info('Creating inventory record');
            $product->inventory()->create([
                'QuantityOnHand' => $request->OpeningStock ?? 0,
                'ReorderLevel' => $request->ReorderLevel ?? 0,
                'LastUpdated' => now()
            ]);

            // Create purchase record for initial stock
            Log::info('Creating initial stock purchase record');
            $purchase = PurchaseRecord::create([
                'SupplierID' => $request->SupplierID,
                'ProductID' => $product->ProductID,
                'Quantity' => $request->OpeningStock,
                'UnitPrice' => $request->CostPrice,
                'TotalAmount' => $request->OpeningStock * $request->CostPrice,
                'ReferenceNumber' => 'INIT-' . str_pad($product->ProductID, 5, '0', STR_PAD_LEFT) . '-' . date('YmdHis'),
                'Notes' => 'Initial stock purchase'
            ]);

            // Create transaction record
            Log::info('Creating initial stock transaction record');
            Transaction::create([
                'ProductID' => $product->ProductID,
                'TransactionType' => 'OPENING_STOCK',
                'TransactionDate' => now(),
                'QuantityChange' => $request->OpeningStock,
                'UnitPrice' => $request->CostPrice,
                'TotalAmount' => $request->OpeningStock * $request->CostPrice,
                'ReferenceType' => 'purchase',
                'ReferenceID' => $purchase->PurchaseID,
                'Notes' => $request->SupplierID ? "Initial stock from supplier" : 'Initial stock recorded'
            ]);

            // Handle supplier relationship if provided
            if ($request->has('SupplierID')) {
                Log::info('Creating supplier relationship');
                $product->productSuppliers()->create([
                    'SupplierID' => $request->SupplierID,
                    'PurchasePrice' => $request->CostPrice // Using CostPrice as PurchasePrice
                ]);
            }

            DB::commit();
            Log::info('Product creation completed successfully');
            return redirect()->route('products.index')
                ->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating product: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->with('error', 'Error creating product: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified product.
     *
     * @param Product $product The product to display
     *
     * @return \Illuminate\View\View
     */
    public function show(Product $product)
    {
        $product->load(['category', 'suppliers', 'transactions']);
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     *
     * @param Product $product The product to edit
     *
     * @return \Illuminate\View\View
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    /**
     * Update the specified product in storage.
     *
     * @param Request $request The incoming request containing updated product data
     * @param Product $product The product to update
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Product $product)
    {
        Log::info('Update method called for product: ' . $product->ProductID);
        Log::info('Request data:', $request->all());

        $validator = Validator::make($request->all(), [
            'ProductName' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($request, $product) {
                    // Check if product with same name exists in the same category, excluding current product
                    $exists = Product::where('ProductName', $value)
                        ->where('CategoryID', $request->CategoryID)
                        ->where('ProductID', '!=', $product->ProductID)
                        ->exists();

                    if ($exists) {
                        $fail('A product with this name already exists in the selected category.');
                    }
                }
            ],
            'Unit' => 'required|string|max:50',
            'CategoryID' => 'required|exists:categories,CategoryID',
            'SupplierID' => 'nullable|exists:suppliers,SupplierID',
            'Description' => 'nullable|string',
            'Brand' => 'nullable|string|max:255',
            'Weight' => 'nullable|numeric|min:0',
            'WeightUnit' => 'nullable|string|max:10',
            'SellingPrice' => 'required|numeric|min:0',
            'CostPrice' => 'required|numeric|min:0',
            'stock_adjustment' => 'nullable|integer',
            'ReorderLevel' => 'required|integer|min:0',
            'IsReturnable' => 'boolean',
            'Product_Image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120'
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed:', $validator->errors()->toArray());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->all();
            $data['IsReturnable'] = $request->has('IsReturnable');

            // Handle image upload
            if ($request->hasFile('Product_Image')) {
                // Delete old image if exists
                if ($product->Product_Image) {
                    Storage::disk('public')->delete($product->Product_Image);
                }
                $imagePath = $request->file('Product_Image')->store('products', 'public');
                $data['Product_Image'] = $imagePath;
            }

            // Update product
            $product->update($data);

            // Update inventory if stock changes are needed
            if ($request->has('stock_adjustment')) {
                $oldQuantity = $product->inventory->QuantityOnHand;
                $adjustment = $request->stock_adjustment; // This is the amount to add/subtract
                $newTotalQuantity = $oldQuantity + $adjustment;

                // Update inventory by adjusting the current stock
                $product->inventory()->update([
                    'QuantityOnHand' => $newTotalQuantity,
                    'ReorderLevel' => $request->ReorderLevel,
                    'LastUpdated' => now()->setTimezone(config('app.timezone')),
                ]);

                // Only create log if there's an actual stock change
                if ($adjustment != 0) {
                    InventoryLog::create([
                        'ProductID' => $product->ProductID,
                        'type' => $adjustment > 0 ? 'stock_in' : 'stock_out',
                        'quantity' => abs($adjustment),
                        'notes' => 'Stock adjustment during product update',
                        'created_by' => Auth::id()
                    ]);
                }
            }

            // Update supplier relationship if provided
            if ($request->has('SupplierID')) {
                $product->productSuppliers()->delete(); // Remove old relationships
                $product->productSuppliers()->create([
                    'SupplierID' => $request->SupplierID,
                    'PurchasePrice' => $request->CostPrice
                ]);
            }

            // If stock has changed, create a purchase record and transaction
            $currentStock = $product->inventory->QuantityOnHand;
            $newStock = $request->stock_adjustment ? ($currentStock + $request->stock_adjustment) : $currentStock;
            $stockDifference = $newStock - $currentStock;

            Log::info('Stock calculation details:', [
                'current_stock' => $currentStock,
                'new_stock' => $newStock,
                'stock_difference' => $stockDifference,
                'stock_adjustment' => $request->stock_adjustment,
                'request_opening_stock' => $request->OpeningStock
            ]);

            // Create purchase record for stock update if there's a difference
            if ($stockDifference != 0) {
                try {
                    // Create purchase record for stock update
                    Log::info('Creating stock purchase record with data:', [
                        'SupplierID' => $request->SupplierID,
                        'ProductID' => $product->ProductID,
                        'Quantity' => abs($stockDifference),
                        'UnitPrice' => $request->CostPrice,
                        'TotalAmount' => abs($stockDifference) * $request->CostPrice
                    ]);

                    $purchase = new PurchaseRecord();
                    $purchase->SupplierID = $request->SupplierID;
                    $purchase->ProductID = $product->ProductID;
                    $purchase->Quantity = abs($stockDifference);
                    $purchase->UnitPrice = $request->CostPrice;
                    $purchase->TotalAmount = abs($stockDifference) * $request->CostPrice;
                    $purchase->ReferenceNumber = 'ADJ-' . str_pad($product->ProductID, 5, '0', STR_PAD_LEFT) . '-' . date('YmdHis');
                    $purchase->Notes = 'Stock adjustment during product update';
                    $purchase->save();

                    Log::info('Purchase record created:', ['purchase_id' => $purchase->PurchaseID]);

                    // Create transaction record
                    Transaction::create([
                        'ProductID' => $product->ProductID,
                        'TransactionType' => 'STOCK_PURCHASE',
                        'QuantityChange' => $stockDifference,
                        'UnitPrice' => $request->CostPrice,
                        'TotalAmount' => abs($stockDifference) * $request->CostPrice,
                        'ReferenceType' => 'purchase',
                        'ReferenceID' => $purchase->PurchaseID,
                        'Notes' => 'Stock adjustment during product update'
                    ]);

                    // Update inventory quantity
                    $product->inventory()->update([
                        'QuantityOnHand' => $newStock
                    ]);

                    // Create inventory log
                    InventoryLog::create([
                        'ProductID' => $product->ProductID,
                        'type' => $stockDifference > 0 ? 'stock_in' : 'stock_out',
                        'quantity' => abs($stockDifference),
                        'notes' => 'Stock adjustment during product update',
                        'created_by' => Auth::id()
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error creating purchase record: ' . $e->getMessage());
                    throw $e;
                }
            } else {
                Log::info('No stock difference detected, skipping purchase record creation');
            }

            DB::commit();
            Log::info('Product updated successfully');
            return redirect()->route('products.index')
                ->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating product: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error updating product: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified product from storage.
     *
     * @param Product $product The product to delete
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product)
    {
        try {
            DB::beginTransaction();

            // Delete product image if exists
            if ($product->Product_Image) {
                Storage::disk('public')->delete($product->Product_Image);
            }

            // Delete related records
            $product->productSuppliers()->delete();
            $product->inventory()->delete();
            $product->transactions()->delete();
            $product->salesItems()->delete();

            // Delete the product
            $product->delete();

            DB::commit();
            return redirect()->route('products.index')
                ->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting product: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error deleting product: ' . $e->getMessage());
        }
    }

    /**
     * Get product inventory status.
     *
     * @param Product $product The product to check inventory for
     *
     * @return \Illuminate\View\View
     */
    public function inventoryStatus(Product $product)
    {
        $product->load(['transactions', 'inventory']);
        return view('product-transaction', compact('product'));
    }

    /**
     * Get all categories for dropdown.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategories()
    {
        $categories = Category::orderBy('CategoryName')->get(['CategoryID', 'CategoryName']);
        return response()->json($categories);
    }

    /**
     * Get all suppliers for dropdown.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSuppliers()
    {
        $suppliers = Supplier::orderBy('SupplierName')->get(['SupplierID', 'SupplierName']);
        return response()->json($suppliers);
    }

    /**
     * Get the current stock for a product
     *
     * @param int $id The product ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStock($id)
    {
        $product = Product::with('inventory')->findOrFail($id);
        return response()->json([
            'quantity' => $product->inventory->QuantityOnHand ?? 0
        ]);
    }
}
