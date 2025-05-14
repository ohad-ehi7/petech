<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionController extends Controller
{
    /**
     * Display a listing of transactions for a specific product
     *
     * @param int $productId The ID of the product
     * @param Request $request The incoming request
     * @return \Illuminate\View\View
     */
    public function productTransactions($productId, Request $request)
    {
        $product = Product::findOrFail($productId);
        $products = Product::with(['inventory'])->get();
        
        // Get transactions
        $query = Transaction::where('ProductID', $productId);
        $transactions = $query->get();

        // Add initial stock as a transaction if exists
        if ($product->OpeningStock > 0) {
            $transactions->prepend((object)[
                'TransactionID' => 'INIT-' . str_pad($product->ProductID, 5, '0', STR_PAD_LEFT),
                'ProductID' => $product->ProductID,
                'TransactionType' => 'OPENING_STOCK',
                'TransactionDate' => $product->created_at,
                'QuantityChange' => $product->OpeningStock,
                'UnitPrice' => $product->CostPrice,
                'TotalAmount' => $product->OpeningStock * $product->CostPrice,
                'ReferenceID' => null
            ]);
        }

        // Add stock updates from inventory logs
        $latestStockUpdate = DB::table('inventory_logs')
            ->where('ProductID', $productId)
            ->where('notes', 'like', 'Stock adjustment during product update%')
            ->latest()
            ->first();

        if ($latestStockUpdate) {
            $transactions->prepend((object)[
                'TransactionID' => 'ADJ-' . str_pad($product->ProductID, 5, '0', STR_PAD_LEFT),
                'ProductID' => $product->ProductID,
                'TransactionType' => 'STOCK_UPDATE',
                'TransactionDate' => Carbon::parse($latestStockUpdate->created_at),
                'QuantityChange' => $latestStockUpdate->type === 'stock_in' ? $latestStockUpdate->quantity : -$latestStockUpdate->quantity,
                'UnitPrice' => $product->CostPrice,
                'TotalAmount' => $latestStockUpdate->quantity * $product->CostPrice,
                'ReferenceID' => null
            ]);
        }

        // Apply sorting to the entire collection
        $sort = $request->get('sort', 'date_desc');
        switch ($sort) {
            case 'date_asc':
                $transactions = $transactions->sortBy('TransactionDate');
                break;
            case 'date_desc':
                $transactions = $transactions->sortByDesc('TransactionDate');
                break;
            case 'amount_asc':
                $transactions = $transactions->sortBy('TotalAmount');
                break;
            case 'amount_desc':
                $transactions = $transactions->sortByDesc('TotalAmount');
                break;
            default:
                $transactions = $transactions->sortByDesc('TransactionDate');
        }

        // Paginate the transactions
        $transactions = new \Illuminate\Pagination\LengthAwarePaginator(
            $transactions->forPage($request->get('page', 1), 10),
            $transactions->count(),
            10,
            $request->get('page', 1),
            ['path' => $request->url()]
        );

        // Get history events
        $history = collect();
        
        // Add transactions to history
        $transactions->each(function ($transaction) use ($history) {
            $description = 'Transaction recorded';
            if ($transaction->TransactionType === 'OPENING_STOCK') {
                $description = 'Opening stock recorded';
            } else if ($transaction->TransactionType === 'STOCK_UPDATE') {
                $description = 'Stock adjustment recorded';
            }
            
            $history->push((object)[
                'EventType' => 'STOCK_ADJUSTMENT',
                'EventDate' => $transaction->TransactionDate,
                'Description' => $description,
                'QuantityChange' => $transaction->QuantityChange,
                'OldValue' => null,
                'NewValue' => null
            ]);
        });
        
        // Sort all events by date
        $history = $history->sortByDesc('EventDate')->values();
        
        // Paginate the history
        $history = new \Illuminate\Pagination\LengthAwarePaginator(
            $history->forPage($request->get('page', 1), 10),
            $history->count(),
            10,
            $request->get('page', 1),
            ['path' => $request->url()]
        );

        return view('transaction.product-transaction', compact('product', 'transactions', 'products', 'history'));
    }

    /**
     * Get transaction history for reporting
     */
    public function getTransactionHistory(Request $request)
    {
        $query = Transaction::with(['product', 'sale']);

        // Apply filters
        if ($request->has('type')) {
            $query->where('TransactionType', $request->type);
        }

        if ($request->has('start_date')) {
            $query->whereDate('TransactionDate', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->whereDate('TransactionDate', '<=', $request->end_date);
        }

        $transactions = $query->orderBy('TransactionDate', 'desc')
            ->paginate(15);

        return response()->json($transactions);
    }

    /**
     * Get transaction summary for dashboard
     */
    public function getTransactionSummary()
    {
        $summary = [
            'total_sales' => Transaction::where('TransactionType', 'SALE')
                ->whereDate('TransactionDate', today())
                ->sum('TotalAmount'),
            'total_quantity_sold' => Transaction::where('TransactionType', 'SALE')
                ->whereDate('TransactionDate', today())
                ->sum(DB::raw('ABS(QuantityChange)')),
            'top_selling_products' => Transaction::where('TransactionType', 'SALE')
                ->whereDate('TransactionDate', today())
                ->select('ProductID', DB::raw('SUM(ABS(QuantityChange)) as total_quantity'))
                ->groupBy('ProductID')
                ->orderBy('total_quantity', 'desc')
                ->limit(5)
                ->with('product')
                ->get()
        ];

        return response()->json($summary);
    }

    /**
     * Display the history of a specific product
     */
    public function productHistory($productId)
    {
        $product = Product::findOrFail($productId);
        $products = Product::with(['inventory'])->get();
        
        // Get all events (transactions, price changes, status changes)
        $history = collect();
        
        // Add transactions
        $transactions = Transaction::where('ProductID', $productId)
            ->orderBy('TransactionDate', 'desc')
            ->get()
            ->map(function ($transaction) {
                return (object)[
                    'EventType' => 'STOCK_ADJUSTMENT',
                    'EventDate' => $transaction->TransactionDate,
                    'Description' => 'Transaction recorded',
                    'QuantityChange' => $transaction->QuantityChange,
                    'OldValue' => null,
                    'NewValue' => null
                ];
            });
        $history = $history->concat($transactions);
        
        // Add price changes (you'll need to implement this based on your data structure)
        // Example:
        // $priceChanges = PriceHistory::where('ProductID', $productId)
        //     ->orderBy('ChangeDate', 'desc')
        //     ->get()
        //     ->map(function ($change) {
        //         return (object)[
        //             'EventType' => 'PRICE_CHANGE',
        //             'EventDate' => $change->ChangeDate,
        //             'Description' => 'Price updated',
        //             'QuantityChange' => null,
        //             'OldValue' => $change->OldPrice,
        //             'NewValue' => $change->NewPrice
        //         ];
        //     });
        // $history = $history->concat($priceChanges);
        
        // Sort all events by date
        $history = $history->sortByDesc('EventDate')->values();
        
        // Paginate the results
        $history = new \Illuminate\Pagination\LengthAwarePaginator(
            $history->forPage(\Request::get('page', 1), 10),
            $history->count(),
            10,
            \Request::get('page', 1),
            ['path' => \Request::url()]
        );

        return view('products.history', compact('product', 'products', 'history'));
    }
}
