<x-header>
  <div class="p-6 space-y-8">
    <div class="flex items-center justify-between">
      <h2 class="text-2xl font-semibold text-gray-800">🧾 Billing Module</h2>
      <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center gap-2">
        <i class="fas fa-plus"></i>
        New Bill
      </button>
    </div>

    <!-- Create Bill Form -->
    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
      <h3 class="text-lg font-semibold text-gray-800 mb-6">Create Bill</h3>
      <form id="billForm" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-2">
          <label class="text-sm font-medium text-gray-700">Supplier Name</label>
          <input type="text" id="supplier" placeholder="Enter supplier name" class="input" required>
        </div>
        <div class="space-y-2">
          <label class="text-sm font-medium text-gray-700">PO Reference</label>
          <input type="text" id="poRef" placeholder="Enter PO reference" class="input" required>
        </div>
        <div class="space-y-2">
          <label class="text-sm font-medium text-gray-700">Amount</label>
          <div class="relative">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">₱</span>
            <input type="number" id="amount" placeholder="0.00" class="input pl-8" required>
          </div>
        </div>
        <div class="space-y-2">
          <label class="text-sm font-medium text-gray-700">Payment Method</label>
          <select id="paymentMethod" class="input">
            <option value="">Select payment method</option>
            <option value="Cash">Cash</option>
            <option value="Check">Check</option>
            <option value="Bank Transfer">Bank Transfer</option>
            <option value="Credit Terms">Credit Terms</option>
          </select>
        </div>
        <div class="flex items-end justify-end md:col-span-2">
          <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center gap-2">
            <i class="fas fa-save"></i>
            Create Bill
          </button>
        </div>
      </form>
    </div>

    <!-- Pending Payments -->
    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-800">📌 Pending Payments</h3>
        <div class="flex items-center gap-4">
          <div class="relative">
            <input type="text" placeholder="Search bills..." class="input pl-10" />
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
          </div>
          <select class="input">
            <option value="">All Payment Methods</option>
            <option value="Cash">Cash</option>
            <option value="Check">Check</option>
            <option value="Bank Transfer">Bank Transfer</option>
            <option value="Credit Terms">Credit Terms</option>
          </select>
        </div>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="bg-gray-50">
              <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Supplier</th>
              <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">PO Reference</th>
              <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Amount</th>
              <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Payment Method</th>
              <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Status</th>
              <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Actions</th>
            </tr>
          </thead>
          <tbody id="pendingTable" class="divide-y divide-gray-100"></tbody>
        </table>
      </div>
    </div>

    <!-- Payment History -->
    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-800">📄 Payment History</h3>
        <div class="flex items-center gap-4">
          <div class="relative">
            <input type="text" placeholder="Search history..." class="input pl-10" />
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
          </div>
          <select class="input">
            <option value="">All Time</option>
            <option value="today">Today</option>
            <option value="week">This Week</option>
            <option value="month">This Month</option>
          </select>
        </div>
      </div>
      <div class="space-y-4">
        <ul id="historyList" class="divide-y divide-gray-100"></ul>
      </div>
    </div>
  </div>

  <script>
    const form = document.getElementById('billForm');
    const pendingTable = document.getElementById('pendingTable');
    const historyList = document.getElementById('historyList');

    form.addEventListener('submit', (e) => {
      e.preventDefault();

      const supplier = document.getElementById('supplier').value;
      const poRef = document.getElementById('poRef').value;
      const amount = document.getElementById('amount').value;
      const paymentMethod = document.getElementById('paymentMethod').value;

      // Add to Pending Table
      const row = document.createElement('tr');
      row.className = 'hover:bg-gray-50 transition-colors duration-200';
      row.innerHTML = `
        <td class="px-4 py-3 text-sm text-gray-900">${supplier}</td>
        <td class="px-4 py-3 text-sm text-gray-900">${poRef}</td>
        <td class="px-4 py-3 text-sm text-gray-900">₱${parseFloat(amount).toFixed(2)}</td>
        <td class="px-4 py-3 text-sm text-gray-900">${paymentMethod}</td>
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
      pendingTable.appendChild(row);

      // Add to History
      const historyItem = document.createElement('li');
      historyItem.className = 'py-3';
      historyItem.innerHTML = `
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-green-100">
              <i class="fas fa-check text-green-600"></i>
            </span>
            <div>
              <p class="text-sm font-medium text-gray-900">Bill created for ${supplier}</p>
              <p class="text-sm text-gray-500">PO: ${poRef} • ₱${parseFloat(amount).toFixed(2)} • ${paymentMethod}</p>
            </div>
          </div>
          <span class="text-xs text-gray-400">Just now</span>
        </div>
      `;
      historyList.insertBefore(historyItem, historyList.firstChild);

      form.reset();
    });
  </script>

  <style>
    .input {
      @apply w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200;
    }
  </style>
</x-header>
