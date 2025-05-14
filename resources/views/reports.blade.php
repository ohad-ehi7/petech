<x-header :title="'Reports'">

    <div class="p-6">
        <!-- Sales Activity Section -->
        <div class="mb-8">
            <h2 class="mb-4 text-lg font-semibold">Sales Activity</h2>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                <div class="p-4 bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200">
                    <div class="text-sm text-gray-500">Today's Sales</div>
                    <div class="text-2xl font-bold text-blue-600">₱{{ number_format($todaySales, 2) }}</div>
                </div>
                <div class="p-4 bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200">
                    <div class="text-sm text-gray-500">Items Sold Today</div>
                    <div class="text-2xl font-bold text-green-600">{{ $itemsSoldToday }}</div>
                </div>
                <div class="p-4 bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200">
                    <div class="text-sm text-gray-500">Transactions Today</div>
                    <div class="text-2xl font-bold text-purple-600">{{ $transactionsToday }}</div>
                </div>
                <div class="p-4 bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200">
                    <div class="text-sm text-gray-500">Void Transactions</div>
                    <div class="text-2xl font-bold text-red-600">{{ $voidTransactionsToday }}</div>
                </div>
            </div>
        </div>

        <!-- Inventory Summary Section -->
        <div class="mb-8">
            <h2 class="mb-4 text-lg font-semibold">Inventory Summary</h2>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-5">
                <div class="p-4 bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200">
                    <div class="text-sm text-gray-500">Quantity in Hand</div>
                    <div class="text-2xl font-bold text-blue-600">{{ $inventorySummary['quantity_in_hand'] }}</div>
                </div>
                <div class="p-4 bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200">
                    <div class="text-sm text-gray-500">Quantity to Receive</div>
                    <div class="text-2xl font-bold text-yellow-600">{{ $inventorySummary['quantity_to_receive'] }}</div>
                </div>
                <div class="p-4 bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200">
                    <div class="text-sm text-gray-500">Low Stock Items</div>
                    <div class="text-2xl font-bold text-red-600">{{ $inventorySummary['low_stock_items'] }}</div>
                </div>
                <div class="p-4 bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200">
                    <div class="text-sm text-gray-500">Total Items</div>
                    <div class="text-2xl font-bold text-green-600">{{ $inventorySummary['total_items'] }}</div>
                </div>
                <div class="p-4 bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200">
                    <div class="text-sm text-gray-500">Active Items</div>
                    <div class="text-2xl font-bold text-purple-600">{{ $inventorySummary['active_items'] }}</div>
                </div>
            </div>
        </div>

        <!-- Item Details Section -->
        <div class="mb-8">
            <h2 class="mb-4 text-lg font-semibold">Item Details</h2>
            <div class="p-4 bg-white rounded-lg shadow">
                <h3 class="mb-4 text-md font-medium">Top Selling Items This Month</h3>
                <div class="overflow-x-auto">
                    <table id="topSellingItemsTable" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Product Name</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Quantity Sold</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Total Sales</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Category</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($topSellingItems as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $item->ProductName }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $item->total_quantity }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">₱{{ number_format($item->total_sales ?? 0, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $item->CategoryName ?? 'N/A' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

{{--        <!-- Sales History Section -->--}}
{{--        <div class="mb-8">--}}
{{--            <h2 class="mb-4 text-lg font-semibold">Sales History</h2>--}}
{{--            <div class="grid grid-cols-1 gap-4 md:grid-cols-5">--}}
{{--                <div class="p-4 bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200">--}}
{{--                    <div class="text-sm text-gray-500">Draft</div>--}}
{{--                    <div class="text-2xl font-bold text-yellow-600">{{ $salesHistory['draft'] }}</div>--}}
{{--                </div>--}}
{{--                <div class="p-4 bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200">--}}
{{--                    <div class="text-sm text-gray-500">Confirmed</div>--}}
{{--                    <div class="text-2xl font-bold text-blue-600">{{ $salesHistory['confirmed'] }}</div>--}}
{{--                </div>--}}
{{--                <div class="p-4 bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200">--}}
{{--                    <div class="text-sm text-gray-500">Packed</div>--}}
{{--                    <div class="text-2xl font-bold text-purple-600">{{ $salesHistory['packed'] }}</div>--}}
{{--                </div>--}}
{{--                <div class="p-4 bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200">--}}
{{--                    <div class="text-sm text-gray-500">Shipped</div>--}}
{{--                    <div class="text-2xl font-bold text-green-600">{{ $salesHistory['shipped'] }}</div>--}}
{{--                </div>--}}
{{--                <div class="p-4 bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200">--}}
{{--                    <div class="text-sm text-gray-500">Invoiced</div>--}}
{{--                    <div class="text-2xl font-bold text-indigo-600">{{ $salesHistory['invoiced'] }}</div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

        <!-- Monthly Sales Chart -->
        <div class="mb-8">
            <h2 class="mb-4 text-lg font-semibold">Monthly Sales</h2>
            <div class="p-4 bg-white rounded-lg shadow">
                <div class="mb-4">
                    <div class="text-sm text-gray-500">Total Sales This Month</div>
                    <div class="text-2xl font-bold text-blue-600">₱{{ number_format($monthlyTotal, 2) }}</div>
                </div>
                <div class="h-64">
                    <canvas id="monthlySalesChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .dt-buttons {
            margin-bottom: 1rem;
        }
        .dt-button {
            background-color: #4F46E5 !important;
            color: white !important;
            border: none !important;
            padding: 0.5rem 1rem !important;
            border-radius: 0.375rem !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
            margin-right: 0.5rem !important;
            transition: all 0.2s !important;
        }
        .dt-button:hover {
            background-color: #4338CA !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
        }
        .dt-button:active {
            transform: translateY(0) !important;
        }
        .dataTables_filter input {
            border: 1px solid #E5E7EB !important;
            border-radius: 0.375rem !important;
            padding: 0.5rem !important;
            margin-left: 0.5rem !important;
        }
        .dataTables_length select {
            border: 1px solid #E5E7EB !important;
            border-radius: 0.375rem !important;
            padding: 0.5rem !important;
            margin: 0 0.5rem !important;
        }
        .dataTables_info {
            color: #6B7280 !important;
            font-size: 0.875rem !important;
        }
        .paginate_button {
            border: 1px solid #E5E7EB !important;
            border-radius: 0.375rem !important;
            padding: 0.5rem 1rem !important;
            margin: 0 0.25rem !important;
            color: #4B5563 !important;
        }
        .paginate_button.current {
            background-color: #4F46E5 !important;
            color: white !important;
            border-color: #4F46E5 !important;
        }
        .paginate_button:hover:not(.current) {
            background-color: #F3F4F6 !important;
            color: #1F2937 !important;
        }
    </style>
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#topSellingItemsTable').DataTable({
                dom: '<"flex flex-wrap items-center justify-between"<"flex items-center"B><"flex items-center"lf>>rtip',
                buttons: [
                    {
                        extend: 'copy',
                        text: '<i class="fas fa-copy"></i> Copy',
                        className: 'dt-button'
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fas fa-file-csv"></i> CSV',
                        className: 'dt-button'
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'dt-button'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        className: 'dt-button'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Print',
                        className: 'dt-button'
                    }
                ],
                order: [[1, 'desc']], // Sort by quantity sold by default
                pageLength: 10,
                language: {
                    search: "Search items:",
                    lengthMenu: "Show _MENU_ items per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ items"
                }
            });

            // Initialize Chart
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
