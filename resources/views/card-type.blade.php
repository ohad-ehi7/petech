<x-header>
<div class="flex p-10">
  <main class="flex-1  space-y-6 ">
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

    <!-- Product Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-10 px-10">
      <!-- Product Card Example -->
      <div class="bg-white rounded-lg border shadow-lg p-6">
        <div class="flex items-center justify-center mb-4">
          <img src="https://via.placeholder.com/150" alt="Bearbrand Milk" class="w-24 h-auto" />
        </div>
        <h2 class="text-xl font-bold text-center">Bearbrand Milk</h2>
        <p class="text-sm text-center text-gray-600">SKU: P001</p>
        <p class="text-center text-red-600 mt-2">Stock on Hand: <span class="font-bold">12.00 boxes</span></p>
        <div class="mt-4">
          <p class="text-center">Selling Price: <span class="font-bold">Php 50.00</span></p>
          <p class="text-center">Cost Price: <span class="font-bold">Php 45.00</span></p>
        </div>
      </div>

      <div class="bg-white rounded-lg border shadow-lg p-6">
        <div class="flex items-center justify-center mb-4">
          <img src="https://via.placeholder.com/150" alt="Nescafe Coffee" class="w-24 h-auto" />
        </div>
        <h2 class="text-xl font-bold text-center">Nescafe Coffee</h2>
        <p class="text-sm text-center text-gray-600">SKU: P001</p>
        <p class="text-center text-green-600 mt-2">Stock on Hand: <span class="font-bold">45.00 boxes</span></p>
        <div class="mt-4">
          <p class="text-center">Selling Price: <span class="font-bold">Php 50.00</span></p>
          <p class="text-center">Cost Price: <span class="font-bold">Php 45.00</span></p>
        </div>
      </div>

      <div class="bg-white rounded-lg border shadow-lg p-6">
        <div class="flex items-center justify-center mb-4">
          <img src="https://via.placeholder.com/150" alt="Nescafe Coffee" class="w-24 h-auto" />
        </div>
        <h2 class="text-xl font-bold text-center">Nescafe Coffee</h2>
        <p class="text-sm text-center text-gray-600">SKU: P001</p>
        <p class="text-center text-green-600 mt-2">Stock on Hand: <span class="font-bold">45.00 boxes</span></p>
        <div class="mt-4">
          <p class="text-center">Selling Price: <span class="font-bold">Php 50.00</span></p>
          <p class="text-center">Cost Price: <span class="font-bold">Php 45.00</span></p>
        </div>
      </div>

      <div class="bg-white rounded-lg border shadow-lg p-6">
        <div class="flex items-center justify-center mb-4">
          <img src="https://via.placeholder.com/150" alt="Nescafe Coffee" class="w-24 h-auto" />
        </div>
        <h2 class="text-xl font-bold text-center">Nescafe Coffee</h2>
        <p class="text-sm text-center text-gray-600">SKU: P001</p>
        <p class="text-center text-green-600 mt-2">Stock on Hand: <span class="font-bold">45.00 boxes</span></p>
        <div class="mt-4">
          <p class="text-center">Selling Price: <span class="font-bold">Php 50.00</span></p>
          <p class="text-center">Cost Price: <span class="font-bold">Php 45.00</span></p>
        </div>
      </div>

      <!-- Add more cards here -->
    </div>
  </main>
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
