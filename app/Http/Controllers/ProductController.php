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
use Illuminate\Http\Request;
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
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $products = Product::with(['category', 'suppliers'])->get();
        return view('product-list', compact('products'));
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
        return view('new-item', compact('categories', 'suppliers'));
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
            'ProductName' => 'required|string|max:255',
            'Unit' => 'required|string|max:50',
            'CategoryID' => 'required|exists:categories,CategoryID',
            'SupplierID' => 'nullable|exists:suppliers,SupplierID',
            'SKU' => 'nullable|string|unique:products,SKU',
            'Description' => 'nullable|string',
            'Brand' => 'nullable|string|max:255',
            'Weight' => 'nullable|numeric|min:0',
            'WeightUnit' => 'nullable|string|max:10',
            'SellingPrice' => 'required|numeric|min:0',
            'CostPrice' => 'required|numeric|min:0',
            'OpeningStock' => 'nullable|integer|min:0',
            'ReorderLevel' => 'nullable|integer|min:0',
            'IsReturnable' => 'boolean',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120' // 5MB max
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $data['IsReturnable'] = $request->has('IsReturnable');

        // Handle image upload
        if ($request->hasFile('product_image')) {
            $imagePath = $request->file('product_image')->store('products', 'public');
            $data['ImagePath'] = $imagePath;
        }

        $product = Product::create($data);

        // Handle supplier relationship if provided
        if ($request->has('SupplierID')) {
            $product->productSuppliers()->create([
                'SupplierID' => $request->SupplierID
            ]);
        }

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
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
        return view('product-overview', compact('product'));
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
        return view('edit-item', compact('product', 'categories', 'suppliers'));
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
        $validator = Validator::make($request->all(), [
            'ProductName' => 'required|string|max:255',
            'Unit' => 'required|string|max:50',
            'CategoryID' => 'required|exists:categories,CategoryID',
            'SupplierID' => 'nullable|exists:suppliers,SupplierID',
            'SKU' => 'nullable|string|unique:products,SKU,' . $product->ProductID . ',ProductID',
            'Description' => 'nullable|string',
            'Brand' => 'nullable|string|max:255',
            'Weight' => 'nullable|numeric|min:0',
            'WeightUnit' => 'nullable|string|max:10',
            'SellingPrice' => 'required|numeric|min:0',
            'CostPrice' => 'required|numeric|min:0',
            'OpeningStock' => 'nullable|integer|min:0',
            'ReorderLevel' => 'nullable|integer|min:0',
            'IsReturnable' => 'boolean',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $data['IsReturnable'] = $request->has('IsReturnable');

        // Handle image upload
        if ($request->hasFile('product_image')) {
            // Delete old image if exists
            if ($product->ImagePath) {
                Storage::disk('public')->delete($product->ImagePath);
            }
            $imagePath = $request->file('product_image')->store('products', 'public');
            $data['ImagePath'] = $imagePath;
        }

        $product->update($data);

        // Update supplier relationship if provided
        if ($request->has('SupplierID')) {
            $product->productSuppliers()->delete(); // Remove old relationships
            $product->productSuppliers()->create([
                'SupplierID' => $request->SupplierID
            ]);
        }

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
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
        // Delete product image if exists
        if ($product->ImagePath) {
            Storage::disk('public')->delete($product->ImagePath);
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
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
}
