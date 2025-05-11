<x-header :title="'Add New Supplier'">
    <div class="p-10">
        <div class="bg-white rounded-xl shadow-md p-6">
            <form method="POST" action="{{ route('suppliers.store') }}" class="space-y-8">
                @csrf
                <h1 class="text-2xl font-bold mb-6">Add new supplier</h1>

                <!-- Basic Information Section -->
                <div class="relative pb-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800">Supplier Information</h2>
                    <div class="max-w-2xl mx-auto space-y-4">
                        <!-- Supplier Name -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Supplier Name*</label>
                            <input type="text" name="SupplierName" value="{{ old('SupplierName') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('SupplierName') border-red-500 @enderror" placeholder="Enter supplier name" required />
                            @error('SupplierName')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Contact Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Contact Number</label>
                                <input type="tel" name="ContactNumber" value="{{ old('ContactNumber') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('ContactNumber') border-red-500 @enderror" placeholder="Enter contact number" />
                                @error('ContactNumber')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Email</label>
                                <input type="email" name="Email" value="{{ old('Email') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('Email') border-red-500 @enderror" placeholder="Enter email address" />
                                @error('Email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Address -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Address</label>
                            <textarea name="Address" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('Address') border-red-500 @enderror" placeholder="Enter supplier address">{{ old('Address') }}</textarea>
                            @error('Address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent"></div>
                </div>

                <!-- Save and Cancel Buttons -->
                <div class="flex justify-end space-x-4 mt-6">
                    <a href="{{ route('suppliers.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors">Cancel</a>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">Save</button>
                </div>
            </form>
        </div>
    </div>
</x-header>

{{--<x-header>--}}
{{--  <!-- Container Wrapper with relative positioning -->--}}
{{--  <div class="relative">--}}

{{--    <!-- Top Right Action Buttons -->--}}
{{--    <div class="absolute top-6 right-6 flex items-center space-x-2 z-10">--}}
{{--      <!-- Edit Icon Button -->--}}
{{--      <button class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded hover:bg-gray-100">--}}
{{--        ✎--}}
{{--      </button>--}}

{{--      <!-- Add Supplier Button -->--}}
{{--      <button class="bg-blue-500 text-white text-sm font-medium px-4 py-2 rounded hover:bg-blue-600 transition">--}}
{{--        <a href="add-supplier">Add Supplier</a>--}}
{{--      </button>--}}

{{--      <!-- Close Button -->--}}
{{--      <button class="text-xl text-black hover:text-red-600">×</button>--}}
{{--    </div>--}}

{{--    <!-- Main Flex Layout -->--}}
{{--    <div class="flex p-6">--}}

{{--      <!-- Sidebar -->--}}
{{--      <aside class="w-64 bg-white shadow h-screen p-6">--}}
{{--        <div class="mb-6 flex items-center justify-between">--}}
{{--          <h2 class="text-lg font-semibold">All Suppliers</h2>--}}
{{--          <a href="new-supplier" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">+ New</a>--}}
{{--        </div>--}}

{{--        <ul class="space-y-4">--}}
{{--          <!-- Supplier 1 -->--}}
{{--          <li class="flex items-start justify-between">--}}
{{--            <label class="flex items-start space-x-3">--}}
{{--              <input type="checkbox" class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">--}}
{{--              <div>--}}
{{--                <p class="font-medium text-sm">Greatwall Trading...</p>--}}
{{--                <span class="text-gray-500 text-xs">SUI: S001</span>--}}
{{--              </div>--}}
{{--            </label>--}}
{{--            <span class="text-green-600 text-sm font-medium">Active</span>--}}
{{--          </li>--}}

{{--          <!-- Supplier 2 -->--}}
{{--          <li class="flex items-start justify-between">--}}
{{--            <label class="flex items-start space-x-3">--}}
{{--              <input type="checkbox" class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">--}}
{{--              <div>--}}
{{--                <p class="font-medium text-sm">Davao Asian Distr...</p>--}}
{{--                <span class="text-gray-500 text-xs">SUI: S002</span>--}}
{{--              </div>--}}
{{--            </label>--}}
{{--            <span class="text-green-600 text-sm font-medium">Active</span>--}}
{{--          </li>--}}

{{--          <!-- Supplier 3 -->--}}
{{--          <li class="flex items-start justify-between">--}}
{{--            <label class="flex items-start space-x-3">--}}
{{--              <input type="checkbox" class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">--}}
{{--              <div>--}}
{{--                <p class="font-medium text-sm">Rafski Food Solut...</p>--}}
{{--                <span class="text-gray-500 text-xs">SUI: S003</span>--}}
{{--              </div>--}}
{{--            </label>--}}
{{--            <span class="text-green-600 text-sm font-medium">Active</span>--}}
{{--          </li>--}}

{{--          <!-- Supplier 4 -->--}}
{{--          <li class="flex items-start justify-between">--}}
{{--            <label class="flex items-start space-x-3">--}}
{{--              <input type="checkbox" class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">--}}
{{--              <div>--}}
{{--                <p class="font-medium text-sm">RL Bodega Davao</p>--}}
{{--                <span class="text-gray-500 text-xs">SUI: S004</span>--}}
{{--              </div>--}}
{{--            </label>--}}
{{--            <span class="text-red-600 text-sm font-medium">Inactive</span>--}}
{{--          </li>--}}
{{--        </ul>--}}
{{--      </aside>--}}

{{--      <!-- Main Content -->--}}
{{--      <main class="flex-1 p-6 bg-white space-y-8">--}}

{{--        <!-- Header -->--}}
{{--        <div>--}}
{{--          <h1 class="text-2xl font-semibold">Greatwall Trading & Supermarket</h1>--}}
{{--          <p class="text-sm text-gray-500">Davao City</p>--}}
{{--        </div>--}}

{{--        <!-- Tabs -->--}}
{{--        <nav>--}}
{{--          <ul class="flex border-b space-x-6 text-sm">--}}
{{--            <li class="pb-2 font-medium text-gray-600 cursor-pointer hover:text-black">Overview</li>--}}
{{--            <li class="pb-2 font-semibold border-b-2 border-blue-600 text-blue-600">Transactions</li>--}}
{{--            <li class="pb-2 text-gray-500 cursor-pointer hover:text-black">History</li>--}}
{{--          </ul>--}}
{{--        </nav>--}}

{{--        <!-- Filters -->--}}
{{--        <div class="flex items-center gap-6">--}}
{{--          <div>--}}
{{--            <label class="text-sm font-medium text-gray-700">Filter By: </label>--}}
{{--            <select class="ml-2 text-sm border rounded px-3 py-1 bg-white">--}}
{{--              <option>Deliver #</option>--}}
{{--            </select>--}}
{{--          </div>--}}
{{--          <div>--}}
{{--            <label class="text-sm font-medium text-gray-700">Status: </label>--}}
{{--            <select class="ml-2 text-sm border rounded px-3 py-1 bg-white">--}}
{{--              <option>All</option>--}}
{{--            </select>--}}
{{--          </div>--}}
{{--        </div>--}}

{{--        <!-- Transactions Table -->--}}
{{--        <div class="overflow-x-auto">--}}
{{--          <table class="min-w-full border-t text-sm text-left text-gray-700">--}}
{{--            <thead class="bg-gray-50">--}}
{{--              <tr>--}}
{{--                <th class="px-4 py-2 font-medium text-gray-600">Date</th>--}}
{{--                <th class="px-4 py-2 font-medium text-gray-600">Deliver #</th>--}}
{{--                <th class="px-4 py-2 font-medium text-gray-600">Units Delivered</th>--}}
{{--                <th class="px-4 py-2 font-medium text-gray-600">Total Cost</th>--}}
{{--                <th class="px-4 py-2 font-medium text-gray-600">Status</th>--}}
{{--              </tr>--}}
{{--            </thead>--}}
{{--            <tbody class="divide-y">--}}
{{--              <tr class="hover:bg-gray-50">--}}
{{--                <td class="px-4 py-2">19 Apr 2025</td>--}}
{{--                <td class="px-4 py-2">DE-00001</td>--}}
{{--                <td class="px-4 py-2">36.00</td>--}}
{{--                <td class="px-4 py-2">PHP 22000.00</td>--}}
{{--                <td class="px-4 py-2 text-green-600 font-semibold">SOLD</td>--}}
{{--              </tr>--}}
{{--            </tbody>--}}
{{--          </table>--}}
{{--        </div>--}}
{{--      </main>--}}
{{--    </div>--}}
{{--  </div>--}}
{{--</x-header>--}}
