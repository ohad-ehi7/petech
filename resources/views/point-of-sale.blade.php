<x-header>
  <body class="bg-gray-50 p-6">
    <div class="w-full mt-4 mb-10 mx-auto bg-white p-10 rounded shadow">
      <h1 class="text-2xl font-bold mb-4">Point of Sale</h1>

    <!-- Customer Info -->
    <div class="grid grid-cols-1 gap-4 mb-6">
      <div>
        <label class="block text-sm font-medium">Customer Name</label>
        <input type="text" class="mt-1 w-full border-gray-300 rounded-md shadow-sm p-2" value="Ken Savellino">
      </div>
      <div>
        <label class="block text-sm font-medium">Receipt Date</label>
        <input type="date" class="mt-1 w-full border-gray-300 rounded-md shadow-sm p-2" value="2025-04-25">
      </div>
      <div>
        <label class="block text-sm font-medium">Sales Receipt#</label>
        <input type="text" class="mt-1 w-full border-gray-300 rounded-md shadow-sm p-2" value="SR-00002">
      </div>
      <div>
        <label class="block text-sm font-medium">Salesperson</label>
        <select class="mt-1 w-full border-gray-300 rounded-md shadow-sm p-2">
          <option>Select or Add Salesperson</option>
        </select>
      </div>
    </div>

    <!-- Item Table -->
    <div class="overflow-auto mb-6 p-4">
      <table class="min-w-full text-sm text-left border">
        <thead class="bg-gray-100">
          <tr>
            <th class="p-2 border">Item Details</th>
            <th class="p-2 border">Quantity</th>
            <th class="p-2 border">Rate</th>
            <th class="p-2 border">Tax</th>
            <th class="p-2 border">Amount</th>
            <th class="p-2 border">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="p-2 border">
              <div class="font-medium">Bearbrand Milk</div>
              <div class="text-xs text-gray-500">SKU: P001</div>
            </td>
            <td class="p-2 border text-center">1.00</td>
            <td class="p-2 border">50.00</td>
            <td class="p-2 border">vat [12%]</td>
            <td class="p-2 border">50.00</td>
            <td class="p-2 border text-center text-red-500 cursor-pointer">&times;</td>
          </tr>
          <tr>
            <td class="p-2 border">
              <div class="font-medium">Nescafe Coffee</div>
              <div class="text-xs text-gray-500">SKU: P002</div>
            </td>
            <td class="p-2 border text-center">1.00</td>
            <td class="p-2 border">55.00</td>
            <td class="p-2 border">vat [12%]</td>
            <td class="p-2 border">55.00</td>
            <td class="p-2 border text-center text-red-500 cursor-pointer">&times;</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Totals -->
    <div class="grid grid-cols-2 gap-4 mb-6">
      <div class="space-y-2 p-4">
        <button class="bg-blue-500 text-white px-4 py-2 rounded">Add New Row</button>
        <button class="bg-blue-100 text-blue-700 px-4 py-2 rounded">Add Items in Bulk</button>
      </div>
      <div class="bg-gray-50 p-4 mr-4 rounded border">
        <div class="flex justify-between mb-2"><span>Sub Total</span><span>160.00</span></div>
        <div class="flex justify-between mb-2"><span>VAT [12%]</span><span>19.20</span></div>
        <div class="flex justify-between mb-2 font-semibold"><span>Total (PHP)</span><span>179.20</span></div>
      </div>
    </div>

    <!-- Notes -->
    <div class="mb-6">
      <label class="block text-sm font-medium p-2">Customer Notes</label>
      <textarea class="mt-1 w-full h-62 border-gray-300 rounded-md shadow-sm "></textarea>
    </div>

   <!-- Terms & Attach Files -->
   <div class="grid grid-cols-2 gap-6 text-sm mb-6">
      <!-- Terms -->
      <div>
          <h3 class="font-semibold mb-2">Terms & Conditions</h3>
          <textarea class="w-full p-2 border border-gray-300 rounded text-xs" rows="5" readonly>
By using this inventory system, you agree to follow all rules related to proper use. Only authorized users may access the system, and you are responsible for your own account. All information entered must be accurate and honest. Misuse of the system may lead to account suspension. User roles determine access levels, and these must not be bypassed. System updates may happen anytime without prior notice.
          </textarea>
        </div>

      <!-- File Upload -->
      <div>
          <h3 class="font-semibold mb-2">Attach File(s) to Sales Receipt</h3>
          <div class="space-y-2">
            <input type="file" id="fileUpload"
              class="text-sm file:py-1 file:px-3 file:rounded file:border-0 file:bg-gray-100 file:text-gray-700"
              multiple>
            <p class="text-sm text-gray-500">You can upload a maximum of 5 files, 10MB each</p>
          </div>
        </div>
      </div>


    <!-- Payment Details -->
    <div class="mb-6">
        <h3 class="font-semibold mb-4 text-sm">Payment Details</h3>
        <div class="grid grid-cols-3 gap-4 items-end">
          <div>
            <label class="block text-xs text-red-500 font-medium mb-1">Payment Mode*</label>
            <input type="text" class="w-full border border-gray-300 rounded px-2 py-1" value="Cash">
          </div>
          <div>
            <label class="block text-xs text-red-500 font-medium mb-1">Deposit To*</label>
            <input type="text" class="w-full border border-gray-300 rounded px-2 py-1" value="Petty Cash">
          </div>
          <div>
            <label class="block text-xs text-gray-700 font-medium mb-1">Reference#</label>
            <input type="text" class="w-full border border-gray-300 rounded px-2 py-1" value="001">
          </div>
        </div>
      </div>

    <!-- Action Buttons -->
     <div class="flex justify-start space-x-2 mt-4">
        <button class="bg-gray-100 border px-4 py-2 rounded text-sm">Save</button>
        <button class="bg-blue-600 text-white px-4 py-2 rounded text-sm">Save and Send</button>
        <button class="bg-gray-200 px-4 py-2 rounded text-sm">Cancel</button>
      </div>

    <!-- PDF Footer -->
    <div class="text-xs text-right text-gray-600 mt-4">
        PDF Template: <span class="text-blue-600">‘Elegant’</span>
        <a href="#" class="text-blue-600 underline">Change</a>
      </div>
    </div>
  </body>
</x-header>