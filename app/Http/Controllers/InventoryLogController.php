<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InventoryLogController extends Controller
{
    public function index()
    {
        // Get inventory logs with related product information
        $inventoryLogs = DB::table('inventory_logs')
            ->join('products', 'inventory_logs.ProductID', '=', 'products.ProductID')
            ->join('categories', 'products.CategoryID', '=', 'categories.CategoryID')
            ->select(
                'inventory_logs.*',
                'products.ProductName',
                'products.SKU',
                'categories.CategoryName'
            )
            ->orderBy('inventory_logs.created_at', 'desc')
            ->get();

        // Get summary statistics
        $summary = [
            'total_adjustments' => $inventoryLogs->count(),
            'stock_in' => $inventoryLogs->where('type', 'stock_in')->sum('quantity'),
            'stock_out' => $inventoryLogs->where('type', 'stock_out')->sum('quantity'),
            'adjustments' => $inventoryLogs->where('type', 'adjustment')->count(),
        ];

        // Get recent activity
        $recentActivity = $inventoryLogs->take(5);

        // Get top adjusted products
        $topAdjustedProducts = DB::table('inventory_logs')
            ->join('products', 'inventory_logs.ProductID', '=', 'products.ProductID')
            ->select(
                'products.ProductName',
                'products.SKU',
                DB::raw('COUNT(*) as adjustment_count'),
                DB::raw('SUM(CASE WHEN type = "stock_in" THEN quantity ELSE 0 END) as total_stock_in'),
                DB::raw('SUM(CASE WHEN type = "stock_out" THEN quantity ELSE 0 END) as total_stock_out')
            )
            ->groupBy('products.ProductID', 'products.ProductName', 'products.SKU')
            ->orderBy('adjustment_count', 'desc')
            ->limit(5)
            ->get();

        return view('inventory-logs', compact('inventoryLogs', 'summary', 'recentActivity', 'topAdjustedProducts'));
    }
} 