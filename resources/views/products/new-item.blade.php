

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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/css/selectize.default.min.css">

<x-header :title="'Add New Product'">
    <div class="p-10">
        <div class="bg-white rounded-xl shadow-md p-6">
            <form method="POST" action="{{route('products.store') }}" class="space-y-8" enctype="multipart/form-data">
                @csrf
                <h1 class="text-2xl font-bold mb-6">Add new item</h1>

                <!-- Basic Information Section -->
                <div class="relative pb-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800">Basic Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="flex flex-col space-y-4">
                            <!-- Name -->
                            <div>
                                <label class="block text-sm font-medium mb-2">Product Name*</label>
                                <input type="text" name="ProductName" value="{{ old('ProductName') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('ProductName') border-red-500 @enderror" required />
                                @error('ProductName')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- SKU -->
                            <div>
                                <label class="block text-sm font-medium mb-2">SKU</label>
                                <input type="text" name="SKU" value="{{ 'PROD-' . str_pad(($lastProductId ?? 0) + 1, 3, '0', STR_PAD_LEFT) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 cursor-not-allowed" readonly />
                                <p class="text-sm text-gray-500 mt-1">SKU is automatically generated</p>
                            </div>
                            <!-- Unit -->
                            <div>
                                <label class="block text-sm font-medium mb-2">Unit*</label>
                                <select name="Unit" id="unitSelect" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('Unit') border-red-500 @enderror" required>
                                    <option value="">Select or type to add</option>
                                    <option value="Piece" {{ old('Unit') == 'Piece' ? 'selected' : '' }}>Piece</option>
                                    <option value="Pack" {{ old('Unit') == 'Pack' ? 'selected' : '' }}>Pack</option>
                                    <option value="Bottle" {{ old('Unit') == 'Bottle' ? 'selected' : '' }}>Bottle</option>
                                    <option value="Can" {{ old('Unit') == 'Can' ? 'selected' : '' }}>Can</option>
                                    <option value="Box" {{ old('Unit') == 'Box' ? 'selected' : '' }}>Box</option>
                                    <option value="Sachet" {{ old('Unit') == 'Sachet' ? 'selected' : '' }}>Sachet</option>
                                    <option value="Bar" {{ old('Unit') == 'Bar' ? 'selected' : '' }}>Bar</option>
                                    <option value="Jar" {{ old('Unit') == 'Jar' ? 'selected' : '' }}>Jar</option>
                                    <option value="Tube" {{ old('Unit') == 'Tube' ? 'selected' : '' }}>Tube</option>
                                    <option value="Tablet" {{ old('Unit') == 'Tablet' ? 'selected' : '' }}>Tablet</option>
                                    <option value="Set" {{ old('Unit') == 'Set' ? 'selected' : '' }}>Set</option>
                                    <option value="Bundle" {{ old('Unit') == 'Bundle' ? 'selected' : '' }}>Bundle</option>
                                    <option value="L" {{ old('Unit') == 'L' ? 'selected' : '' }}>L</option>
                                    <option value="Kg" {{ old('Unit') == 'Kg' ? 'selected' : '' }}>Kg</option>
                                </select>
                                @error('Unit')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Returnable Item -->
                            <div>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="IsReturnable" value="1" {{ old('IsReturnable') ? 'checked' : '' }} class="w-4 h-4">
                                    <span class="ml-2 text-sm">Returnable Item</span>
                                </label>
                            </div>
                        </div>

                        <!-- Image Upload -->
                        <div class="flex flex-col">
                            <label class="block text-sm font-medium mb-2">Product Image</label>
                            <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors @error('Product_Image') border-red-500 @enderror">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6" id="upload-placeholder">
                                    <svg class="w-10 h-10 mb-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5A5.5 5.5 0 0 0 5.207 5.021A4 4 0 0 0 5 13h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                    <p class="text-xs text-gray-500">PNG, JPG or JPEG (MAX. 5MB)</p>
                                </div>
                                <img id="image-preview" class="hidden w-full h-full object-contain rounded-lg" alt="Image preview">
                                <input id="dropzone-file" type="file" name="Product_Image" class="hidden" accept="image/*" onchange="previewImage(this)" />
                            </label>
                            @error('Product_Image')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
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
                            <select name="CategoryID" id="categorySelect" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('CategoryID') border-red-500 @enderror" required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->CategoryID }}" {{ old('CategoryID') == $category->CategoryID ? 'selected' : '' }}>
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
                            <select name="SupplierID" id="supplierSelect" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('SupplierID') border-red-500 @enderror">
                                <option value="">Select Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->SupplierID }}" {{ old('SupplierID') == $supplier->SupplierID ? 'selected' : '' }}>
                                        {{ $supplier->SupplierName }}
                                    </option>
                                @endforeach
                            </select>
                            @error('SupplierID')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
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
                            <input type="text" name="Brand" value="{{ old('Brand') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('Brand') border-red-500 @enderror" />
                            @error('Brand')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Description</label>
                            <textarea name="Description" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('Description') border-red-500 @enderror">{{ old('Description') }}</textarea>
                            @error('Description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
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
                                <input type="number" name="Weight" step="0.01" value="{{ old('Weight') }}" class="w-32 border border-gray-300 rounded-lg px-3 py-2 @error('Weight') border-red-500 @enderror" />
                                <select name="WeightUnit" id="weightUnitSelectt" class="border border-gray-300 rounded-lg px-3 py-2 @error('WeightUnit') border-red-500 @enderror">
                                    <option value="g" {{ old('WeightUnit') == 'g' ? 'selected' : '' }}>g</option>
                                    <option value="kg" {{ old('WeightUnit') == 'kg' ? 'selected' : '' }}>kg</option>
                                    <option value="mL" {{ old('WeightUnit') == 'mL' ? 'selected' : '' }}>mL</option>
                                    <option value="L" {{ old('WeightUnit') == 'L' ? 'selected' : '' }}>L</option>
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
                                <input type="number" name="SellingPrice" step="0.01" value="{{ old('SellingPrice') }}" class="w-full border border-gray-300 rounded-lg pl-8 pr-3 py-2 @error('SellingPrice') border-red-500 @enderror" placeholder="0.00" required />
                            </div>
                            @error('SellingPrice')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Cost Price*</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">₱</span>
                                <input type="number" name="CostPrice" step="0.01" value="{{ old('CostPrice') }}" class="w-full border border-gray-300 rounded-lg pl-8 pr-3 py-2 @error('CostPrice') border-red-500 @enderror" placeholder="0.00" required />
                            </div>
                            @error('CostPrice')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
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
                            <input type="number" name="OpeningStock" value="{{ old('OpeningStock') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('OpeningStock') border-red-500 @enderror" />
                            @error('OpeningStock')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Reorder Level</label>
                            <input type="number" name="ReorderLevel" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('ReorderLevel') border-red-500 @enderror" />
                            @error('ReorderLevel')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent"></div>
                </div>

                <!-- Save and Cancel Buttons -->
                <div class="flex justify-end space-x-4 mt-6">
                    <a href="{{ route('products.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors">Cancel</a>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function previewImage(input) {
        const preview = document.getElementById('image-preview');
        const placeholder = document.getElementById('upload-placeholder');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }
            
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.classList.add('hidden');
            placeholder.classList.remove('hidden');
        }
    }

    // Initialiser Selectize sur les champs de sélection
    document.addEventListener('DOMContentLoaded', function() {
        // Configuration pour permettre l'ajout de nouvelles unités
        $('#unitSelect').selectize({
            create: true,
            sortField: 'text',
            placeholder: 'Select or type to add',
            createOnBlur: true,
            createFilter: function(input) {
                return input.length <= 50; // Limiter la longueur des nouvelles entrées
            }
        });

        // Configuration pour les catégories (sans création)
        $('#categorySelect').selectize({
            sortField: 'text',
            placeholder: 'Select Category'
        });

        // Configuration pour les fournisseurs (sans création)
        $('#supplierSelect').selectize({
            sortField: 'text',
            placeholder: 'Select Supplier'
        });

        // Configuration pour les unités de poids
        $('#weightUnitSelect').selectize({
            sortField: 'text',
            placeholder: 'Select Unit'
        });

        // Gestion de la soumission du formulaire
        const form = document.querySelector('form');
        const submitButton = form.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;

        form.addEventListener('submit', function(e) {
            // Afficher l'état de chargement
            submitButton.disabled = true;
            submitButton.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Saving...
            `;

            // Vérifier les erreurs de validation
            const requiredFields = form.querySelectorAll('[required]');
            let hasErrors = false;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('border-red-500');
                    hasErrors = true;
                } else {
                    field.classList.remove('border-red-500');
                }
            });

            if (hasErrors) {
                e.preventDefault();
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
                alert('Please fill in all required fields');
                return;
            }
        });
    });
    </script>
</x-header>
