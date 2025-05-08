<x-header>
<div class="p-10">
<div class="w-full mx-auto">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Sales Receipts</h1>
      <button class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded">+ New</button>
    </div>

    <!-- Filter Dropdown -->
    <div class="flex items-center justify-between mb-4">
      <div class="relative inline-block text-left">
        <div>
          <button type="button" id="filterToggle" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
            <span class="font-bold">VIEW BY: </span>  PERIOD: <span id="selectedPeriod" class="ml-1">This Month</span>
            <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.586l3.71-4.356a.75.75 0 011.14.976l-4.25 5a.75.75 0 01-1.14 0l-4.25-5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
            </svg>
          </button>
        </div>

        <div id="dropdownPeriod" class="hidden origin-top-right absolute mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10">
          <div class="py-1">
            <button class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" onclick="selectPeriod('Today')">Today</button>
            <button class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" onclick="selectPeriod('This Week')">This Week</button>
            <button class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" onclick="selectPeriod('This Month')">This Month</button>
            <button class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" onclick="selectPeriod('This Year')">This Quarter</button>
            <button class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" onclick="selectPeriod('This Week')">This Year</button>
            <button class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" onclick="selectPeriod('This Month')">Paid</button>
            <button class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" onclick="selectPeriod('This Year')">Void</button>
          </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto bg-white rounded-lg shadow">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Receipt Date</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sales Receipt #</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reference</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment Method</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created By</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr>
          <td class="px-6 py-4 whitespace-nowrap">
              <label class="inline-flex items-center space-x-2">
                <input type="checkbox" class="form-checkbox h-4 w-4 text-blue-600">
                <span>25 Apr 2025</span>
              </label>
            </td>
            <td class="px-6 py-4 text-blue-600 hover:underline cursor-pointer"><a href="sales-receipt">SR-00001</a></td>
            <td class="px-6 py-4">001</td>
            <td class="px-6 py-4">PHP 172.60</td>
            <td class="px-6 py-4">CASH</td>
            <td class="px-6 py-4 text-green-600 font-semibold">PAID</td>
            <td class="px-6 py-4">CHARISSE PRIEGO</td>
          </tr>
          <tr>
          <td class="px-6 py-4 whitespace-nowrap">
              <label class="inline-flex items-center space-x-2">
                <input type="checkbox" class="form-checkbox h-4 w-4 text-blue-600">
                <span>27 Apr 2025</span>
              </label>
            </td>
            <td class="px-6 py-4 text-blue-600 hover:underline cursor-pointer">SR-00002</td>
            <td class="px-6 py-4">002</td>
            <td class="px-6 py-4">PHP 192.60</td>
            <td class="px-6 py-4">CASH</td>
            <td class="px-6 py-4 text-red-500 font-semibold">VOID</td>
            <td class="px-6 py-4">CHARISSE PRIEGO</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  
    
</div>

  <script>
    const filterToggle = document.getElementById('filterToggle');
    const dropdownPeriod = document.getElementById('dropdownPeriod');

    filterToggle.addEventListener('click', () => {
      dropdownPeriod.classList.toggle('hidden');
    });

    function selectPeriod(period) {
      document.getElementById('selectedPeriod').innerText = period;
      dropdownPeriod.classList.add('hidden');
      // Add actual filtering logic here if needed
    }
  </script>

</x-header>
