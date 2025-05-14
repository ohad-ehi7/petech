<x-header :title="'Sales Receipt Details'">
<div class="p-10">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold">Sales Receipt Details</h1>
                <p class="text-gray-600">SR-{{ str_pad($sale->SaleID, 5, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('sales.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded">
                    Back to Sales
                </a>
                <button onclick="window.print()" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded">
                    Print Receipt
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <!-- Sale Information -->
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <h2 class="text-lg font-semibold mb-4">Sale Information</h2>
                    <div class="space-y-2">
                        <p><span class="font-medium">Date:</span> {{ $sale->SaleDate->format('F d, Y h:i A') }}</p>
                        <p><span class="font-medium">Customer:</span> {{ $sale->customer->CustomerCode ?? 'Walk-in Customer' }}</p>
                        <p><span class="font-medium">Payment Method:</span> {{ $sale->PaymentMethod }}</p>
                        <p><span class="font-medium">Status:</span> PAID</p>
                    </div>
                </div>
                <div>
                    <h2 class="text-lg font-semibold mb-4">Transaction Summary</h2>
                    <div class="space-y-2">
                        @php
                            $subtotal = $sale->salesItems->sum(function($item) { return $item->Quantity * $item->PriceAtSale; });
                            $vat = $subtotal * 0.12;
                            $discount = $sale->DiscountAmount;
                            $total = $subtotal + $vat - $discount;
                        @endphp
                        <p><span class="font-medium">Subtotal:</span> ₱{{ number_format($subtotal, 2) }}</p>
                        <p><span class="font-medium">VAT (12%):</span> ₱{{ number_format($vat, 2) }}</p>
                        <p><span class="font-medium">Discount:</span> ₱{{ number_format($discount, 2) }}</p>
                        <p class="text-lg font-bold"><span class="font-medium">Total Amount:</span> ₱{{ number_format($total, 2) }}</p>
                        <p><span class="font-medium">Amount Paid:</span> ₱{{ number_format($sale->AmountPaid, 2) }}</p>
                        <p><span class="font-medium">Change:</span> ₱{{ number_format($sale->AmountPaid - $total, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="mt-8">
                <h2 class="text-lg font-semibold mb-4">Items Purchased</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sub Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($sale->salesItems as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $item->product->ProductName }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $item->Quantity }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    ₱{{ number_format($item->PriceAtSale, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    ₱{{ number_format($item->Quantity * $item->PriceAtSale, 2) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="mt-8">
                <h2 class="text-lg font-semibold mb-4">Transaction Details</h2>
                <div class="space-y-2">
                    <p><span class="font-medium">Transaction ID:</span> {{ $sale->SaleID }}</p>
                    <p><span class="font-medium">Processed By:</span> {{ $sale->clerk->name ?? 'System' }}</p>
                    <p><span class="font-medium">Created At:</span> {{ $sale->created_at->format('F d, Y h:i A') }}</p>
                    @if($sale->updated_at != $sale->created_at)
                        <p><span class="font-medium">Last Updated:</span> {{ $sale->updated_at->format('F d, Y h:i A') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Print Styles -->
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .p-10, .p-10 * {
            visibility: visible;
        }
        .p-10 {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        button, a {
            display: none !important;
        }
    }
</style>
</x-header> 