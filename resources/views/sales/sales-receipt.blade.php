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
      <!-- Header with background image -->
      <div class="relative overflow-hidden rounded-lg mb-2" style="background: url('/images/small-background.png') center/cover no-repeat; min-height: 90px;">
        <div class="flex flex-col items-center justify-center py-6 bg-white/80 ">
          <h2 class="text-xl font-bold">Changchang Store IMS/SMS</h2>
          <p class="text-sm text-gray-600">Philippines</p>
          <p class="text-sm text-gray-600">POD@gmail.com</p>
        </div>
      </div>

      <div class="border-b border-gray-200"></div>

      <!-- Receipt Title and Info -->
      <div>
        <h3 class="text-lg font-bold mb-1">SALES RECEIPT</h3>
        <p class="text-sm text-gray-700 mb-2">Sales Receipt# SR-{{ isset($sale) ? str_pad($sale->SaleID, 5, '0', STR_PAD_LEFT) : '00001' }}</p>
        <div class="flex flex-wrap justify-between text-sm mb-2">
          <div>
            <span class="font-semibold">Bill To</span><br>
            <a href="#" class="text-blue-600 hover:underline">
              {{ isset($sale) && $sale->customer ? $sale->customer->name : 'Ken Sevellino' }}
            </a>
          </div>
          <div>
            <span class="font-semibold">Receipt Date</span><br>
            {{ isset($sale) ? ($sale->SaleDate ? \Carbon\Carbon::parse($sale->SaleDate)->format('d M Y') : '') : '25 Apr 2025' }}
          </div>
          <div>
            <span class="font-semibold">Reference</span><br>
            {{ isset($sale) ? str_pad($sale->SaleID, 3, '0', STR_PAD_LEFT) : '001' }}
          </div>
        </div>
      </div>

      <!-- Item Table -->
      <div class="overflow-x-auto rounded-lg border border-gray-200">
        <table class="min-w-full text-sm text-left">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-3 py-2 font-medium text-gray-600">#</th>
              <th class="px-3 py-2 font-medium text-gray-600">Item Description</th>
              <th class="px-3 py-2 font-medium text-gray-600">Qty</th>
              <th class="px-3 py-2 font-medium text-gray-600">Rate</th>
              <th class="px-3 py-2 font-medium text-gray-600">Amount</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-100">
            @if(isset($sale) && $sale->salesItems)
              @foreach($sale->salesItems as $i => $item)
                <tr>
                  <td class="px-3 py-2">{{ $i+1 }}</td>
                  <td class="px-3 py-2">
                    {{ $item->product->name ?? 'Item' }}
                    <div class="text-xs text-gray-500">{{ $item->product->description ?? '' }}</div>
                  </td>
                  <td class="px-3 py-2">{{ number_format($item->Quantity, 2) }}</td>
                  <td class="px-3 py-2">{{ number_format($item->PriceAtSale, 2) }}</td>
                  <td class="px-3 py-2">{{ number_format($item->Quantity * $item->PriceAtSale, 2) }}</td>
                </tr>
              @endforeach
            @else
              <tr>
                <td class="px-3 py-2">1</td>
                <td class="px-3 py-2">Bearbrand Milk<div class="text-xs text-gray-500">45g Fortified Milk</div></td>
                <td class="px-3 py-2">1.00</td>
                <td class="px-3 py-2">50.00</td>
                <td class="px-3 py-2">50.00</td>
              </tr>
              <tr>
                <td class="px-3 py-2">2</td>
                <td class="px-3 py-2">Nescafe Coffee<div class="text-xs text-gray-500">45g tetra pack,original coffee</div></td>
                <td class="px-3 py-2">2.00</td>
                <td class="px-3 py-2">55.00</td>
                <td class="px-3 py-2">110.00</td>
              </tr>
            @endif
          </tbody>
        </table>
      </div>

      <!-- Payment Details and Totals -->
      <div class="flex flex-wrap gap-6 mt-4">
        <div class="flex-1 min-w-[200px] bg-gray-50 border rounded p-4">
          <div class="font-semibold mb-2">Payment Details</div>
          <div class="flex justify-between text-sm mb-1">
            <span>Payment Mode</span>
            <span class="font-semibold">{{ isset($sale) ? ($sale->PaymentMethod ?? 'Cash') : 'Cash' }}</span>
          </div>
          <div class="flex justify-between text-sm mb-1">
            <span>Amount Paid</span>
            <span class="font-semibold">₱{{ isset($sale) ? number_format($sale->AmountPaid, 2) : '0.00' }}</span>
          </div>
          <div class="flex justify-between text-sm">
            <span>Change</span>
            <span>₱{{ isset($sale) ? number_format($sale->AmountPaid - $sale->TotalAmount, 2) : '0.00' }}</span>
          </div>
        </div>
        <div class="flex-1 min-w-[200px]">
          <table class="w-full text-sm">
            <tr>
              <td class="py-1">Sub Total</td>
              <td class="py-1 text-right">{{ isset($sale) ? number_format($sale->TotalAmount - ($sale->TotalAmount * 0.12), 2) : '160.00' }}</td>
            </tr>
            <tr>
              <td class="py-1">vat(12%)</td>
              <td class="py-1 text-right">{{ isset($sale) ? number_format($sale->TotalAmount * 0.12, 2) : '12.60' }}</td>
            </tr>
            <tr class="border-t">
              <td class="py-2 font-bold">Total</td>
              <td class="py-2 text-right font-bold">{{ isset($sale) ? number_format($sale->TotalAmount, 2) : '172.60' }}</td>
            </tr>
          </table>
        </div>
      </div>

      <!-- Terms and Conditions -->
      <div class="mt-6">
        <div class="font-semibold text-sm mb-1">Terms and Conditions</div>
        <p class="text-xs text-gray-600">
          Prices may change without notice and depend on item availability. Full payment is required upon purchase unless credit is approved. Credit must be paid within 7 days; late payments may lead to suspension of credit. Items can be returned or exchanged within 2 days only if defective or expired, with a receipt. Personal data is used only for store records. Terms may change anytime with notice to buyers.
        </p>
      </div>
    </div>
  </main>
</div>


</x-header>