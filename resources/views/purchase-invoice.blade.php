<x-header>
  <div class="p-4 max-w-5xl mx-auto mt-10">
    <h2 class="text-2xl font-semibold mb-4">📄 Invoice Module</h2>

    <!-- Create Invoice -->
    <form id="invoiceForm" class="bg-white p-4 rounded-xl shadow space-y-4">
      <h3 class="text-xl font-semibold">Create Sales Invoice</h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <input type="text" id="customerName" placeholder="Customer Name" class="border p-2 rounded" required>
        <input type="text" id="itemDesc" placeholder="Item Description" class="border p-2 rounded" required>
        <input type="number" id="amount" placeholder="Amount (₱)" class="border p-2 rounded" required>
        <input type="number" id="discount" placeholder="Discount (%)" class="border p-2 rounded" value="0">
        <input type="number" id="tax" placeholder="VAT (%)" class="border p-2 rounded" value="12">
        <select id="paymentStatus" class="border p-2 rounded">
          <option>Unpaid</option>
          <option>Partially Paid</option>
          <option>Paid</option>
        </select>
      </div>
      <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
        Generate Invoice
      </button>
    </form>

    <!-- Invoice History -->
    <div class="mt-8 bg-white p-4 rounded-xl shadow">
      <h3 class="text-xl font-semibold mb-2">🗂 Invoice History</h3>
      <table class="w-full text-sm">
        <thead class="bg-gray-100">
          <tr>
            <th class="p-2 text-left">Customer</th>
            <th class="p-2 text-left">Item</th>
            <th class="p-2 text-left">Total (with VAT)</th>
            <th class="p-2 text-left">Status</th>
            <th class="p-2 text-left">Actions</th>
          </tr>
        </thead>
        <tbody id="invoiceList" class="divide-y"></tbody>
      </table>
    </div>
  </div>

  <script>
    const invoiceForm = document.getElementById("invoiceForm");
    const invoiceList = document.getElementById("invoiceList");

    invoiceForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const customer = document.getElementById("customerName").value;
      const item = document.getElementById("itemDesc").value;
      const amount = parseFloat(document.getElementById("amount").value);
      const discount = parseFloat(document.getElementById("discount").value || 0);
      const tax = parseFloat(document.getElementById("tax").value || 0);
      const status = document.getElementById("paymentStatus").value;

      const discountedAmount = amount - (amount * discount / 100);
      const taxAmount = discountedAmount * (tax / 100);
      const total = discountedAmount + taxAmount;

      const row = document.createElement("tr");
      row.innerHTML = `
        <td class="p-2">${customer}</td>
        <td class="p-2">${item}</td>
        <td class="p-2">₱${total.toFixed(2)}</td>
        <td class="p-2">${status}</td>
        <td class="p-2">
          <button onclick="window.print()" class="text-blue-600 hover:underline">🖨️ Print</button>
        </td>
      `;
      invoiceList.appendChild(row);

      invoiceForm.reset();
    });
  </script>
</x-header>
