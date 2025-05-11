<x-header :title="'Edit Supplier'">
    <div class="p-10">
        <div class="bg-white rounded-xl shadow-md p-6">
            <form method="POST" action="{{ route('suppliers.update', $supplier) }}" class="space-y-8">
                @csrf
                @method('PUT')
                <h1 class="text-2xl font-bold mb-6">Edit Supplier</h1>

                <!-- Basic Information Section -->
                <div class="relative pb-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800">Supplier Information</h2>
                    <div class="max-w-2xl mx-auto space-y-4">
                        <!-- Supplier Name -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Supplier Name*</label>
                            <input type="text" 
                                   name="SupplierName" 
                                   value="{{ old('SupplierName', $supplier->SupplierName) }}" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('SupplierName') border-red-500 @enderror" 
                                   placeholder="Enter supplier name" 
                                   required />
                            @error('SupplierName')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Contact Information -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Contact Number</label>
                            <input type="text" 
                                   name="ContactNumber" 
                                   value="{{ old('ContactNumber', $supplier->ContactNumber) }}" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('ContactNumber') border-red-500 @enderror" 
                                   placeholder="Enter contact number" />
                            @error('ContactNumber')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Email</label>
                            <input type="email" 
                                   name="Email" 
                                   value="{{ old('Email', $supplier->Email) }}" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('Email') border-red-500 @enderror" 
                                   placeholder="Enter email address" />
                            @error('Email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Address</label>
                            <textarea name="Address" 
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('Address') border-red-500 @enderror" 
                                      placeholder="Enter address" 
                                      rows="3">{{ old('Address', $supplier->Address) }}</textarea>
                            @error('Address')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('suppliers.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Update Supplier
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-header> 