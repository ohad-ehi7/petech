<x-header :title="'Point of Sale'">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="flex h-screen bg-gray-50">
        <!-- Left Section: Product Selection -->
        <div class="w-2/3 p-6 overflow-y-auto">
            <!-- Search and Filter Bar -->
            <div class="mb-6 flex items-center space-x-4">
                <div class="flex-1">
                    <div class="relative">
                        <input type="text" 
                               id="searchInput"
                               placeholder="Search products..." 
                               class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <i class="fa-solid fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>
                <select id="categoryFilter" class="px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->CategoryID }}">{{ $category->CategoryName }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Product Grid -->
            <div id="productGrid" class="grid grid-cols-3 gap-4">
                @foreach($products as $product)
                    <div class="product-card bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow p-4 cursor-pointer" 
                         data-product-id="{{ $product->ProductID }}"
                         data-product-name="{{ strtolower($product->ProductName) }}"
                         data-category-id="{{ $product->CategoryID }}"
                         onclick="showQuantityModal({{ $product->ProductID }}, '{{ $product->ProductName }}', {{ $product->SellingPrice }}, {{ $product->inventory->QuantityOnHand ?? 0 }})">
                        <div class="aspect-square mb-3 bg-gray-100 rounded-lg overflow-hidden">
                            @if($product->Product_Image)
                                <img src="{{ asset('storage/' . $product->Product_Image) }}" 
                                     alt="{{ $product->ProductName }}" 
                                     class="w-full h-full object-contain">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fa-solid fa-image text-gray-400 text-4xl"></i>
                                </div>
                            @endif
                        </div>
                        <h3 class="font-medium text-gray-900 truncate">{{ $product->ProductName }}</h3>
                        <p class="text-sm text-gray-500 mb-2">SKU: {{ $product->SKU }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold text-blue-600">₱{{ number_format($product->SellingPrice, 2) }}</span>
                            <span class="text-sm text-gray-500">Stock: {{ $product->inventory->QuantityOnHand ?? 0 }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Right Section: Cart and Checkout -->
        <div class="w-1/3 bg-white border-l border-gray-200 p-6">
            <!-- Cart content will be dynamically updated -->
        </div>
    </div>
</x-header>