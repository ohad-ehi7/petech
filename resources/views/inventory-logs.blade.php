<x-header :title="'Inventory Logs'">
    <div class="p-6">
        <!-- Summary Cards -->
        <div class="mb-8">
            <h2 class="mb-4 text-lg font-semibold">Inventory Activity Summary</h2>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                <div class="p-4 bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200">
                    <div class="text-sm text-gray-500">Total Adjustments</div>
                    <div class="text-2xl font-bold text-blue-600">{{ $summary['total_adjustments'] }}</div>
                </div>
                <div class="p-4 bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200">
                    <div class="text-sm text-gray-500">Total Stock In</div>
                    <div class="text-2xl font-bold text-green-600">{{ $summary['stock_in'] }}</div>
                </div>
                <div class="p-4 bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200">
                    <div class="text-sm text-gray-500">Total Stock Out</div>
                    <div class="text-2xl font-bold text-red-600">{{ $summary['stock_out'] }}</div>
                </div>
                <div class="p-4 bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200">
                    <div class="text-sm text-gray-500">Manual Adjustments</div>
                    <div class="text-2xl font-bold text-purple-600">{{ $summary['adjustments'] }}</div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="mb-8">
            <h2 class="mb-4 text-lg font-semibold">Recent Activity</h2>
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Product</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Type</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Quantity</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentActivity as $activity)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $activity->ProductName }}</div>
                                    <div class="text-sm text-gray-500">{{ $activity->SKU }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $activity->type === 'stock_in' ? 'bg-green-100 text-green-800' : 
                                           ($activity->type === 'stock_out' ? 'bg-red-100 text-red-800' : 
                                           'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($activity->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $activity->quantity }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($activity->created_at)->format('M d, Y H:i') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $activity->notes ?? 'No notes' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Top Adjusted Products -->
        <div class="mb-8">
            <h2 class="mb-4 text-lg font-semibold">Top Adjusted Products</h2>
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Product</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Adjustments</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Total Stock In</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Total Stock Out</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($topAdjustedProducts as $product)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $product->ProductName }}</div>
                                    <div class="text-sm text-gray-500">{{ $product->SKU }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $product->adjustment_count }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">
                                    +{{ $product->total_stock_in }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">
                                    -{{ $product->total_stock_out }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Full Inventory Logs -->
        <div class="mb-8">
            <h2 class="mb-4 text-lg font-semibold">Inventory Logs</h2>
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table id="inventoryLogsTable" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Product</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Category</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Type</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Quantity</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($inventoryLogs as $log)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $log->ProductName }}</div>
                                    <div class="text-sm text-gray-500">{{ $log->SKU }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $log->CategoryName }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $log->type === 'stock_in' ? 'bg-green-100 text-green-800' : 
                                           ($log->type === 'stock_out' ? 'bg-red-100 text-red-800' : 
                                           'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($log->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $log->quantity }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($log->created_at)->format('M d, Y H:i') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $log->notes ?? 'No notes' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
            $('#inventoryLogsTable').DataTable({
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
                order: [[4, 'desc']], // Sort by date by default
                pageLength: 25,
                language: {
                    search: "Search logs:",
                    lengthMenu: "Show _MENU_ logs per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ logs"
                }
            });
        });
    </script>
</x-header> 