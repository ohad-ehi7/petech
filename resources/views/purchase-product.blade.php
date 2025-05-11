<x-header>
  <!-- Purchase Module UI -->
  <div class="p-6 space-y-8">
    <div class="flex items-center justify-between">
      <h2 class="text-2xl font-semibold text-gray-800">🛒 Purchase Module</h2>
      <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center gap-2">
        <i class="fas fa-plus"></i>
        New Purchase Order
      </button>
    </div>

    <!-- Create Purchase Order Form -->
    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
      <h3 class="text-lg font-semibold text-gray-800 mb-6">Create Purchase Order</h3>
      <form id="poForm" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-2">
          <label class="text-sm font-medium text-gray-700">Supplier Name </label>
          <input type="text" placeholder="Enter supplier name" class="input ml-2 p-2" required />
        </div>
        <div class="space-y-2">
          <label class="text-sm font-medium text-gray-700">Product Name</label>
          <input type="text" placeholder="Enter product name" class="input ml-2 p-2" required />
        </div>
        <div class="space-y-2">
          <label class="text-sm font-medium text-gray-700">Quantity</label>
          <input type="number" placeholder="Enter quantity" class="input ml-2 p-2" required />
        </div>
        <div class="space-y-2">
          <label class="text-sm font-medium text-gray-700">Cost per Unit</label>
          <div class="relative">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">₱</span>
            <input type="number" placeholder="0.00" class="input pl-8 " required />
          </div>
        </div>
        <div class="space-y-2">
          <label class="text-sm font-medium text-gray-700">Expected Delivery Date</label>
          <input type="date" class="input " required />
        </div>
        <div class="flex items-end">
          <button type="submit" class="w-full bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center justify-center gap-2">
            <i class="fas fa-save"></i>
            Create PO
          </button>
        </div>
      </form>
    </div>

    <!-- Purchase Order List -->
    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-800">Purchase Order List</h3>
        <div class="flex items-center gap-4">
          <div class="relative">
            <input type="text" placeholder="Search POs..." class="input pl-10" />
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
          </div>
          <select class="input">
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="delivered">Delivered</option>
          </select>
        </div>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="bg-gray-50">
              <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">PO Number</th>
              <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Supplier</th>
              <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Product</th>
              <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Quantity</th>
              <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Total Cost</th>
              <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Status</th>
              <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Actions</th>
            </tr>
          </thead>
          <tbody id="poList" class="divide-y divide-gray-100">
            <!-- Table rows will be dynamically added here -->
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
    const poForm = document.getElementById('poForm');
    const poList = document.getElementById('poList');

    poForm.addEventListener('submit', function(e) {
      e.preventDefault();
      const [supplier, product, quantity, cost, date] = Array.from(poForm.querySelectorAll('input')).map(input => input.value);
      
      const row = document.createElement('tr');
      row.className = 'hover:bg-gray-50 transition-colors duration-200';
      row.innerHTML = `
        <td class="px-4 py-3 text-sm text-gray-900">PO-${Date.now().toString().slice(-6)}</td>
        <td class="px-4 py-3 text-sm text-gray-900">${supplier}</td>
        <td class="px-4 py-3 text-sm text-gray-900">${product}</td>
        <td class="px-4 py-3 text-sm text-gray-900">${quantity}</td>
        <td class="px-4 py-3 text-sm text-gray-900">₱${(quantity * cost).toFixed(2)}</td>
        <td class="px-4 py-3">
          <span class="px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded-full">Pending</span>
        </td>
        <td class="px-4 py-3">
          <div class="flex items-center gap-2">
            <button class="p-1 text-blue-600 hover:text-blue-800">
              <i class="fas fa-edit"></i>
            </button>
            <button class="p-1 text-red-600 hover:text-red-800">
              <i class="fas fa-trash"></i>
            </button>
          </div>
        </td>
      `;
      poList.appendChild(row);
      poForm.reset();
    });
  </script>

  <style>
    .input {
      @apply w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200;
    }
  </style>
</x-header>