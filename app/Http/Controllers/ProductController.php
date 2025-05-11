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
use App\Models\InventoryLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
                $imagePath = $request->file('Product_Image')->store('products', 'public');
                \Log::info('Storing image at path: ' . $imagePath);
                \Log::info('Full storage path: ' . storage_path('app/public/' . $imagePath));
                $data['Product_Image'] = $imagePath;
            }

            // Create product
            $product = Product::create($data);

            // Create initial inventory record
            $product->inventory()->create([
                'QuantityOnHand' => $request->OpeningStock ?? 0,
                'ReorderLevel' => $request->ReorderLevel ?? 0,
                'LastUpdated' => now(),
                'Status' => 'active',
            ]);

            // Create inventory log for initial stock
            if ($request->OpeningStock > 0) {
                InventoryLog::create([
                    'ProductID' => $product->ProductID,
                    'type' => 'stock_in',
                    'quantity' => $request->OpeningStock,
                    'notes' => 'Initial stock',
                    'created_by' => Auth::id()
                ]);
            }

            // Handle supplier relationship if provided
            if ($request->has('SupplierID')) {
                $product->productSuppliers()->create([
                    'SupplierID' => $request->SupplierID,
                    'PurchasePrice' => $request->CostPrice // Using CostPrice as PurchasePrice
                ]);
            }

            DB::commit();
            return redirect()->route('products.index')
                ->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating product: ' . $e->getMessage());
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
        \Log::info('Update method called for product: ' . $product->ProductID);
        \Log::info('Request data:', $request->all());

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
            'OpeningStock' => 'nullable|integer|min:0',
            'ReorderLevel' => 'required|integer|min:0',
            'IsReturnable' => 'boolean',
            'Product_Image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120'
        ]);

        if ($validator->fails()) {
            \Log::error('Validation failed:', $validator->errors()->toArray());
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

            // Update inventory if OpeningStock changed
            if ($request->has('OpeningStock') && $request->OpeningStock != $product->inventory->QuantityOnHand) {
                $oldQuantity = $product->inventory->QuantityOnHand;
                $newQuantity = $request->OpeningStock;
                $quantityChange = $newQuantity - $oldQuantity;

                $product->inventory()->update([
                    'QuantityOnHand' => $newQuantity,
                    'ReorderLevel' => $request->ReorderLevel,
                    'LastUpdated' => now()
                ]);

                // Create inventory log for the adjustment
                InventoryLog::create([
                    'ProductID' => $product->ProductID,
                    'type' => $quantityChange > 0 ? 'stock_in' : 'stock_out',
                    'quantity' => abs($quantityChange),
                    'notes' => 'Stock adjustment during product update',
                    'created_by' => Auth::id()
                ]);
            }

            // Update supplier relationship if provided
            if ($request->has('SupplierID')) {
                $product->productSuppliers()->delete(); // Remove old relationships
                $product->productSuppliers()->create([
                    'SupplierID' => $request->SupplierID,
                    'PurchasePrice' => $request->CostPrice
                ]);
            }

            DB::commit();
            \Log::info('Product updated successfully');
            return redirect()->route('products.index')
                ->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating product: ' . $e->getMessage());
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
            \Log::error('Error deleting product: ' . $e->getMessage());
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
