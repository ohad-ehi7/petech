<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();

        $today = Carbon::today();
        $dateRange = $request->input('date_range', 'this_month');
        $startDate = $this->getStartDate($dateRange);
        $endDate = $this->getEndDate($dateRange);

        // Si caissier, filtrer par ClerkID
        $salesQuery = Sale::query();
        if ($user->RoleID === 3) { // 3 = Caissier
            $salesQuery->where('ClerkID', $user->id);
        }

        // Today sales
        $todaySales = (clone $salesQuery)
            ->whereDate('SaleDate', $today)
            ->sum('TotalAmount');

        // Items sold today
        $itemsSoldToday = DB::table('sales_items')
            ->join('sales', 'sales_items.SaleID', '=', 'sales.SaleID')
            ->when($user->RoleID === 3, function ($q) use ($user) {
                $q->where('sales.ClerkID', $user->id);
            })
            ->whereDate('sales.SaleDate', $today)
            ->sum('sales_items.Quantity');

        // Transactions today
        $transactionsToday = (clone $salesQuery)->whereDate('SaleDate', $today)->count();

        $voidTransactionsToday = 0; // à adapter si tu as un statut

        // Inventory summary reste identique
        $inventorySummary = [
            'quantity_in_hand' => Inventory::sum('QuantityOnHand'),
            'quantity_to_receive' => 0,
            'low_stock_items' => DB::table('products')
                ->join('inventories', 'products.ProductID', '=', 'inventories.ProductID')
                ->whereRaw('inventories.QuantityOnHand <= (products.OpeningStock/2 - 1)')
                ->count(),
            'total_items' => Product::count(),
            'active_items' => Product::whereHas('inventory', fn($q) => $q->where('QuantityOnHand', '>', 0))->count(),
        ];

        // Top selling items
        $topSellingItems = DB::table('sales_items')
            ->join('products', 'sales_items.ProductID', '=', 'products.ProductID')
            ->join('sales', 'sales_items.SaleID', '=', 'sales.SaleID')
            ->join('categories', 'products.CategoryID', '=', 'categories.CategoryID')
            ->when($user->RoleID === 3, fn($q) => $q->where('sales.ClerkID', $user->id))
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

        // Sales history
        $salesHistory = [
            'draft' => 0,
            'confirmed' => (clone $salesQuery)->whereBetween('SaleDate', [$startDate, $endDate])->where('TotalAmount', '>', 0)->count(),
            'packed' => 0,
            'shipped' => 0,
            'invoiced' => (clone $salesQuery)->whereBetween('SaleDate', [$startDate, $endDate])->where('TotalAmount', '>', 0)->count()
        ];

        // Monthly sales chart
        $monthlySales = (clone $salesQuery)->whereBetween('SaleDate', [$startDate, $endDate])
            ->select(DB::raw('DATE(SaleDate) as date'), DB::raw('CAST(SUM(TotalAmount) AS DECIMAL(10,2)) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $monthlyTotal = (clone $salesQuery)->whereBetween('SaleDate', [$startDate, $endDate])
            ->sum('TotalAmount');

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
        return match ($dateRange) {
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
        return match ($dateRange) {
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
