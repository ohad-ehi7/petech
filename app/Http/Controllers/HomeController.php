<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Get today's date
        $today = Carbon::today();
        
        // Get sales data for today
        $todaySales = DB::table('sales')
            ->whereDate('SaleDate', $today)
            ->select(DB::raw('ROUND(SUM(TotalAmount)) as total'))
            ->value('total') ?? 0;
            
        // Get items sold today
        $itemsSoldToday = DB::table('sales_items')
            ->join('sales', 'sales_items.SaleID', '=', 'sales.SaleID')
            ->whereDate('sales.SaleDate', $today)
            ->sum('sales_items.Quantity');
            
        // Get number of transactions today
        $transactionsToday = Sale::whereDate('SaleDate', $today)
            ->count();
            
        // Get void transactions today (since we don't have status, this will be 0)
        $voidTransactionsToday = 0;
            
        // Get inventory summary
        $inventorySummary = [
            'quantity_in_hand' => Inventory::sum('QuantityOnHand'),
            'quantity_to_receive' => 0, // This would come from purchase orders
            'low_stock_items' => DB::table('products')
                ->join('inventories', 'products.ProductID', '=', 'inventories.ProductID')
                ->whereRaw('inventories.QuantityOnHand <= (products.OpeningStock/2 - 1)')
                ->count(),
            'total_items' => Product::count(),
            'active_items' => Product::whereHas('inventory', function($query) {
                $query->where('QuantityOnHand', '>', 0);
            })->count()
        ];
        
        // Get date range for filtering
        $dateRange = $request->input('date_range', 'this_month');
        $startDate = $this->getStartDate($dateRange);
        $endDate = $this->getEndDate($dateRange);
        
        // Get top selling items based on date range
        $topSellingItems = DB::table('sales_items')
            ->join('products', 'sales_items.ProductID', '=', 'products.ProductID')
            ->join('sales', 'sales_items.SaleID', '=', 'sales.SaleID')
            ->join('categories', 'products.CategoryID', '=', 'categories.CategoryID')
            ->whereBetween('sales.SaleDate', [$startDate, $endDate])
            ->select(
                'products.ProductName',
                'categories.CategoryName',
                DB::raw('CAST(SUM(sales_items.Quantity) AS DECIMAL(10,2)) as total_quantity'),
                DB::raw('CAST(SUM(sales_items.Quantity * sales_items.PriceAtSale) AS DECIMAL(10,2)) as total_sales')
            )
            ->groupBy('products.ProductID', 'products.ProductName', 'categories.CategoryName')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();
            
        // Get sales history by status
        $salesHistory = [
            'draft' => 0, // Since we don't have draft status
            'confirmed' => Sale::whereBetween('SaleDate', [$startDate, $endDate])
                ->where('TotalAmount', '>', 0)
                ->count(),
            'packed' => 0, // Since we don't have packed status
            'shipped' => 0, // Since we don't have shipped status
            'invoiced' => Sale::whereBetween('SaleDate', [$startDate, $endDate])
                ->where('TotalAmount', '>', 0)
                ->count()
        ];
        
        // Get monthly sales data for the chart
        $monthlySales = Sale::whereBetween('SaleDate', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(SaleDate) as date'),
                DB::raw('CAST(SUM(TotalAmount) AS DECIMAL(10,2)) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        // Get total sales for the period
        $monthlyTotal = DB::table('sales')
            ->whereBetween('SaleDate', [$startDate, $endDate])
            ->select(DB::raw('CAST(SUM(TotalAmount) AS DECIMAL(10,2)) as total'))
            ->value('total') ?? 0;

        // Get all products with their inventory
        $products = Product::with('inventory')->get();

        return view('home', compact(
            'todaySales',
            'itemsSoldToday',
            'transactionsToday',
            'voidTransactionsToday',
            'inventorySummary',
            'topSellingItems',
            'salesHistory',
            'monthlySales',
            'monthlyTotal',
            'dateRange',
            'products'
        ));
    }

    private function getStartDate($dateRange)
    {
        return match($dateRange) {
            'today' => Carbon::today(),
            'yesterday' => Carbon::yesterday(),
            'this_week' => Carbon::now()->startOfWeek(),
            'last_week' => Carbon::now()->subWeek()->startOfWeek(),
            'this_month' => Carbon::now()->startOfMonth(),
            'last_month' => Carbon::now()->subMonth()->startOfMonth(),
            'this_year' => Carbon::now()->startOfYear(),
            'last_year' => Carbon::now()->subYear()->startOfYear(),
            default => Carbon::now()->startOfMonth(),
        };
    }

    private function getEndDate($dateRange)
    {
        return match($dateRange) {
            'today' => Carbon::today()->endOfDay(),
            'yesterday' => Carbon::yesterday()->endOfDay(),
            'this_week' => Carbon::now()->endOfWeek(),
            'last_week' => Carbon::now()->subWeek()->endOfWeek(),
            'this_month' => Carbon::now()->endOfMonth(),
            'last_month' => Carbon::now()->subMonth()->endOfMonth(),
            'this_year' => Carbon::now()->endOfYear(),
            'last_year' => Carbon::now()->subYear()->endOfYear(),
            default => Carbon::now()->endOfMonth(),
        };
    }
} 