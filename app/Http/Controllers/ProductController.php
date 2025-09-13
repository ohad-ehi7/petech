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
            'ProductName'   => 'required|string|max:255',
            'Unit'          => 'required|string|max:50',
            'CategoryID'    => 'required|exists:categories,CategoryID',
            'SupplierID'    => 'nullable|exists:suppliers,SupplierID',
            'SKU'           => 'required|string|unique:products,SKU',
            'Description'   => 'nullable|string',
            'Brand'         => 'nullable|string|max:255',
            'Weight'        => 'nullable|numeric|min:0',
            'WeightUnit'    => 'nullable|string|max:10',
            'SellingPrice'  => 'required|numeric|min:0',
            'CostPrice'     => 'required|numeric|min:0',
            'OpeningStock'  => 'nullable|integer|min:0',
            'ReorderLevel'  => 'required|integer|min:0',
            'IsReturnable'  => 'boolean',
            'Product_Image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120'
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

            // 📌 Gestion du fournisseur par défaut
            if (empty($data['SupplierID'])) {
                // Récupérer l'ID du fournisseur "Local"
                $localSupplier = Supplier::where('SupplierName', 'Local')->first();
                $data['SupplierID'] = $localSupplier ? $localSupplier->SupplierID : null;
            }

            // 📌 Gestion de l'image
            if ($request->hasFile('Product_Image')) {
                $file = $request->file('Product_Image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('assets/images/products'), $filename);
                $data['Product_Image'] = 'assets/images/products/' . $filename;
            }

            $product = Product::create($data);

            // Inventaire initial
            $product->inventory()->create([
                'QuantityOnHand' => $request->OpeningStock ?? 0,
                'ReorderLevel'   => $request->ReorderLevel ?? 0,
                'LastUpdated'    => now()
            ]);

            // Achat initial
            $purchase = PurchaseRecord::create([
                'SupplierID'      => $data['SupplierID'],
                'ProductID'       => $product->ProductID,
                'Quantity'        => $request->OpeningStock,
                'UnitPrice'       => $request->CostPrice,
                'TotalAmount'     => ($request->OpeningStock ?? 0) * $request->CostPrice,
                'ReferenceNumber' => 'INIT-' . str_pad($product->ProductID, 5, '0', STR_PAD_LEFT) . '-' . date('YmdHis'),
                'Notes'           => 'Initial stock purchase'
            ]);

            // Transaction initiale
            Transaction::create([
                'ProductID'       => $product->ProductID,
                'TransactionType' => 'OPENING_STOCK',
                'TransactionDate' => now(),
                'QuantityChange'  => $request->OpeningStock,
                'UnitPrice'       => $request->CostPrice,
                'TotalAmount'     => ($request->OpeningStock ?? 0) * $request->CostPrice,
                'ReferenceType'   => 'purchase',
                'ReferenceID'     => $purchase->PurchaseID,
                'Notes'           => $data['SupplierID'] ? "Initial stock from supplier" : 'Initial stock recorded'
            ]);

            DB::commit();

            return redirect()->route('products.index')->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating product: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Error creating product: ' . $e->getMessage())->withInput();
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
                $exists = Product::where('ProductName', $value)
                    ->where('CategoryID', $request->CategoryID)
                    ->where('ProductID', '!=', $product->ProductID)
                    ->exists();

                if ($exists) {
                    $fail('A product with this name already exists in the selected category.');
                }
            }
        ],
        'Unit'          => 'required|string|max:50',
        'CategoryID'    => 'required|exists:categories,CategoryID',
        'SupplierID'    => 'nullable|exists:suppliers,SupplierID',
        'Description'   => 'nullable|string',
        'Brand'         => 'nullable|string|max:255',
        'Weight'        => 'nullable|numeric|min:0',
        'WeightUnit'    => 'nullable|string|max:10',
        'SellingPrice'  => 'required|numeric|min:0',
        'CostPrice'     => 'required|numeric|min:0',
        'stock_adjustment' => 'nullable|integer',
        'ReorderLevel'  => 'required|integer|min:0',
        'IsReturnable'  => 'boolean',
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

        // 📌 Gestion du fournisseur par défaut (comme dans store)
        if (empty($data['SupplierID'])) {
            // Récupérer l'ID du fournisseur "Local"
            $localSupplier = Supplier::where('SupplierName', 'Local')->first();
            $data['SupplierID'] = $localSupplier ? $localSupplier->SupplierID : null;
        }

        // 📌 Gestion de l'image avec public/assets/images/products
        if ($request->hasFile('Product_Image')) {
            // Supprimer l'ancienne image si existe
            if ($product->Product_Image && file_exists(public_path($product->Product_Image))) {
                unlink(public_path($product->Product_Image));
            }

            $file = $request->file('Product_Image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/images/products'), $filename);

            $data['Product_Image'] = 'assets/images/products/' . $filename;
        }

        // Update du produit
        $product->update($data);

        // Mise à jour du niveau de réapprovisionnement
        $product->inventory()->update([
            'ReorderLevel' => $request->ReorderLevel,
            'LastUpdated' => now()->setTimezone(config('app.timezone')),
        ]);

        // Mise à jour stock si demandé
        if ($request->has('stock_adjustment') && $request->stock_adjustment != 0) {
            $oldQuantity = $product->inventory->QuantityOnHand;
            $adjustment = $request->stock_adjustment;
            $newTotalQuantity = $oldQuantity + $adjustment;

            $product->inventory()->update([
                'QuantityOnHand' => $newTotalQuantity,
                'LastUpdated'    => now()->setTimezone(config('app.timezone')),
            ]);

            // Création achat + transaction si stock changé
            $purchase = PurchaseRecord::create([
                'SupplierID'      => $data['SupplierID'],
                'ProductID'       => $product->ProductID,
                'Quantity'        => abs($adjustment),
                'UnitPrice'       => $request->CostPrice,
                'TotalAmount'     => abs($adjustment) * $request->CostPrice,
                'ReferenceNumber' => 'ADJ-' . str_pad($product->ProductID, 5, '0', STR_PAD_LEFT) . '-' . date('YmdHis'),
                'Notes'           => 'Stock adjustment during product update'
            ]);

            Transaction::create([
                'ProductID'       => $product->ProductID,
                'TransactionType' => $adjustment > 0 ? 'STOCK_PURCHASE' : 'STOCK_ADJUSTMENT',
                'TransactionDate' => now(),
                'QuantityChange'  => $adjustment,
                'UnitPrice'       => $request->CostPrice,
                'TotalAmount'     => abs($adjustment) * $request->CostPrice,
                'ReferenceType'   => 'purchase',
                'ReferenceID'     => $purchase->PurchaseID,
                'Notes'           => 'Stock adjustment during product update'
            ]);

            InventoryLog::create([
                'ProductID' => $product->ProductID,
                'type'      => $adjustment > 0 ? 'stock_in' : 'stock_out',
                'quantity'  => abs($adjustment),
                'notes'     => 'Stock adjustment during product update',
                'created_by' => Auth::id()
            ]);
        }

        // Mise à jour fournisseur
        if ($request->has('SupplierID')) {
            $product->productSuppliers()->delete();
            $product->productSuppliers()->create([
                'SupplierID'    => $data['SupplierID'],
                'PurchasePrice' => $request->CostPrice
            ]);
        }

        DB::commit();
        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
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


// public function destroy(Product $product)
// {
//     try {
//         // Vérifier si le produit a déjà été vendu
//         if ($product->salesItems()->exists()) {
//             return redirect()->back()
//                 ->with('error', 'Impossible de supprimer ce produit car des ventes ont été réalisées.');
//         }

//         DB::beginTransaction();

//         // Supprimer l’image si elle existe
//         if ($product->Product_Image) {
//             $imagePath = public_path($product->Product_Image);
//             if (file_exists($imagePath)) {
//                 unlink($imagePath);
//             }
//         }

//         // Supprimer les relations
//         $product->productSuppliers()->delete();
//         $product->inventory()->delete();
//         $product->transactions()->delete();
//         $product->salesItems()->delete();

//         // Supprimer le produit
//         $product->delete();

//         DB::commit();
//         return redirect()->route('products.index')
//             ->with('success', 'Produit supprimé avec succès.');
//     } catch (\Exception $e) {
//         DB::rollBack();
//         Log::error('Erreur suppression produit: ' . $e->getMessage());
//         return redirect()->back()
//             ->with('error', 'Erreur suppression produit: ' . $e->getMessage());
//     }
// }
public function destroy(Product $product)
{
    try {
        // Vérifier si le produit a déjà été vendu
        if ($product->salesItems()->exists()) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer ce produit car des ventes ont été réalisées.');
        }

        DB::beginTransaction();

        // Supprimer les enregistrements liés dans purchase_records
        if ($product->purchaseRecords()->exists()) {
            $product->purchaseRecords()->delete();
        }

        // Supprimer l’image si elle existe
        if ($product->Product_Image) {
            $imagePath = public_path($product->Product_Image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        // Supprimer les autres relations
        if ($product->productSuppliers()->exists()) {
            $product->productSuppliers()->delete();
        }

        if ($product->inventory()->exists()) {
            $product->inventory()->delete();
        }

        if ($product->transactions()->exists()) {
            $product->transactions()->delete();
        }

        if ($product->salesItems()->exists()) {
            $product->salesItems()->delete();
        }

        // Supprimer le produit
        $product->delete();

        DB::commit();

        return redirect()->route('products.index')
            ->with('success', 'Produit supprimé avec succès.');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Erreur suppression produit: ' . $e->getMessage());

        return redirect()->back()
            ->with('error', 'Erreur suppression produit: ' . $e->getMessage());
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
