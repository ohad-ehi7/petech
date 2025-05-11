<x-header>
    <div class="p-10">
        <div class="bg-white rounded-xl shadow-md p-6">
            <form method="POST" action="{{ route('categories.update', $category) }}" class="space-y-8">
                @csrf
                @method('PUT')
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Edit Category</h1>
                    <a href="{{ route('categories.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors">
                        Back to List
                    </a>
                </div>

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Basic Information Section -->
                <div class="relative pb-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800">Category Information</h2>
                    <div class="max-w-2xl mx-auto space-y-4">
                        <!-- Category Name -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Category Name*</label>
                            <input type="text" name="CategoryName" value="{{ old('CategoryName', $category->CategoryName) }}" 
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('CategoryName') border-red-500 @enderror" 
                                placeholder="Enter category name" required />
                            @error('CategoryName')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent"></div>
                </div>

                <!-- Save and Cancel Buttons -->
                <div class="flex justify-end space-x-4 mt-6">
                    <a href="{{ route('categories.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors">Cancel</a>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</x-header> 