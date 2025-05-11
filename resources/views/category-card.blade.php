<x-header>
     <div class="flex justify-between items-center mb-6 p-10">
    <h1 class="text-2xl font-semibold">Categories</h1>
    <div class="space-x-2">
      <button class="p-2 border rounded hover:bg-gray-200">
        <!-- List View Icon -->
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M4 6h16M4 10h16M4 14h16M4 18h16" />
        </svg>
      </button>
      <button class="p-2 border rounded hover:bg-gray-200 bg-gray-200">
        <!-- Grid View Icon -->
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M4 6h4v4H4V6zm6 0h4v4h-4V6zm6 0h4v4h-4V6zM4 12h4v4H4v-4zm6 0h4v4h-4v-4zm6 0h4v4h-4v-4zM4 18h4v4H4v-4zm6 0h4v4h-4v-4zm6 0h4v4h-4v-4z" />
        </svg>
      </button>
    </div>
  </div>


  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 ml-10">
    <!-- Category Card -->
    <div class="bg-white rounded-xl shadow p-4 text-center w-82">
      <img src="https://cdn-icons-png.flaticon.com/512/1046/1046784.png" alt="Food" class="mx-auto w-16 h-16 mb-3" />
      <h2 class="font-semibold">Food & Beverages</h2>
      <p class="text-sm text-gray-500">CID: 001</p>
      <p class="mt-2">25 Products</p>
      <ul class="text-sm mt-2 space-y-1">
        <li><span class="text-green-600">●</span> 10 Good</li>
        <li><span class="text-yellow-500">●</span> 5 To Restock</li>
        <li><span class="text-red-500">●</span> 5 Out of Stock</li>
      </ul>
    </div>

    <!-- Duplicate the card and change content for the rest of the categories -->
    <!-- Household Essentials -->
    <div class="bg-white rounded-xl shadow p-4 text-center w-82">
      <img src="https://cdn-icons-png.flaticon.com/512/2944/2944576.png" alt="Essentials" class="mx-auto w-16 h-16 mb-3" />
      <h2 class="font-semibold">Households Essentials</h2>
      <p class="text-sm text-gray-500">CID: 002</p>
      <p class="mt-2">25 Products</p>
      <ul class="text-sm mt-2 space-y-1">
        <li><span class="text-green-600">●</span> 10 Good</li>
        <li><span class="text-yellow-500">●</span> 5 To Restock</li>
        <li><span class="text-red-500">●</span> 5 Out of Stock</li>
      </ul>
    </div>

    <!-- Add more categories by repeating the structure above -->
    <!-- Replace image links with appropriate icons or placeholders -->
    
    <!-- Example of a few more, abbreviated: -->
    <div class="bg-white rounded-xl shadow p-4 text-center w-82">
      <img src="https://cdn-icons-png.flaticon.com/512/2965/2965567.png" alt="Health" class="mx-auto w-16 h-16 mb-3" />
      <h2 class="font-semibold">Health & Personal Care</h2>
      <p class="text-sm text-gray-500">CID: 003</p>
      <p class="mt-2">25 Products</p>
      <ul class="text-sm mt-2 space-y-1">
        <li><span class="text-green-600">●</span> 10 Good</li>
        <li><span class="text-yellow-500">●</span> 5 To Restock</li>
        <li><span class="text-red-500">●</span> 5 Out of Stock</li>
      </ul>
    </div>

    <!-- Add remaining cards: Clothing, Home & Kitchen, Baby & Kids, Stationary -->
     <div class="bg-white rounded-xl shadow p-4 text-center w-82">
      <img src="https://cdn-icons-png.flaticon.com/512/3081/3081559.png" alt="Clothing" class="mx-auto w-16 h-16 mb-3" />
      <h2 class="font-semibold">Clothing and Accessories</h2>
      <p class="text-sm text-gray-500">CID: 003</p>
      <p class="mt-2">25 Products</p>
      <ul class="text-sm mt-2 space-y-1">
        <li><span class="text-green-600">●</span> 10 Good</li>
        <li><span class="text-yellow-500">●</span> 5 To Restock</li>
        <li><span class="text-red-500">●</span> 5 Out of Stock</li>
      </ul>
    </div>

    <div class="bg-white rounded-xl shadow p-4 text-center w-82 mt-10">
      <img src="https://cdn-icons-png.flaticon.com/512/3659/3659899.png" alt="Home" class="mx-auto w-16 h-16 mb-3" />
      <h2 class="font-semibold">Home and Kitchen</h2>
      <p class="text-sm text-gray-500">CID: 003</p>
      <p class="mt-2">25 Products</p>
      <ul class="text-sm mt-2 space-y-1">
        <li><span class="text-green-600">●</span> 10 Good</li>
        <li><span class="text-yellow-500">●</span> 5 To Restock</li>
        <li><span class="text-red-500">●</span> 5 Out of Stock</li>
      </ul>
    </div>

    <div class="bg-white rounded-xl shadow p-4 text-center w-82 mt-10">
      <img src="https://cdn-icons-png.flaticon.com/512/1785/1785210.png" alt="Baby" class="mx-auto w-16 h-16 mb-3" />
      <h2 class="font-semibold">Baby & Kids</h2>
      <p class="text-sm text-gray-500">CID: 003</p>
      <p class="mt-2">25 Products</p>
      <ul class="text-sm mt-2 space-y-1">
        <li><span class="text-green-600">●</span> 10 Good</li>
        <li><span class="text-yellow-500">●</span> 5 To Restock</li>
        <li><span class="text-red-500">●</span> 5 Out of Stock</li>
      </ul>
    </div>

    <div class="bg-white rounded-xl shadow p-4 text-center w-82 mt-10">
      <img src="https://cdn-icons-png.flaticon.com/512/2232/2232688.png" alt="Stationary" class="mx-auto w-16 h-16 mb-3" />
      <h2 class="font-semibold">Stationary & Office Supplies</h2>
      <p class="text-sm text-gray-500">CID: 003</p>
      <p class="mt-2">25 Products</p>
      <ul class="text-sm mt-2 space-y-1">
        <li><span class="text-green-600">●</span> 10 Good</li>
        <li><span class="text-yellow-500">●</span> 5 To Restock</li>
        <li><span class="text-red-500">●</span> 5 Out of Stock</li>
      </ul>
    </div>

    <a href="http://127.0.0.1:8000/categories/create" class="block">
      <div class="bg-white rounded-xl shadow p-4 text-center w-82 mt-10 h-[255px] hover:bg-gray-50 transition-colors duration-200 cursor-pointer flex flex-col items-center justify-center">
        <img src="https://cdn-icons-png.flaticon.com/512/1828/1828911.png" alt="Add Category" class="w-16 h-16 mb-3" />
        <h2 class="font-semibold">Add Category</h2>
      </div>
    </a>
  </div>
</x-header>