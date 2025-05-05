<x-header>
<div class="flex p-10 -mt-8">
  <main class="flex-1 pr-4 space-y-6 overflow-x-hidden">
    <!-- Category and Buttons -->
    <div class="w-full p-4">
      <div class="flex items-center justify-between">
        <!-- Category Dropdown -->
        <ul class="flex items-center space-x-4">
          <li class="list-none relative">
            <button type="button"
              class="flex items-center p-2 text-base text-black transition duration-150 rounded-lg hover:bg-black dark:text-black dark:hover:bg-gray-700 group"
              aria-controls="dropdown-example" data-collapse-toggle="dropdown-example">
              <span class="flex-1 text-left whitespace-nowrap">Category</span>
              <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 10 6">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4" />
              </svg>
            </button>
            <ul id="dropdown-example" class="hidden absolute right-0 mt-2 py-2 w-48 bg-white border border-gray-200 rounded shadow-md space-y-2">
              <li><a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Products</a></li>
              <li><a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Billing</a></li>
              <li><a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Invoice</a></li>
            </ul>
          </li>
        </ul>

        <!-- Action Buttons -->
        <div class="flex items-center space-x-2">
          <a href="new-item" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
            <i class="fa-solid fa-plus mr-2"></i>New
          </a>
          <button class="p-2 text-gray-900 rounded-lg hover:bg-gray-100"><i class="fa-solid fa-list"></i></button>
          <button class="p-2 text-gray-900 rounded-lg hover:bg-gray-100"><i class="fa-solid fa-th"></i></button>
          <button class="p-2 text-gray-900 rounded-lg hover:bg-gray-100"><i class="fa-solid fa-ellipsis-vertical"></i></button>
        </div>
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
</x-header>
