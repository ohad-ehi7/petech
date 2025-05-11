<x-header :title="'Product Overview'">
    <div class="p-10">
        <div class="bg-white rounded-xl shadow-md p-6">
            <!-- Header with back button and actions -->
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-gray-900">
                        <i class="fa-solid fa-arrow-left"></i> Back to Products
                    </a>
                    <h1 class="text-2xl font-bold text-gray-800">{{ $product->ProductName }}</h1>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('products.edit', $product) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                        Edit Product
                    </a>
                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors" onclick="return confirm('Are you sure you want to delete this product?')">
                            Delete Product
                        </button>
                    </form>
                </div>
            </div>

            <!-- Main Content -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Left Column: Product Image and Basic Info -->
                <div class="md:col-span-1">
                    <div class="bg-gray-50 rounded-lg p-4">
                        @if($product->Product_Image)
                            <img src="{{ asset('storage/' . $product->Product_Image) }}" alt="{{ $product->ProductName }}" class="w-full h-64 object-cover rounded-lg mb-4">
                        @else
                            <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center mb-4">
                                <span class="text-gray-500">No image available</span>
                            </div>
                        @endif
                        
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">SKU</h3>
                                <p class="mt-1">{{ $product->SKU ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Category</h3>
                                <p class="mt-1">{{ $product->category->CategoryName }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Brand</h3>
                                <p class="mt-1">{{ $product->Brand ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Middle Column: Product Details -->
                <div class="md:col-span-1">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h2 class="text-lg font-semibold mb-4">Product Details</h2>
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Description</h3>
                                <p class="mt-1">{{ $product->Description ?? 'No description available' }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Unit</h3>
                                <p class="mt-1">{{ $product->Unit }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Weight</h3>
                                <p class="mt-1">
                                    @if($product->Weight)
                                        {{ $product->Weight }} {{ $product->WeightUnit }}
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Returnable</h3>
                                <p class="mt-1">{{ $product->IsReturnable ? 'Yes' : 'No' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Pricing and Inventory -->
                <div class="md:col-span-1">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h2 class="text-lg font-semibold mb-4">Pricing & Inventory</h2>
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Selling Price</h3>
                                <p class="mt-1 text-lg font-semibold text-green-600">${{ number_format($product->SellingPrice, 2) }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Cost Price</h3>
                                <p class="mt-1">${{ number_format($product->CostPrice, 2) }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Current Stock</h3>
                                <p class="mt-1">{{ $product->inventory->QuantityOnHand ?? 0 }} units</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Reorder Level</h3>
                                <p class="mt-1">{{ $product->ReorderLevel ?? 'Not set' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Supplier Information -->
            <div class="mt-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <h2 class="text-lg font-semibold mb-4">Supplier Information</h2>
                    @if($product->suppliers->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($product->suppliers as $supplier)
                                <div class="bg-white p-4 rounded-lg shadow">
                                    <h3 class="font-medium">{{ $supplier->SupplierName }}</h3>
                                    <div class="mt-2 space-y-1 text-sm text-gray-600">
                                        @if($supplier->ContactNumber)
                                            <p>Phone: {{ $supplier->ContactNumber }}</p>
                                        @endif
                                        @if($supplier->Email)
                                            <p>Email: {{ $supplier->Email }}</p>
                                        @endif
                                        @if($supplier->Address)
                                            <p>Address: {{ $supplier->Address }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No suppliers assigned to this product.</p>
                    @endif
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="mt-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <h2 class="text-lg font-semibold mb-4">Recent Transactions</h2>
                    @if($product->transactions->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-white">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit Price</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($product->transactions->take(5) as $transaction)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $transaction->TransactionDate->format('M d, Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $transaction->TransactionType }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $transaction->QuantityChange }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                ${{ number_format($transaction->UnitPrice, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                ${{ number_format($transaction->TotalAmount, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500">No recent transactions found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-header> 