<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sales Receipt</title>
    <link href="{{ public_path('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-white p-5">
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold mb-2">Changchang Store IMS/SMS</h1>
        <p class="text-gray-600">Philippines</p>
        <p class="text-gray-600">POD@gmail.com</p>
    </div>

    <div class="mb-6">
        <table class="w-full">
            <tr>
                <td class="w-1/2">
                    <p class="mb-1"><span class="font-semibold">Sales Receipt#:</span> SR-{{ str_pad($sale->SaleID, 5, '0', STR_PAD_LEFT) }}</p>
                    <p><span class="font-semibold">Bill To:</span> {{ $sale->customer->name ?? 'Walk-in Customer' }}</p>
                </td>
                <td class="w-1/2 text-right">
                    <p class="mb-1"><span class="font-semibold">Receipt Date:</span> {{ $sale->SaleDate->format('d M Y') }}</p>
                    <p><span class="font-semibold">Reference:</span> {{ str_pad($sale->SaleID, 3, '0', STR_PAD_LEFT) }}</p>
                </td>
            </tr>
        </table>
    </div>

    <table class="w-full border-collapse mb-6">
        <thead>
            <tr class="bg-gray-50">
                <th class="border border-gray-200 px-4 py-2 text-left font-semibold text-gray-600">Item</th>
                <th class="border border-gray-200 px-4 py-2 text-left font-semibold text-gray-600">Quantity</th>
                <th class="border border-gray-200 px-4 py-2 text-left font-semibold text-gray-600">Price</th>
                <th class="border border-gray-200 px-4 py-2 text-left font-semibold text-gray-600">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->salesItems as $item)
            <tr>
                <td class="border border-gray-200 px-4 py-2">{{ $item->product->name }}</td>
                <td class="border border-gray-200 px-4 py-2">{{ number_format($item->Quantity, 2) }}</td>
                <td class="border border-gray-200 px-4 py-2">Php {{ number_format($item->PriceAtSale, 2) }}</td>
                <td class="border border-gray-200 px-4 py-2">Php {{ number_format($item->Quantity * $item->PriceAtSale, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="ml-auto w-72">
        <table class="w-full">
            <tr>
                <td class="py-1"><span class="font-semibold">Sub Total:</span></td>
                <td class="py-1 text-right">Php {{ number_format($sale->TotalAmount - ($sale->TotalAmount * 0.12), 2) }}</td>
            </tr>
            <tr>
                <td class="py-1"><span class="font-semibold">VAT (12%):</span></td>
                <td class="py-1 text-right">Php {{ number_format($sale->TotalAmount * 0.12, 2) }}</td>
            </tr>
            <tr class="border-t border-gray-200">
                <td class="py-2"><span class="font-bold text-lg">Total:</span></td>
                <td class="py-2 text-right font-bold text-lg">Php {{ number_format($sale->TotalAmount, 2) }}</td>
            </tr>
            <tr>
                <td class="py-1"><span class="font-semibold">Amount Paid:</span></td>
                <td class="py-1 text-right">Php {{ number_format($sale->AmountPaid, 2) }}</td>
            </tr>
            <tr>
                <td class="py-1"><span class="font-semibold">Change:</span></td>
                <td class="py-1 text-right">Php {{ number_format($sale->AmountPaid - $sale->TotalAmount, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="mt-12 text-center text-sm text-gray-500">
        <p class="mb-1">Thank you for your business!</p>
        <p>This is a computer-generated receipt and does not require a signature.</p>
    </div>
</body>
</html> 