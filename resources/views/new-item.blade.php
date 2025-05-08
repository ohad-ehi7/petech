<x-header>
    <div class="p-10">
        <div class="bg-white rounded-xl shadow-md p-6">
            <form class="space-y-8">
                <h1 class="text-2xl font-bold mb-6">Add new item</h1>

                <!-- Basic Information Section -->
                <div class="relative pb-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800">Basic Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="flex flex-col space-y-4">
                            <!-- Name -->
                            <div>
                                <label class="block text-sm font-medium mb-2">Product Name*</label>
                                <input type="text" name="ProductName" class="w-full border border-gray-300 rounded-lg px-3 py-2" />
                            </div>
                            <!-- SKU -->
                            <div>
                                <label class="block text-sm font-medium mb-2">SKU</label>
                                <input type="text" name="SKU" class="w-full border border-gray-300 rounded-lg px-3 py-2" />
                            </div>
                            <!-- Unit -->
                            <div>
                                <label class="block text-sm font-medium mb-2">Unit*</label>
                                <select name="Unit" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                    <option value="">Select or type to add</option>
                                    <option value="piece">Piece</option>
                                    <option value="box">Box</option>
                                    <option value="kg">Kilogram</option>
                                    <option value="g">Gram</option>
                                    <option value="L">Liter</option>
                                    <option value="mL">Milliliter</option>
                                </select>
                            </div>
                            <!-- Returnable Item -->
                            <div>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="IsReturnable" class="w-4 h-4">
                                    <span class="ml-2 text-sm">Returnable Item</span>
                                </label>
                            </div>
                        </div>

                        <!-- Image Upload -->
                        <div class="flex flex-col">
                            <label class="block text-sm font-medium mb-2">Product Image</label>
                            <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-10 h-10 mb-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5A5.5 5.5 0 0 0 5.207 5.021A4 4 0 0 0 5 13h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                    <p class="text-xs text-gray-500">PNG, JPG or JPEG (MAX. 5MB)</p>
                                </div>
                                <input id="dropzone-file" type="file" class="hidden" accept="image/*" />
                            </label>
                        </div>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent"></div>
                </div>

                <!-- Classification Section -->
                <div class="relative pb-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800">Classification</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Category*</label>
                            <select name="CategoryID" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                <option value="">Select Category</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Supplier</label>
                            <select name="SupplierID" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                <option value="">Select Supplier</option>
                            </select>
                        </div>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent"></div>
                </div>

                <!-- Product Details Section -->
                <div class="relative pb-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800">Product Details</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Brand</label>
                            <input type="text" name="Brand" class="w-full border border-gray-300 rounded-lg px-3 py-2" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Description</label>
                            <textarea name="Description" class="w-full border border-gray-300 rounded-lg px-3 py-2"></textarea>
                        </div>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent"></div>
                </div>

                <!-- Specifications Section -->
                <div class="relative pb-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800">Specifications</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Weight</label>
                            <div class="flex space-x-2">
                                <input type="number" name="Weight" step="0.01" class="w-32 border border-gray-300 rounded-lg px-3 py-2" />
                                <select name="WeightUnit" class="border border-gray-300 rounded-lg px-3 py-2">
                                    <option value="g">g</option>
                                    <option value="kg">kg</option>
                                    <option value="mL">mL</option>
                                    <option value="L">L</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent"></div>
                </div>

                <!-- Pricing Section -->
                <div class="relative pb-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800">Pricing</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Selling Price*</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">₱</span>
                                <input type="number" name="SellingPrice" step="0.01" class="w-full border border-gray-300 rounded-lg pl-8 pr-3 py-2" placeholder="0.00" />
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Cost Price*</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">₱</span>
                                <input type="number" name="CostPrice" step="0.01" class="w-full border border-gray-300 rounded-lg pl-8 pr-3 py-2" placeholder="0.00" />
                            </div>
                        </div>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent"></div>
                </div>

                <!-- Inventory Section -->
                <div class="relative pb-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800">Inventory</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Opening Stock</label>
                            <input type="number" name="OpeningStock" class="w-full border border-gray-300 rounded-lg px-3 py-2" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Reorder Level</label>
                            <input type="number" name="ReorderLevel" class="w-full border border-gray-300 rounded-lg px-3 py-2" />
                        </div>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent"></div>
                </div>

                <!-- Save and Cancel Buttons -->
                <div class="flex justify-end space-x-4 mt-6">
                    <button type="button" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors">Cancel</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">Save</button>
                </div>
            </form>
        </div>
    </div>
</x-header>
