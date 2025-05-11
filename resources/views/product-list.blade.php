<x-header>
<div class=" p-10 ">
<div class="w-full mx-auto">
    

    <!-- Header Row: Filter + Right Buttons -->
<div class="flex items-center justify-between mb-4">
  <!-- Filter Dropdown -->
  <div class="relative inline-block text-left">
    <button type="button" id="filterToggle" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
      <span id="selectedPeriod" class="ml-1">Beverages</span>
      <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.586l3.71-4.356a.75.75 0 011.14.976l-4.25 5a.75.75 0 01-1.14 0l-4.25-5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
      </svg>
    </button>

    <div id="dropdownPeriod" class="hidden origin-top-right absolute mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10">
      <div class="py-1">
        <button class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" onclick="selectPeriod('Today')">Groceries</button>
        <button class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" onclick="selectPeriod('This Week')">Snacks</button>
        <button class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" onclick="selectPeriod('This Month')">Household goods</button>
        <button class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" onclick="selectPeriod('This Year')">Other</button>
      </div>
    </div>
  </div>

  <!-- Right Buttons -->
  <div class="flex items-center space-x-2">
    <a href="new-item" class="flex items-center px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
      <i class="fa-solid fa-plus mr-2"></i>New
    </a>
    <button class="p-2 hover:bg-gray-200 rounded"><i class="fa-solid fa-list"></i></button>
    <a href="card-type" class="p-2 hover:bg-gray-200 rounded"><i class="fa-solid fa-th"></i></a>
    <button class="p-2 hover:bg-gray-200 rounded"><i class="fa-solid fa-ellipsis-vertical"></i></button>
  </div>
</div>


   

    <!-- Table -->
    <div class="overflow-x-auto bg-white rounded-lg shadow">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU (ID)</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock on hand</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reorder Level</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Supplier</th>

            
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr>
            <td class="px-6 py-4 whitespace-nowrap">
              <label class="inline-flex items-center space-x-2">
                <input type="checkbox" class="form-checkbox h-4 w-4 text-blue-600">
                <a href="product-overview"><span>Bear Brand</span></a>
              </label>
            </td>
            <td class="px-6 py-4 text-blue-600 hover:underline cursor-pointer">PD01</td>
            <td class="px-6 py-4">45.00</td>
            <td class="px-6 py-4">12</td>
            <td class="px-6 py-4 text-green-600 font-semibold">Good</td>
             <td class="px-6 py-4">Davao Distribution</td>
          </tr>
          <tr>
          <td class="px-6 py-4 whitespace-nowrap">
              <label class="inline-flex items-center space-x-2">
                <input type="checkbox" class="form-checkbox h-4 w-4 text-blue-600">
                <span>Nescafe Stick</span>
              </label>
            </td>
            <td class="px-6 py-4 text-blue-600 hover:underline cursor-pointer">PD01</td>
            <td class="px-6 py-4">11.00</td>
            <td class="px-6 py-4">12</td>
            <td class="px-6 py-4 text-red-600">
              <p class="font-bold">To Restock</p>
              <p class="italic text-sm">
                Reorder level reached, <br> you have to restock <span class="font-bold italic">ASAP</span>
              </p>
            </td>
            <td class="px-6 py-4">Greatwall Trading</td>
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
