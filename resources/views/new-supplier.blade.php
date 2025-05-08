<x-header>
    <div class="p-10">
        <div class="bg-white rounded-xl shadow-md p-6">
            <form class="space-y-8">
                <h1 class="text-2xl font-bold mb-6">Add new supplier</h1>

                <!-- Basic Information Section -->
                <div class="relative pb-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800">Supplier Information</h2>
                    <div class="max-w-2xl mx-auto space-y-4">
                        <!-- Supplier Name -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Supplier Name*</label>
                            <input type="text" name="SupplierName" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Enter supplier name" />
                        </div>

                        <!-- Contact Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Contact Number</label>
                                <input type="tel" name="ContactNumber" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Enter contact number" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Email</label>
                                <input type="email" name="Email" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Enter email address" />
                            </div>
                        </div>

                        <!-- Address -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Address</label>
                            <textarea name="Address" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Enter supplier address"></textarea>
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