<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class POSController extends Controller
{
    /**
     * Display the point of sale interface.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $categories = Category::all();
       
        $products = Product::with(['category', 'inventory'])
    ->whereHas('inventory', function($query) {
        $query->where('QuantityOnHand', '>', 0);
    })
    ->paginate(30); // <-- Pagination de 20 produits

       

        return view('sales.point-of-sale', compact('categories', 'products'));
    }

    // autre verrion
    // public function index()
    // {
    //     $categories = Category::all();
    //     $products = Product::with(['category', 'inventory'])
    //         ->whereHas('inventory', function($query) {
    //             $query->where('QuantityOnHand', '>', 0);
    //         })
    //         ->get();

    //     return view('sales.point-of-sale', compact('categories', 'products'));
    // }
}
