<x-header>
<div class="flex h-screen bg-gray-100">
  <!-- Sidebar -->
  <aside class="w-72 bg-white border-r p-4 space-y-4">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-lg font-semibold">Sales Receipts</h2>
      <button class="bg-blue-500 text-white text-sm px-3 py-1 rounded hover:bg-blue-600">+ New</button>
    </div>
    <div class="text-xs text-gray-500 uppercase tracking-wide">Period: This Month</div>

    <!-- Receipt 1 -->
    <div class="mt-2 space-y-4">
      <div class="p-3 rounded hover:bg-gray-50 cursor-pointer bg-blue-50">
        <p class="font-medium">Ken Sevellino</p>
        <p class="text-sm text-gray-600">SR-00001 • 25 Apr 2025</p>
        <p class="text-sm text-green-600">Paid</p>
        <p class="text-sm text-gray-800 font-semibold">Php 172.60</p>
        <p class="text-xs text-gray-400">001</p>
      </div>

      <!-- Receipt 2 -->
      <div class="p-3 rounded hover:bg-gray-50 cursor-pointer">
        <p class="font-medium">Customer 1</p>
        <p class="text-sm text-gray-600">SR-00002 • 25 Apr 2025</p>
        <p class="text-sm text-red-600">Void</p>
        <p class="text-sm text-gray-800 font-semibold">Php 185.60</p>
        <p class="text-xs text-gray-400">002</p>
      </div>
    </div>
  </aside>

  <!-- Main Content -->
  <main class="flex-1 p-10">
    <!-- Header controls -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-semibold">SR-00001</h1>
      <div class="space-x-2">
        <button class="text-sm text-gray-600 hover:underline">Edit</button>
        <button class="text-sm text-gray-600 hover:underline">Send</button>
        @if(isset($sale))
            <a href="{{ route('sales-receipt.pdf', ['id' => $sale->SaleID]) }}" 
               target="_blank" 
               class="text-sm text-gray-600 hover:underline"
               onclick="console.log('Generating PDF for sale: {{ $sale->SaleID }}')">
               PDF / Print
            </a>
        @else
            <button class="text-sm text-gray-600 hover:underline" disabled>PDF / Print</button>
        @endif
      </div>
    </div>

    <!-- Sales Receipt Preview -->
    <div class="bg-white p-8 rounded shadow-md max-w-3xl mx-auto space-y-6">
      <div class="text-center">
        <h2 class="text-xl font-bold">Changchang Store IMS/SMS</h2>
        <p class="text-sm text-gray-600">Philippines</p>
        <p class="text-sm text-gray-600">POD@gmail.com</p>
      </div>

      <hr />

      <div>
        <h3 class="text-lg font-semibold mb-2">SALES RECEIPT</h3>
        <div class="grid grid-cols-2 text-sm gap-4">
          <div>
            <p><strong>Sales Receipt#:</strong> SR-00001</p>
            <p><strong>Bill To:</strong> <a href="#" class="text-blue-600 hover:underline">Ken Sevellino</a></p>
          </div>
          <div class="text-right">
            <p><strong>Receipt Date:</strong> 25 Apr 2025</p>
            <p><strong>Reference:</strong> 001</p>
          </div>
        </div>
      </div>

      <div class="bg-gray-100 p-4 rounded text-sm">
        <p><strong>Status:</strong> <span class="text-green-600 font-semibold">Paid</span></p>
        <p><strong>Amount:</strong> Php 172.60</p>
      </div>
    </div>
  </main>
</div>


</x-header>