<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Exception;

class CustomerController extends Controller
{
    /**
     * Display a listing of the customers.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $customers = Customer::all();
        return view('customer.customer-list', compact('customers'));
    }

    /**
     * Show the form for creating a new customer.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('customer.new-customer');
    }

    /**
     * Store a newly created customer in storage.
     *
     * @param Request $request The incoming request containing customer data
     *
     * @return \Illuminate\Http\RedirectResponse
     */

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Si l'utilisateur essaie de créer "Cash", on bloque
            if (strtolower(trim($request->fullname)) === 'cash') {
                return redirect()->back()
                    ->with('error', 'Impossible de créer un client nommé "Cash".');
            }

            $validator = Validator::make(
                $request->all(),
                [
                    'fullname' => 'required|string|max:255',
                    'nif_cin'  => 'nullable|string|max:20|unique:customers,nif_cin',
                    'phone'    => 'required|string|max:20|unique:customers,phone',
                    'address'  => 'nullable|string|max:255'
                ],
                [
                    'fullname.required' => 'Le nom complet est obligatoire.',
                    'nif_cin.unique'    => 'Ce NIF/CIN est déjà utilisé.',
                    'phone.required'    => 'Le numéro de téléphone est obligatoire.',
                    'phone.unique'      => 'Ce numéro de téléphone existe déjà.',
                ]
            );

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            Customer::create([
                'fullname' => $request->fullname,
                'nif_cin'  => $request->nif_cin,
                'phone'    => $request->phone,
                'address'  => $request->address,
            ]);

            DB::commit();
            return redirect()->route('customers.index')
                ->with('success', 'Client créé avec succès.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erreur lors de la création du client: ' . $e->getMessage())
                ->withInput();
        }
    }
    /**
     * Show the form for editing the specified customer.
     *
     * @param Customer $customer The customer to edit
     *
     * @return \Illuminate\View\View
     */
    public function edit(Customer $customer)
    {
        return view('customer.edit-customer', compact('customer'));
    }

    /**
     * Update the specified customer in storage.
     *
     * @param Request  $request  The incoming request containing updated customer data
     * @param Customer $customer The customer to update
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Customer $customer)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make(
                $request->all(),
                [
                    'fullname' => 'required|string|max:255',
                    'nif_cin' => [
                        'required',
                        'integer',
                        function ($attribute, $value, $fail) use ($request, $customer) {
                            $exists = Customer::where('nif_cin', $value)
                                ->where('CustomerID', '!=', $customer->CustomerID)
                                ->exists();

                            if ($exists) {
                                $fail('Un client avec ce NIF/CIN existe déjà.');
                            }
                        }
                    ],
                    'phone' => [
                        'required',
                        'integer',
                        function ($attribute, $value, $fail) use ($request, $customer) {
                            $exists = Customer::where('phone', $value)
                                ->where('CustomerID', '!=', $customer->CustomerID)
                                ->exists();

                            if ($exists) {
                                $fail('Un client avec ce numéro de téléphone existe déjà.');
                            }
                        }
                    ],
                    'address' => 'required|string|max:255'
                ]
            );

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $customer->update($request->all());

            DB::commit();
            return redirect()->route('customers.index')
                ->with('success', 'Client mis à jour avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour du client: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified customer from storage.
     *
     * @param Customer $customer The customer to delete
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Customer $customer)
    {
        try {
            DB::beginTransaction();

            // Vérifier si le client a déjà des ventes
            $hasSales = DB::table('sales')
                ->where('CustomerID', $customer->CustomerID)
                ->exists();

            if ($hasSales) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', "Impossible de supprimer ce client car il possède déjà  ventes.");
            }

            // Suppression si aucune vente
            $customer->delete();

            DB::commit();
            return redirect()->route('customers.index')
                ->with('success', 'Client supprimé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression du client: ' . $e->getMessage());
        }
    }


    // Récupérer un fullname aléatoire
    public function randomFullname()
    {
        $customer = Customer::inRandomOrder()->first();

        if ($customer) {
            return response()->json(['fullname' => $customer->fullname]);
        }

        return response()->json(['error' => 'Aucun client trouvé'], 404);
    }
}
