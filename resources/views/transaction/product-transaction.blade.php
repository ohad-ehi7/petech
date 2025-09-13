<x-header>
<div class="flex p-6">
    
    <!-- Sidebar -->
        <aside class="w-64 bg-white shadow h-screen p-6">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-lg font-semibold">All Items</h2>
            <a href="{{ route('products.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">+ New</a>
        </div>

        <div class="mb-4">
            <input type="text" id="productSearch" placeholder="Search products..." class="w-full px-3 py-2 border rounded-lg text-sm">
        </div>

        <ul class="space-y-4 max-h-[calc(100vh-200px)] overflow-y-auto" id="productList">
            @foreach($products as $item)
            <li class="flex items-start justify-between hover:bg-gray-50 p-2 rounded-lg transition cursor-pointer product-item" 
                data-product-name="{{ strtolower($item->ProductName) }}"
                data-product-sku="{{ strtolower($item->SKU) }}"
                onclick="window.location.href='{{ route('products.transactions', $item->ProductID) }}'">
                <div class="flex-1">
                    <p class="font-medium text-sm">{{ $item->ProductName }}</p>
                    <span class="text-gray-500 text-xs">SKU: {{ $item->SKU }}</span>
                    <div class="mt-1">
                        <span class="text-xs {{ $item->inventory->QuantityOnHand <= ($item->OpeningStock/2 - 1) ? 'text-red-500' : 'text-green-500' }}">
                            Stock: {{ $item->inventory->QuantityOnHand ?? 0 }}
                        </span>
                    </div>
                </div>
                <span class="text-sm font-semibold">HTG {{ number_format($item->SellingPrice, 2) }}</span>
            </li>
            @endforeach
        </ul>

        </aside>


        <!-- Main Content -->
        <main class="flex-1 p-6 bg-white space-y-8">

        <!-- Header -->
        <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">{{ $product->ProductName }}</h1>
        <div class="flex items-center gap-2">
            <span class="bg-green-100 text-green-700 text-xs font-medium px-2 py-1 rounded">Active</span>
        </div>
        </div>

        <!-- Search Bar -->
        <div class="flex items-center gap-4">
            <div class="flex-1">
                <input type="text" id="transactionSearch" placeholder="Search transactions..." class="w-full px-3 py-2 border rounded-lg text-sm">
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-lg shadow border">
                <h3 class="text-sm font-medium text-gray-500">Total Sales</h3>
                <p class="text-2xl font-semibold mt-1">HTG {{ number_format($transactions->where('TransactionType', 'SALE')->sum('TotalAmount'), 2) }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow border">
                <h3 class="text-sm font-medium text-gray-500">Total Returns</h3>
                <p class="text-2xl font-semibold mt-1">HTG {{ number_format($transactions->where('TransactionType', 'RETURN')->sum('TotalAmount'), 2) }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow border">
                <h3 class="text-sm font-medium text-gray-500">Units Sold</h3>
                <p class="text-2xl font-semibold mt-1">{{ abs($transactions->where('TransactionType', 'SALE')->sum('QuantityChange')) }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow border">
                <h3 class="text-sm font-medium text-gray-500">Units Returned</h3>
                <p class="text-2xl font-semibold mt-1">{{ abs($transactions->where('TransactionType', 'RETURN')->sum('QuantityChange')) }}</p>
            </div>
        </div>

        <!-- Tabs -->
        <nav>
        <ul class="flex border-b space-x-6 text-sm">
            <li class="border-b-2 border-blue-600 text-blue-600 pb-2 font-semibold cursor-pointer" onclick="showTab('transactions')">Transactions</li>
            <li class="text-gray-500 hover:text-black cursor-pointer pb-2" onclick="showTab('history')">History</li>
        </ul>
        </nav>

        <!-- Transactions Tab -->
        <div id="transactionsTab" class="space-y-4">
            <!-- Filters -->
            <div class="flex flex-wrap items-center gap-4 bg-gray-50 p-4 rounded-lg">
                <div>
                    <label class="text-sm font-medium text-gray-700">Sort By:</label>
                    <select id="sortBy" class="ml-2 text-sm border rounded px-3 py-1 bg-white">
                        <option value="date_desc">Date (Newest)</option>
                        <option value="date_asc">Date (Oldest)</option>
                        <option value="amount_desc">Amount (High to Low)</option>
                        <option value="amount_asc">Amount (Low to High)</option>
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full border-t text-sm text-left text-gray-700">
                <thead class="bg-gray-50">
                    <tr>
                    <th class="px-4 py-2 font-medium text-gray-600 whitespace-nowrap">Date</th>
                    <th class="px-4 py-2 font-medium text-gray-600 whitespace-nowrap">Type</th>
                    <th class="px-4 py-2 font-medium text-gray-600 whitespace-nowrap">Quantity</th>
                    <th class="px-4 py-2 font-medium text-gray-600 whitespace-nowrap">Unit Price</th>
                    <th class="px-4 py-2 font-medium text-gray-600 whitespace-nowrap">Total</th>
                    <th class="px-4 py-2 font-medium text-gray-600 whitespace-nowrap">Reference</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($transactions as $transaction)
                    <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $transaction->TransactionDate->format('M d, Y H:i') }}</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 rounded text-xs font-medium
                            @if($transaction->TransactionType === 'SALE') bg-green-100 text-green-800
                            @elseif($transaction->TransactionType === 'RETURN') bg-red-100 text-red-800
                            @elseif($transaction->TransactionType === 'OPENING_STOCK') bg-blue-100 text-blue-800
                            @elseif($transaction->TransactionType === 'STOCK_UPDATE') bg-purple-100 text-purple-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ $transaction->TransactionType }}
                        </span>
                    </td>
                    <td class="px-4 py-2">
                        <span class="{{ $transaction->QuantityChange < 0 ? 'text-red-600' : 'text-green-600' }}">
                            {{ abs($transaction->QuantityChange) }}
                        </span>
                    </td>
                    <td class="px-4 py-2">HTG {{ number_format($transaction->UnitPrice, 2) }}</td>
                    <td class="px-4 py-2">HTG {{ number_format($transaction->TotalAmount, 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if($transaction->TransactionType === 'SALE')
                            @if($transaction->sale)
                                <a href="{{ route('sales.show', $transaction->sale->SaleID) }}" class="text-blue-600 hover:underline">
                                    SR-{{ str_pad($transaction->sale->SaleID, 5, '0', STR_PAD_LEFT) }}
                                </a>
                            @else
                                N/A
                            @endif
                        @elseif($transaction->TransactionType === 'OPENING_STOCK' || $transaction->TransactionType === 'STOCK_PURCHASE')
                            @if($transaction->purchase)
                                {{ $transaction->purchase->supplier->SupplierName ?? 'N/A' }}
                            @else
                                {{ $transaction->Notes ?? 'N/A' }}
                            @endif
                        @else
                            {{ $transaction->Notes ?? 'N/A' }}
                        @endif
                    </td>
                    </tr>
                    @endforeach
                </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $transactions->links() }}
            </div>
        </div>

        <!-- History Tab -->
        <div id="historyTab" class="space-y-4 hidden">
            <!-- History Timeline -->
            <div class="timeline">
                @foreach($history as $event)
                <div class="timeline-item">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <h3 class="timeline-title">
                            @switch($event->EventType)
                                @case('STOCK_ADJUSTMENT')
                                    @if($event->Description === 'Opening stock recorded')
                                        <i class="fas fa-box"></i> Opening Stock
                                    @elseif($event->Description === 'Stock adjustment recorded')
                                        <i class="fas fa-box"></i> Stock Update
                                    @else
                                        <i class="fas fa-box"></i> Stock Adjustment
                                    @endif
                                    @break
                                @case('PRICE_CHANGE')
                                    <i class="fas fa-tag"></i> Price Change
                                    @break
                                @default
                                    <i class="fas fa-info-circle"></i> {{ $event->EventType }}
                            @endswitch
                        </h3>
                        <p class="timeline-date">{{ $event->EventDate->format('M d, Y H:i') }}</p>
                        <div class="timeline-body">
                            <p>{{ $event->Description }}</p>
                            @if($event->QuantityChange)
                            <p class="mb-0">
                                <strong>Quantity Change:</strong> 
                                <span class="{{ $event->QuantityChange > 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $event->QuantityChange > 0 ? '+' : '' }}{{ $event->QuantityChange }}
                                </span>
                            </p>
                            @endif
                            @if($event->OldValue !== null && $event->NewValue !== null)
                            <p class="mb-0">
                                <strong>Price Change:</strong> 
                                {{ number_format($event->OldValue, 2) }} → {{ number_format($event->NewValue, 2) }}
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $history->links() }}
            </div>
        </div>

        </main>


</x-header>

<!-- Transaction Details Modal -->
<div id="transactionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Transaction Details</h3>
            <div class="mt-2 px-7 py-3">
                <div id="transactionDetails" class="text-sm text-gray-500">
                    <!-- Details will be loaded here -->
                </div>
            </div>
            <div class="items-center px-4 py-3">
                <button id="closeModal" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding: 20px 0;
}

.timeline-item {
    position: relative;
    padding-left: 50px;
    margin-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    left: 0;
    top: 0;
    width: 15px;
    height: 15px;
    border-radius: 50%;
    background: #007bff;
    border: 3px solid #fff;
    box-shadow: 0 0 0 3px #007bff;
}

.timeline-content {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.timeline-title {
    font-size: 1.1rem;
    margin-bottom: 5px;
    color: #333;
}

.timeline-date {
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 10px;
}

.timeline-body {
    color: #555;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: 7px;
    top: 15px;
    height: calc(100% + 15px);
    width: 2px;
    background: #e9ecef;
}

.timeline-item:last-child::before {
    display: none;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sortBy = document.getElementById('sortBy');
    const productSearch = document.getElementById('productSearch');
    const transactionSearch = document.getElementById('transactionSearch');
    const modal = document.getElementById('transactionModal');
    const closeModal = document.getElementById('closeModal');

    // Set initial sort value from URL
    const urlParams = new URLSearchParams(window.location.search);
    const currentSort = urlParams.get('sort') || 'date_desc';
    sortBy.value = currentSort;

    // Tab switching functionality
    window.showTab = function(tabName) {
        const transactionsTab = document.getElementById('transactionsTab');
        const historyTab = document.getElementById('historyTab');
        const tabs = document.querySelectorAll('nav ul li');
        
        if (tabName === 'transactions') {
            transactionsTab.classList.remove('hidden');
            historyTab.classList.add('hidden');
            tabs[0].classList.add('border-b-2', 'border-blue-600', 'text-blue-600', 'font-semibold');
            tabs[0].classList.remove('text-gray-500');
            tabs[1].classList.remove('border-b-2', 'border-blue-600', 'text-blue-600', 'font-semibold');
            tabs[1].classList.add('text-gray-500');
        } else {
            transactionsTab.classList.add('hidden');
            historyTab.classList.remove('hidden');
            tabs[1].classList.add('border-b-2', 'border-blue-600', 'text-blue-600', 'font-semibold');
            tabs[1].classList.remove('text-gray-500');
            tabs[0].classList.remove('border-b-2', 'border-blue-600', 'text-blue-600', 'font-semibold');
            tabs[0].classList.add('text-gray-500');
        }
    };

    // Sort functionality
    function updateTransactions() {
        const params = new URLSearchParams(window.location.search);
        params.set('sort', sortBy.value);
        window.location.href = `${window.location.pathname}?${params.toString()}`;
    }

    // Event listeners
    sortBy.addEventListener('change', updateTransactions);

    // Product search functionality
    productSearch.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const productItems = document.querySelectorAll('.product-item');
        let hasVisibleItems = false;
        
        productItems.forEach(item => {
            const productName = item.dataset.productName;
            const sku = item.dataset.productSku;
            
            if (productName.includes(searchTerm) || sku.includes(searchTerm)) {
                item.style.display = '';
                hasVisibleItems = true;
            } else {
                item.style.display = 'none';
            }
        });

        // Show/hide "No results" message
        const noResultsMsg = document.getElementById('noResultsMsg');
        
        if (!hasVisibleItems && searchTerm !== '') {
            if (!noResultsMsg) {
                const msg = document.createElement('div');
                msg.id = 'noResultsMsg';
                msg.className = 'text-center text-gray-500 py-4';
                msg.textContent = 'No products found';
                document.getElementById('productList').appendChild(msg);
            }
        } else if (noResultsMsg) {
            noResultsMsg.remove();
        }
    });

    // Transaction search functionality
    transactionSearch.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const transactionRows = document.querySelectorAll('#transactionsTab table tbody tr');
        
        transactionRows.forEach(row => {
            const type = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const quantity = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            const unitPrice = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
            const total = row.querySelector('td:nth-child(5)').textContent.toLowerCase();
            const reference = row.querySelector('td:nth-child(6)').textContent.toLowerCase();
            
            if (type.includes(searchTerm) || 
                quantity.includes(searchTerm) || 
                unitPrice.includes(searchTerm) || 
                total.includes(searchTerm) || 
                reference.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Modal functionality
    window.showTransactionDetails = function(transactionId) {
        fetch(`/api/transactions/${transactionId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('transactionDetails').innerHTML = `
                    <p><strong>Date:</strong> ${new Date(data.TransactionDate).toLocaleString()}</p>
                    <p><strong>Type:</strong> ${data.TransactionType}</p>
                    <p><strong>Quantity:</strong> ${data.QuantityChange}</p>
                    <p><strong>Unit Price:</strong> HTG ${data.UnitPrice.toFixed(2)}</p>
                    <p><strong>Total Amount:</strong> HTG ${data.TotalAmount.toFixed(2)}</p>
                    <p><strong>Reference:</strong> ${data.ReferenceID}</p>
                `;
                modal.classList.remove('hidden');
            });
    };

    closeModal.addEventListener('click', function() {
        modal.classList.add('hidden');
    });

    // Close modal when clicking outside
    window.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.add('hidden');
        }
    });
});
</script>
