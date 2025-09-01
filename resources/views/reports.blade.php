<x-header :title="$isCashier ? 'My Sales Report' : 'Reports'">

    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">
            {{ $isCashier ? 'Rapport de vos ventes personnelles' : 'Rapport global des ventes' }}
        </h1>

        @if($isCashier)
            <div class="mb-4 p-3 bg-blue-100 text-blue-800 rounded">
                Vous voyez uniquement vos ventes du jour (Clerk: {{ Auth::user()->name }}).
            </div>
        @endif

        <!-- Sales Activity Section -->
        <div class="mb-8">
            <h2 class="mb-4 text-lg font-semibold">Activité des ventes</h2>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                <div class="p-4 bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200">
                    <div class="text-sm text-gray-500">Ventes aujourd'hui</div>
                    <div class="text-2xl font-bold text-blue-600">HTG{{ number_format($todaySales, 2) }}</div>
                </div>
                <div class="p-4 bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200">
                    <div class="text-sm text-gray-500">Articles vendus aujourd'hui</div>
                    <div class="text-2xl font-bold text-green-600">{{ $itemsSoldToday }}</div>
                </div>
                <div class="p-4 bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200">
                    <div class="text-sm text-gray-500">Transactions aujourd'hui</div>
                    <div class="text-2xl font-bold text-purple-600">{{ $transactionsToday }}</div>
                </div>
                <div class="p-4 bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200">
                    <div class="text-sm text-gray-500">Transactions annulées</div>
                    <div class="text-2xl font-bold text-red-600">{{ $voidTransactionsToday }}</div>
                </div>
            </div>
        </div>

        <!-- Inventory Summary Section -->
        <div class="mb-8">
            <h2 class="mb-4 text-lg font-semibold">Résumé de l'inventaire</h2>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-5">
                <div class="p-4 bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200">
                    <div class="text-sm text-gray-500">Quantité en stock</div>
                    <div class="text-2xl font-bold text-blue-600">{{ $inventorySummary['quantity_in_hand'] }}</div>
                </div>
                <div class="p-4 bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200">
                    <div class="text-sm text-gray-500">Quantité à recevoir</div>
                    <div class="text-2xl font-bold text-yellow-600">{{ $inventorySummary['quantity_to_receive'] }}</div>
                </div>
                <div class="p-4 bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200">
                    <div class="text-sm text-gray-500">Articles en rupture</div>
                    <div class="text-2xl font-bold text-red-600">{{ $inventorySummary['low_stock_items'] }}</div>
                </div>
                <div class="p-4 bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200">
                    <div class="text-sm text-gray-500">Total des articles</div>
                    <div class="text-2xl font-bold text-green-600">{{ $inventorySummary['total_items'] }}</div>
                </div>
                <div class="p-4 bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200">
                    <div class="text-sm text-gray-500">Articles actifs</div>
                    <div class="text-2xl font-bold text-purple-600">{{ $inventorySummary['active_items'] }}</div>
                </div>
            </div>
        </div>

        <!-- Item Details Section -->
        <div class="mb-8">
            <h2 class="mb-4 text-lg font-semibold">Détails des articles</h2>
            <div class="p-4 bg-white rounded-lg shadow">
                <h3 class="mb-4 text-md font-medium">Articles les plus vendus ce mois</h3>
                <div class="overflow-x-auto">
                    <table id="topSellingItemsTable" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Produit</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Quantité vendue</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Total ventes</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Catégorie</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($topSellingItems as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $item->ProductName }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $item->total_quantity }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">HTG{{ number_format($item->total_sales ?? 0, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $item->CategoryName ?? 'N/A' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Monthly Sales Chart -->
        <div class="mb-8">
            <h2 class="mb-4 text-lg font-semibold">Ventes mensuelles</h2>
            <div class="p-4 bg-white rounded-lg shadow">
                <div class="mb-4">
                    <div class="text-sm text-gray-500">Total ventes ce mois</div>
                    <div class="text-2xl font-bold text-blue-600">HTG{{ number_format($monthlyTotal, 2) }}</div>
                </div>
                <div class="h-64">
                    <canvas id="monthlySalesChart"></canvas>
                </div>
            </div>
        </div>
    </div>

      <!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- DataTables CSS & JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/js/selectize.min.js"></script>
<script src="{{ asset('assets/js/chart.js') }}"></script>


    <script>
        $(document).ready(function() {
            // DataTable
            $('#topSellingItemsTable').DataTable({
                dom: '<"flex flex-wrap items-center justify-between"<"flex items-center"B><"flex items-center"lf>>rtip',
                buttons: ['copy','csv','excel','pdf','print'],
                order: [[1, 'desc']],
                pageLength: 25,
                language: {
                    search: "Rechercher :",
                    lengthMenu: "Afficher _MENU_ articles par page",
                    info: "Affichage de _START_ à _END_ sur _TOTAL_ articles"
                }
            });

            // Chart
            const ctx = document.getElementById('monthlySalesChart').getContext('2d');
            const monthlySales = @json($monthlySales);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: monthlySales.map(item => item.date),
                    datasets: [{
                        label: 'Ventes quotidiennes',
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
                        legend: { position: 'top' },
                        title: { display: true, text: 'Tendance quotidienne des ventes' }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'HTG' + value;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>

</x-header>
