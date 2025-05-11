<?php
/**
 * Supplier Controller
 *
 * Handles all supplier-related operations including CRUD operations.
 *
 * @category Controllers
 * @package  App\Http\Controllers
 * @author   ChangChang Team <team@changchang.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/changchang
 */

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

/**
 * Class SupplierController
 *
 * Manages supplier operations including creation, updates, and deletion.
 *
 * @category Controllers
 * @package  App\Http\Controllers
 * @author   ChangChang Team <team@changchang.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/changchang
 */
class SupplierController extends Controller
{
    /**
     * Display a listing of the suppliers.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $suppliers = Supplier::with('products')->get();
        return view('supplier.supplier-list', compact('suppliers'));
    }

    /**
     * Show the form for creating a new supplier.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('supplier.add-supplier');
    }

    /**
     * Store a newly created supplier in storage.
     *
     * @param Request $request The incoming request containing supplier data
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'SupplierName' => 'required|string|max:255',
                'ContactNumber' => 'nullable|string|max:20',
                'Email' => 'nullable|email|max:255',
                'Address' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            Supplier::create($request->all());

            DB::commit();
            return redirect()->route('suppliers.index')
                ->with('success', 'Supplier created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error creating supplier: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified supplier.
     *
     * @param Supplier $supplier The supplier to display
     *
     * @return \Illuminate\View\View
     */
    public function show(Supplier $supplier)
    {
        $supplier->load('products');
        return view('supplier-overview', compact('supplier'));
    }

    /**
     * Show the form for editing the specified supplier.
     *
     * @param Supplier $supplier The supplier to edit
     *
     * @return \Illuminate\View\View
     */
    public function edit(Supplier $supplier)
    {
        return view('supplier.edit-supplier', compact('supplier'));
    }

    /**
     * Update the specified supplier in storage.
     *
     * @param Request   $request   The incoming request containing updated supplier data
     * @param Supplier  $supplier  The supplier to update
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Supplier $supplier)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'SupplierName' => [
                    'required',
                    'string',
                    'max:255',
                    function ($attribute, $value, $fail) use ($request, $supplier) {
                        $exists = Supplier::where('SupplierName', $value)
                            ->where('SupplierID', '!=', $supplier->SupplierID)
                            ->exists();
                        
                        if ($exists) {
                            $fail('A supplier with this name already exists.');
                        }
                    }
                ],
                'ContactPerson' => 'nullable|string|max:255',
                'Email' => 'nullable|email|max:255',
                'Phone' => 'nullable|string|max:20',
                'Address' => 'nullable|string',
                'Status' => 'required|in:active,inactive'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $supplier->update($request->all());

            DB::commit();
            return redirect()->route('suppliers.index')
                ->with('success', 'Supplier updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error updating supplier: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified supplier from storage.
     *
     * @param Supplier $supplier The supplier to delete
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Supplier $supplier)
    {
        try {
            DB::beginTransaction();

            // Check if supplier has products
            if ($supplier->products()->exists()) {
                throw new \Exception('Cannot delete supplier with associated products.');
            }

            $supplier->delete();

            DB::commit();
            return redirect()->route('suppliers.index')
                ->with('success', 'Supplier deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error deleting supplier: ' . $e->getMessage());
        }
    }
}
