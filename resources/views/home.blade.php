<x-header :title="'Home'">

    <!-- Profile section -->
    <div class="relative flex items-center p-4 border border-gray-200 shadow bg-cover bg-center overflow-hidden" style="background-color: rgba(255, 255, 255, 0.8);">
        <!-- Background layer -->
        <div class="absolute inset-0 bg-cover bg-center opacity-40" style="background-image: url('images/small-background.png'); z-index: 0;"></div>

        <!-- Foreground content -->
        <div class="relative z-10 flex items-center">
            <img src="images/profile-icon.png" alt="Picture nako" class="w-12 h-12 rounded-full border-2 border-gray-300 dark:border-gray-600 mr-4">
            <div class="flex flex-col">
                <h2 class="text-lg font-semibold text-black">Hello, {{Auth::user()->name}}</h2>
                <p class="text-black">Changchang Store IMS/SMS</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-5 gap-4 p-4">
        <div class="col-span-3">
            <div class="max-w-3xl mx-auto rounded-lg overflow-hidden shadow border border-gray-200">
                <!-- Header -->
                <div class="bg-blue-400 text-black p-4 text-sm font-semibold">
                    Sales Activity
                </div>

                <!-- Content -->
                <div class="bg-white grid grid-cols-4 text-center divide-x divide-gray-200 p-4">
                    <!-- Today Sales -->
                    <div>
                        <div class="text-2xl font-bold text-blue-500">₱{{ number_format($todaySales, 2) }}</div>
                        <div class="text-gray-500 text-sm">Peso</div>
                        <div class="mt-2 text-xs text-gray-600 flex items-center justify-center gap-1">
                            <span>⚙</span>
                            TODAY SALES
                        </div>
                    </div>

                    <!-- Items Sold -->
                    <div>
                        <div class="text-2xl font-bold text-green-500">{{ $itemsSoldToday }}</div>
                        <div class="text-gray-500 text-sm">Products</div>
                        <div class="mt-2 text-xs text-gray-600 flex items-center justify-center gap-1">
                            <span>⚙</span>
                            ITEMS SOLD
                        </div>
                    </div>

                    <!-- Transactions -->
                    <div>
                        <div class="text-2xl font-bold text-green-500">{{ $transactionsToday }}</div>
                        <div class="text-gray-500 text-sm">Qty</div>
                        <div class="mt-2 text-xs text-gray-600 flex items-center justify-center gap-1">
                            <span>⚙</span>
                            TRANSACTIONS
                        </div>
                    </div>

                    <!-- Void -->
                    <div>
                        <div class="text-2xl font-bold text-red-500">{{ $voidTransactionsToday }}</div>
                        <div class="text-gray-500 text-sm">Qty</div>
                        <div class="mt-2 text-xs text-gray-600 flex items-center justify-center gap-1">
                            <span>✖</span>
                            VOID
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-span-2">
            <div class="rounded-lg shadow-md overflow-hidden">
                <div class="bg-purple-400">
                    <h2 class="text-black text-sm font-semibold p-4">Inventory Summary</h2>
                </div>
                <div class="bg-white px-4 py-3 text-sm space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-500">QUANTITY IN HAND</span>
                        <span class="font-semibold text-gray-800">{{ $inventorySummary['quantity_in_hand'] }}</span>
                    </div>
                    <hr />
                    <div class="flex justify-between">
                        <span class="text-gray-500">QUANTITY TO BE RECEIVED</span>
                        <span class="font-semibold text-gray-800">{{ $inventorySummary['quantity_to_receive'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-span-2">
            <div class="rounded-lg shadow-md overflow-hidden">
                <div class="bg-gray-400">
                    <h2 class="text-black text-sm font-semibold p-4">Item Details</h2>
                </div>
                <div class="bg-white p-4 flex justify-between items-center">
                    <!-- Left Side -->
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-red-500 text-sm">Low Stock Items</span>
                            <span class="text-red-500 font-semibold">{{ $inventorySummary['low_stock_items'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 text-sm">All Item Groups</span>
                            <span class="text-black font-semibold">{{ $inventorySummary['total_items'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 text-sm">All Items</span>
                            <span class="text-black font-semibold">{{ $inventorySummary['active_items'] }}</span>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="border-l h-auto mx-4"></div>

                    <!-- Right Side (Active Items Circle) -->
                    <div class="flex flex-col items-center justify-center text-xs text-gray-400">
                        <span class="text-gray-600 mb-2 font-semibold">Active Items</span>
                        <div class="w-20 h-20 flex items-center justify-center rounded-full border-[6px] border-gray-200 text-center">
                            <span class="text-[10px] text-center leading-tight">{{ $inventorySummary['active_items'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-span-3">
            <div class="rounded-lg shadow-md overflow-hidden">
                <!-- Header -->
                <div class="bg-amber-400 flex justify-between items-center">
                    <h2 class="text-black text-sm font-semibold p-4">Top Selling Items</h2>
                    <form method="GET" action="{{ route('home') }}" class="mr-4">
                        <select name="date_range" class="text-sm text-gray-800 bg-transparent border-none focus:ring-0" onchange="this.form.submit()">
                            <option value="today" {{ $dateRange === 'today' ? 'selected' : '' }}>Today</option>
                            <option value="yesterday" {{ $dateRange === 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                            <option value="this_week" {{ $dateRange === 'this_week' ? 'selected' : '' }}>This Week</option>
                            <option value="last_week" {{ $dateRange === 'last_week' ? 'selected' : '' }}>Last Week</option>
                            <option value="this_month" {{ $dateRange === 'this_month' ? 'selected' : '' }}>This Month</option>
                            <option value="last_month" {{ $dateRange === 'last_month' ? 'selected' : '' }}>Last Month</option>
                            <option value="this_year" {{ $dateRange === 'this_year' ? 'selected' : '' }}>This Year</option>
                            <option value="last_year" {{ $dateRange === 'last_year' ? 'selected' : '' }}>Last Year</option>
                        </select>
                    </form>
                </div>

                <!-- Content -->
                <div class="bg-white p-4">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity Sold</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Sales</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($topSellingItems as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->ProductName }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ (int)$item->total_quantity }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₱{{ number_format($item->total_sales ?? 0, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->CategoryName ?? 'N/A' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No items were invoiced in this time frame</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-span-1">
            <!-- Purchase Order Card -->
            <div class="bg-white rounded-lg shadow-lg">
                <!-- Header -->
                <div class="flex justify-between items-center bg-green-400 text-black rounded-t-lg">
                    <h2 class="font-semibold p-4">Purchase Order</h2>
                    <div class="text-sm cursor-pointer">
                        This Month <span class="text-blue-800">▾</span>
                    </div>
                </div>

                <!-- Body -->
                <div class="flex flex-col items-center justify-center px-4 py-6 space-y-4">
                    <div class="text-sm text-gray-600">Quantity Ordered</div>
                    <div class="text-2xl text-blue-500 font-semibold">0</div>

                    <hr class="w-full border-t border-gray-200" />

                    <div class="text-sm text-gray-600">Total Cost</div>
                    <div class="text-xl text-blue-500 font-bold">PHP0.00</div>
                </div>
            </div>
        </div>

        <div class="col-span-4">
            <!-- Sales History Card -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="flex justify-between items-center bg-sky-300 rounded-t-lg">
                    <h2 class="text-black font-semibold p-4">Sales History</h2>
                    <form method="GET" action="{{ route('home') }}" class="mr-4">
                        <select name="date_range" class="text-sm text-black bg-transparent border-none focus:ring-0" onchange="this.form.submit()">
                            <option value="today" {{ $dateRange === 'today' ? 'selected' : '' }}>Today</option>
                            <option value="yesterday" {{ $dateRange === 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                            <option value="this_week" {{ $dateRange === 'this_week' ? 'selected' : '' }}>This Week</option>
                            <option value="last_week" {{ $dateRange === 'last_week' ? 'selected' : '' }}>Last Week</option>
                            <option value="this_month" {{ $dateRange === 'this_month' ? 'selected' : '' }}>This Month</option>
                            <option value="last_month" {{ $dateRange === 'last_month' ? 'selected' : '' }}>Last Month</option>
                            <option value="this_year" {{ $dateRange === 'this_year' ? 'selected' : '' }}>This Year</option>
                            <option value="last_year" {{ $dateRange === 'last_year' ? 'selected' : '' }}>Last Year</option>
                        </select>
                    </form>
                </div>

                <!-- Table Head -->
                <div class="bg-gray-100 text-sm text-gray-600 font-semibold px-4 py-2 grid grid-cols-6">
                    <div>Channel</div>
                    <div>Draft</div>
                    <div>Confirmed</div>
                    <div>Packed</div>
                    <div>Shipped</div>
                    <div>Invoiced</div>
                </div>

                <!-- Table Body -->
                <div class="bg-white">
                    <div class="grid grid-cols-6 px-4 py-3 text-sm">
                        <div class="font-medium text-gray-900">Direct Sales</div>
                        <div class="text-yellow-600 font-medium">{{ $salesHistory['draft'] }}</div>
                        <div class="text-blue-600 font-medium">{{ $salesHistory['confirmed'] }}</div>
                        <div class="text-purple-600 font-medium">{{ $salesHistory['packed'] }}</div>
                        <div class="text-green-600 font-medium">{{ $salesHistory['shipped'] }}</div>
                        <div class="text-indigo-600 font-medium">{{ $salesHistory['invoiced'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-span-5">
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="flex justify-between items-center bg-indigo-200 rounded-t-xl">
                    <h2 class="text-sm font-semibold text-gray-800 p-4">Sales Order Summary (in PHP)</h2>
                    <form method="GET" action="{{ route('home') }}" class="mr-4">
                        <select name="date_range" class="text-sm text-gray-600 bg-transparent border-none focus:ring-0" onchange="this.form.submit()">
                            <option value="today" {{ $dateRange === 'today' ? 'selected' : '' }}>Today</option>
                            <option value="yesterday" {{ $dateRange === 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                            <option value="this_week" {{ $dateRange === 'this_week' ? 'selected' : '' }}>This Week</option>
                            <option value="last_week" {{ $dateRange === 'last_week' ? 'selected' : '' }}>Last Week</option>
                            <option value="this_month" {{ $dateRange === 'this_month' ? 'selected' : '' }}>This Month</option>
                            <option value="last_month" {{ $dateRange === 'last_month' ? 'selected' : '' }}>Last Month</option>
                            <option value="this_year" {{ $dateRange === 'this_year' ? 'selected' : '' }}>This Year</option>
                            <option value="last_year" {{ $dateRange === 'last_year' ? 'selected' : '' }}>Last Year</option>
                        </select>
                    </form>
                </div>
                <div class="p-4 flex flex-col md:flex-row">
                    <!-- Chart area -->
                    <div class="flex-1">
                        <div class="h-64">
                            <canvas id="monthlySalesChart"></canvas>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500 mt-2 px-2">
                            @foreach($monthlySales as $sale)
                                <span>{{ \Carbon\Carbon::parse($sale->date)->format('d M') }}</span>
                            @endforeach
                        </div>
                    </div>
                    <!-- Total Sales -->
                    <div class="md:w-48 mt-6 md:mt-0 md:ml-6">
                        <h3 class="text-sm font-medium text-gray-600 mb-2">Total Sales</h3>
                        <div class="flex items-center bg-blue-50 border border-blue-200 rounded-md px-3 py-2">
                            <div class="h-3 w-3 bg-cyan-400 rounded-full mr-2"></div>
                            <div>
                                <p class="text-xs text-gray-500">DIRECT SALES</p>
                                <p class="text-lg font-semibold text-gray-700">₱{{ number_format($monthlyTotal, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('monthlySalesChart').getContext('2d');
            const monthlySales = @json($monthlySales);
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: monthlySales.map(item => item.date),
                    datasets: [{
                        label: 'Daily Sales',
                        data: monthlySales.map(item => item.total),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Daily Sales Trend'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '₱' + value;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-header>