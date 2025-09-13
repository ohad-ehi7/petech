<x-header>
    <div class="p-10">
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Edit Product</h1>
                <a href="{{ route('products.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors">
                    Back to List
                </a>
            </div>

            <form method="POST" action="{{ route('products.update', $product) }}" class="space-y-8" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="_method" value="PUT">

                <!-- Basic Information Section -->
                <div class="relative pb-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800">Basic Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="flex flex-col space-y-4">
                            <!-- Name -->
                            <div>
                                <label class="block text-sm font-medium mb-2">Product Name*</label>
                                <input type="text" name="ProductName" value="{{ old('ProductName', $product->ProductName) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('ProductName') border-red-500 @enderror" required />
                                @error('ProductName')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- SKU -->
                            <div>
                                <label class="block text-sm font-medium mb-2">SKU</label>
                                <input type="text" value="{{ $product->SKU }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 cursor-not-allowed" readonly />
                                <input type="hidden" name="SKU" value="{{ $product->SKU }}">
                                <p class="text-sm text-gray-500 mt-1">SKU cannot be modified</p>
                            </div>
                            <!-- Unit -->
                            <div>
                                <label class="block text-sm font-medium mb-2">Unit*</label>
                                <select name="Unit" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('Unit') border-red-500 @enderror" required>
                                    <option value="">Select or type to add</option>
                                    <option value="Piece" {{ old('Unit', $product->Unit) == 'Piece' ? 'selected' : '' }}>Piece</option>
                                    <option value="Pack" {{ old('Unit', $product->Unit) == 'Pack' ? 'selected' : '' }}>Pack</option>
                                    <option value="Bottle" {{ old('Unit', $product->Unit) == 'Bottle' ? 'selected' : '' }}>Bottle</option>
                                    <option value="Can" {{ old('Unit', $product->Unit) == 'Can' ? 'selected' : '' }}>Can</option>
                                    <option value="Box" {{ old('Unit', $product->Unit) == 'Box' ? 'selected' : '' }}>Box</option>
                                    <option value="Sachet" {{ old('Unit', $product->Unit) == 'Sachet' ? 'selected' : '' }}>Sachet</option>
                                    <option value="Bar" {{ old('Unit', $product->Unit) == 'Bar' ? 'selected' : '' }}>Bar</option>
                                    <option value="Jar" {{ old('Unit', $product->Unit) == 'Jar' ? 'selected' : '' }}>Jar</option>
                                    <option value="Tube" {{ old('Unit', $product->Unit) == 'Tube' ? 'selected' : '' }}>Tube</option>
                                    <option value="Tablet" {{ old('Unit', $product->Unit) == 'Tablet' ? 'selected' : '' }}>Tablet</option>
                                    <option value="Set" {{ old('Unit', $product->Unit) == 'Set' ? 'selected' : '' }}>Set</option>
                                    <option value="Bundle" {{ old('Unit', $product->Unit) == 'Bundle' ? 'selected' : '' }}>Bundle</option>
                                    <option value="L" {{ old('Unit', $product->Unit) == 'L' ? 'selected' : '' }}>L</option>
                                    <option value="Kg" {{ old('Unit', $product->Unit) == 'Kg' ? 'selected' : '' }}>Kg</option>
                                </select>
                                @error('Unit')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Returnable Item -->
                            <div>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="IsReturnable" value="1" {{ old('IsReturnable', $product->IsReturnable) ? 'checked' : '' }} class="w-4 h-4">
                                    <span class="ml-2 text-sm">Returnable Item</span>
                                </label>
                            </div>
                        </div>

                        <!-- Image Upload -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Product Image</label>
                            <div class="mt-1 flex items-center space-x-4">
                                @if($product->Product_Image)
                                    <img src="{{ asset($product->Product_Image) }}" alt="Current product image" class="h-32 w-32 object-cover rounded-lg">
                                @else
                                    <div class="text-sm text-gray-500">No image uploaded</div>
                                @endif
                                <div class="flex-1">
                                    <input type="file" name="Product_Image" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                                    <p class="mt-1 text-sm text-gray-500">PNG, JPG, JPEG up to 5MB</p>
                                </div>
                            </div>
                            @error('Product_Image')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Classification Section -->
                <div class="relative pb-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800">Classification</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Category*</label>
                            <select name="CategoryID" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('CategoryID') border-red-500 @enderror" required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->CategoryID }}" {{ old('CategoryID', $product->CategoryID) == $category->CategoryID ? 'selected' : '' }}>
                                        {{ $category->CategoryName }}
                                    </option>
                                @endforeach
                            </select>
                            @error('CategoryID')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Supplier</label>
                            <select name="SupplierID" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('SupplierID') border-red-500 @enderror">
                                <option value="">Select Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->SupplierID }}" {{ old('SupplierID', $product->SupplierID) == $supplier->SupplierID ? 'selected' : '' }}>
                                        {{ $supplier->SupplierName }}
                                    </option>
                                @endforeach
                            </select>
                            @error('SupplierID')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Product Details Section -->
                <div class="relative pb-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800">Product Details</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Brand</label>
                            <input type="text" name="Brand" value="{{ old('Brand', $product->Brand) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('Brand') border-red-500 @enderror" />
                            @error('Brand')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Description</label>
                            <textarea name="Description" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('Description') border-red-500 @enderror">{{ old('Description', $product->Description) }}</textarea>
                            @error('Description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Specifications Section -->
                <div class="relative pb-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800">Specifications</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Weight</label>
                            <div class="flex space-x-2">
                                <input type="number" name="Weight" step="0.01" value="{{ old('Weight', $product->Weight) }}" class="w-32 border border-gray-300 rounded-lg px-3 py-2 @error('Weight') border-red-500 @enderror" />
                                <select name="WeightUnit" class="border border-gray-300 rounded-lg px-3 py-2 @error('WeightUnit') border-red-500 @enderror">
                                    <option value="g" {{ old('WeightUnit', $product->WeightUnit) == 'g' ? 'selected' : '' }}>g</option>
                                    <option value="kg" {{ old('WeightUnit', $product->WeightUnit) == 'kg' ? 'selected' : '' }}>kg</option>
                                    <option value="mL" {{ old('WeightUnit', $product->WeightUnit) == 'mL' ? 'selected' : '' }}>mL</option>
                                    <option value="L" {{ old('WeightUnit', $product->WeightUnit) == 'L' ? 'selected' : '' }}>L</option>
                                </select>
                            </div>
                            @error('Weight')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            @error('WeightUnit')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Pricing Section -->
                <div class="relative pb-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800">Pricing</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Selling Price*</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">G </span>
                                <input type="number" name="SellingPrice" step="0.01" value="{{ old('SellingPrice', $product->SellingPrice) }}" class="w-full border border-gray-300 rounded-lg pl-8 pr-3 py-2 @error('SellingPrice') border-red-500 @enderror" placeholder="0.00" required />
                            </div>
                            @error('SellingPrice')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Cost Price*</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">G </span>
                                <input type="number" name="CostPrice" step="0.01" value="{{ old('CostPrice', $product->CostPrice) }}" class="w-full border border-gray-300 rounded-lg pl-8 pr-3 py-2 @error('CostPrice') border-red-500 @enderror" placeholder="0.00" required />
                            </div>
                            @error('CostPrice')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Inventory Section -->
                <div class="relative pb-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800">Inventory</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Stock Adjustment</label>
                            <div class="flex flex-col space-y-2">
                                <input type="number" name="stock_adjustment" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('stock_adjustment') border-red-500 @enderror" placeholder="Enter positive number to add stock, negative to remove" />
                                <p class="text-sm text-gray-500">Current stock: {{ $product->inventory->QuantityOnHand ?? 0 }} units</p>
                                <p class="text-sm text-gray-500">Opening stock: {{ $product->OpeningStock ?? 0 }} units</p>
                            </div>
                            @error('stock_adjustment')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Reorder Level</label>
                            <input type="number" name="ReorderLevel" value="{{ old('ReorderLevel', $product->inventory->ReorderLevel) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('ReorderLevel') border-red-500 @enderror" />
                            @error('ReorderLevel')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Save and Cancel Buttons -->
                <div class="flex justify-end space-x-4 mt-6">
                    <a href="{{ route('products.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors">Cancel</a>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</x-header>
