<x-header>
  <body class="bg-gray-50 p-6">
    <div class="w-full mt-4 mx-auto bg-white p-12 rounded">
      <h1 class="text-2xl font-bold mb-4">Point of Sale</h1>

      <!-- Filter Dropdown -->
     

    <!-- Customer Info -->
    <div class="grid grid-cols-1 gap-4 mb-6">
      <div>
        <label class="block text-sm font-medium">Customer Name</label>
        <input type="text" class="mt-1 w-[900px] border-gray-300 rounded-md shadow-sm p-2" value="Ken Savellino">
      </div>
      <div>
        <label class="block text-sm font-medium">Receipt Date</label>
        <input type="date" class="mt-1 w-[900px] border-gray-300 rounded-md shadow-sm p-2" value="2025-04-25">
      </div>
      <div>
        <label class="block text-sm font-medium">Sales Receipt#</label>
        <input type="text" class="mt-1 w-[900px] border-gray-300 rounded-md shadow-sm p-2" value="SR-00002">
      </div>
      <div>
        <label class="block text-sm font-medium">Salesperson</label>
        <select class="mt-1 w-[900px] border-gray-300 rounded-md shadow-sm p-2">
          <option>Select or Add Salesperson</option>
        </select>
      </div>
    </div>

    <!-- Item Table -->
    <div class="overflow-auto mb-6 p-4">
      <table class="min-w-full text-sm text-left border" id="itemTable">
        <h4 class="bg-gray-100 w-full p-4 font-bold rounded-l">Item Table</h4>
        <thead class="bg-white">
          <tr>
            <th class="p-2 border">Item Details</th>
            <th class="p-2 border">Quantity</th>
            <th class="p-2 border">Rate</th>
            <th class="p-2 border">Tax</th>
            <th class="p-2 border">Amount</th>
            <th class="p-2 border">Actions</th>
          </tr>
        </thead>
        <tbody id="itemTableBody">
          <tr>
            <td class="p-2 border">
              <div class="flex items-center space-x-4">
                <div class="w-16 h-16 overflow-hidden">
                  <img src="https://www.nestle.com.ph/sites/g/files/pydnoa336/files/styles/scale_992/public/2022-03/Bear-Brand-Sterilized-Milk-110ml.png" alt="Bearbrand Milk" class="w-full h-full object-contain">
                </div>
                <div class="flex-1">
                  <div class="font-medium">Bearbrand Milk</div>
                  <div class="text-xs text-gray-500">SKU: P001</div>
                </div>
              </div>
            </td>
            <td class="p-2 border text-center">1.00</td>
            <td class="p-2 border">50.00</td>
            <td class="p-2 border">vat [12%]</td>
            <td class="p-2 border">50.00</td>
            <td class="p-2 border text-center text-red-500 cursor-pointer" onclick="deleteRow(this)">&times;</td>
          </tr>
          <tr>
            <td class="p-2 border">
              <div class="flex items-center space-x-4">
                <div class="w-16 h-16 overflow-hidden">
                  <img src="https://www.nescafe.com/gb/sites/default/files/2020-07/nescafe-original-jar.png" alt="Nescafe Coffee" class="w-full h-full object-contain">
                </div>
                <div class="flex-1">
                  <div class="font-medium">Nescafe Coffee</div>
                  <div class="text-xs text-gray-500">SKU: P002</div>
                </div>
              </div>
            </td>
            <td class="p-2 border text-center">1.00</td>
            <td class="p-2 border">55.00</td>
            <td class="p-2 border">vat [12%]</td>
            <td class="p-2 border">55.00</td>
            <td class="p-2 border text-center text-red-500 cursor-pointer" onclick="deleteRow(this)">&times;</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Totals -->
    <div class="grid grid-cols-2 gap-4 mb-6">
      <div class="space-y-2 p-4">
        <button class="bg-blue-500 text-white px-4 py-2 rounded" onclick="addNewRow()"><i class="fa-solid fa-plus mr-2"></i>Add New Row</button>
        <button class="bg-blue-100 text-blue-700 px-4 py-2 rounded"><i class="fa-solid fa-plus mr-2 "></i>Add Items in Bulk</button>
      </div>
      <div class="bg-gray-50 p-6 rounded ml-32 w-full max-w-md space-y-4">
        <!-- Sub Total -->
        <div class="flex justify-between items-center">
          <span class="font-medium text-gray-700">Sub Total</span>
          <span class="text-gray-900 font-medium">160.00</span>
        </div>

        <!-- Discount -->
        <div class="flex justify-between items-center">
          <span class="text-gray-700">Discount</span>
          <div class="flex items-center space-x-2">
            <input type="number" value="0" class="w-16 px-2 py-1 border border-gray-300 rounded text-right" />
            <select class="border border-gray-300 rounded px-1 py-1">
              <option>%</option>
              <option>PHP</option>
            </select>
          </div>
          <span class="w-14 text-right">0.00</span>
        </div>

        <!-- Shipping Charges -->
        <div class="flex justify-between items-center">
          <span class="text-gray-700 flex items-center">
            Shipping Charges
            <span class="ml-1 text-gray-400 cursor-help" title="Enter delivery cost">
              <i class="fa-solid fa-circle-question"></i>
            </span>
          </span>
          <input type="number" class="border border-gray-300 rounded px-2 py-1 w-24 text-right" />
          <span class="w-14 text-right">0.00</span>
        </div>

        <!-- VAT -->
        <div class="flex justify-between items-center">
          <span class="text-gray-700">vat [12%]</span>
          <span class="w-14 text-right">19.20</span>
        </div>

        <!-- Adjustment -->
        <div class="flex justify-between items-center">
          <span class="text-gray-700 flex items-center">
            <span class="border border-dashed border-gray-400 rounded px-2 py-1 mr-2">Adjustment</span>
            <span class="text-gray-400 cursor-help" title="Optional additional adjustment">
              <i class="fa-solid fa-circle-question"></i>
            </span>
          </span>
          <input type="number" class="border border-gray-300 rounded px-2 py-1 w-24 text-right" />
          <span class="w-14 text-right">0.00</span>
        </div>

        <!-- Total -->
        <div class="border-t pt-4 flex justify-between items-center font-bold text-lg text-gray-800">
          <span>Total ( PHP )</span>
          <span>179.20</span>
        </div>
      </div>
    </div>

    <!-- Notes -->
    <div class="mb-6">
      <label class="block text-sm font-medium p-2">Customer Notes</label>
      <textarea class="mt-1 w-full h-32 border-gray-300 rounded-md shadow-lg "></textarea>
    </div>

   <!-- Terms & Attach Files -->
   <div class="grid grid-cols-2 gap-6 text-sm mb-6">
      <!-- Terms -->
      <div>
          <h3 class="font-semibold text-xl mb-2">Terms & Conditions</h3>
          <textarea class="w-full p-2 border border-gray-300 rounded text-md" rows="5" readonly>
By using this inventory system, you agree to follow all rules related to proper use. Only authorized users may access the system, and you are responsible for your own account. All information entered must be accurate and honest. Misuse of the system may lead to account suspension. User roles determine access levels, and these must not be bypassed. System updates may happen anytime without prior notice.
          </textarea>
        </div>

      <!-- File Upload -->
      <div class="flex flex-col ml-4">
          <h3 class="font-semibold mb-2 p-2">Attach File(s) to Sales Receipt</h3>
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

    
    </div>
  </body>

  <script>
    // Add these new functions for the filter dropdown
    document.addEventListener('DOMContentLoaded', function() {
      const filterToggle = document.getElementById('filterToggle');
      const dropdownPeriod = document.getElementById('dropdownPeriod');

      // Toggle dropdown
      filterToggle.addEventListener('click', function() {
        dropdownPeriod.classList.toggle('hidden');
      });

      // Close dropdown when clicking outside
      document.addEventListener('click', function(event) {
        if (!filterToggle.contains(event.target) && !dropdownPeriod.contains(event.target)) {
          dropdownPeriod.classList.add('hidden');
        }
      });
    });

    function selectPeriod(period) {
      document.getElementById('selectedPeriod').textContent = period;
      document.getElementById('dropdownPeriod').classList.add('hidden');
      // Add your filter logic here
    }

    function addNewRow() {
      const tbody = document.getElementById('itemTableBody');
      const newRow = document.createElement('tr');
      
      newRow.innerHTML = `
        <td class="p-2 border">
          <div class="editing-mode">
            <div class="flex items-center space-x-4">
              <div class="w-16 h-16 overflow-hidden relative">
                <input type="file" class="hidden" accept="image/*" onchange="previewImage(this)">
                <img src="" alt="" class="w-full h-full object-contain hidden item-image-preview">
                <div class="w-full h-full flex items-center justify-center bg-gray-100 cursor-pointer image-upload-placeholder" onclick="document.querySelector('input[type=file]').click()">
                  <i class="fa-solid fa-image text-gray-400"></i>
                </div>
              </div>
              <div class="flex-1">
                <input type="text" class="w-full p-1 border rounded" placeholder="Item Name">
                <input type="text" class="w-full p-1 border rounded mt-1" placeholder="SKU">
              </div>
            </div>
          </div>
          <div class="saved-mode hidden">
            <div class="flex items-center space-x-4">
              <div class="w-16 h-16 overflow-hidden">
                <img src="" alt="" class="w-full h-full object-contain saved-item-image">
              </div>
              <div class="flex-1">
                <div class="font-medium item-name"></div>
                <div class="text-xs text-gray-500 sku"></div>
              </div>
            </div>
          </div>
        </td>
        <td class="p-2 border text-center">
          <div class="editing-mode">
            <input type="number" class="w-full p-1 border rounded text-center quantity-input" value="1.00" min="0" step="0.01" onchange="calculateAmount(this)">
          </div>
          <div class="saved-mode hidden text-center quantity"></div>
        </td>
        <td class="p-2 border">
          <div class="editing-mode">
            <input type="number" class="w-full p-1 border rounded rate-input" value="0.00" min="0" step="0.01" onchange="calculateAmount(this)">
          </div>
          <div class="saved-mode hidden rate"></div>
        </td>
        <td class="p-2 border">
          <div class="editing-mode">
            <select class="w-full p-1 border rounded" onchange="calculateAmount(this)">
              <option>vat [12%]</option>
              <option>No Tax</option>
            </select>
          </div>
          <div class="saved-mode hidden tax"></div>
        </td>
        <td class="p-2 border">
          <div class="editing-mode">
            <input type="number" class="w-full p-1 border rounded amount-input" value="0.00" readonly>
          </div>
          <div class="saved-mode hidden amount"></div>
        </td>
        <td class="p-2 border text-center">
          <div class="editing-mode">
            <button class="bg-green-500 text-white px-2 py-1 rounded text-sm mr-1" onclick="saveRow(this)">Save</button>
            <span class="text-red-500 cursor-pointer" onclick="deleteRow(this)">&times;</span>
          </div>
          <div class="saved-mode hidden">
            <span class="text-red-500 cursor-pointer" onclick="deleteRow(this)">&times;</span>
          </div>
        </td>
      `;

      tbody.appendChild(newRow);
    }

    function calculateAmount(element) {
      const row = element.closest('tr');
      const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
      const rate = parseFloat(row.querySelector('.rate-input').value) || 0;
      const amount = quantity * rate;
      
      row.querySelector('.amount-input').value = amount.toFixed(2);
      updateTotals();
    }

    function previewImage(input) {
      if (input.files && input.files[0]) {
        const reader = new FileReader();
        const row = input.closest('tr');
        const preview = row.querySelector('.item-image-preview');
        const placeholder = row.querySelector('.image-upload-placeholder');
        
        reader.onload = function(e) {
          preview.src = e.target.result;
          preview.classList.remove('hidden');
          placeholder.classList.add('hidden');
        }
        
        reader.readAsDataURL(input.files[0]);
      }
    }

    function saveRow(button) {
      const row = button.closest('tr');
      const editingMode = row.querySelectorAll('.editing-mode');
      const savedMode = row.querySelectorAll('.saved-mode');
      
      // Get values from inputs
      const itemName = row.querySelector('input[placeholder="Item Name"]').value;
      const sku = row.querySelector('input[placeholder="SKU"]').value;
      const quantity = row.querySelector('.quantity-input').value;
      const rate = row.querySelector('.rate-input').value;
      const tax = row.querySelector('select').value;
      const amount = row.querySelector('.amount-input').value;
      const imagePreview = row.querySelector('.item-image-preview');
      const savedImage = row.querySelector('.saved-item-image');

      // Update saved mode divs
      row.querySelector('.item-name').textContent = itemName;
      row.querySelector('.sku').textContent = `SKU: ${sku}`;
      row.querySelector('.quantity').textContent = quantity;
      row.querySelector('.rate').textContent = rate;
      row.querySelector('.tax').textContent = tax;
      row.querySelector('.amount').textContent = amount;
      
      // Update saved image
      if (imagePreview.src) {
        savedImage.src = imagePreview.src;
      }

      // Hide editing mode, show saved mode
      editingMode.forEach(el => el.classList.add('hidden'));
      savedMode.forEach(el => el.classList.remove('hidden'));

      // Force update totals after saving
      setTimeout(updateTotals, 0);
    }

    function deleteRow(button) {
      const row = button.closest('tr');
      row.remove();
      updateTotals();
    }

    function updateTotals() {
      const rows = document.querySelectorAll('#itemTableBody tr');
      let subtotal = 0;
      let totalVat = 0;

      rows.forEach(row => {
        // Get amount from either editing mode or saved mode
        let amount = 0;
        if (row.querySelector('.amount-input')) {
          // For rows in editing mode
          amount = parseFloat(row.querySelector('.amount-input').value) || 0;
        } else {
          // For saved rows
          const amountText = row.querySelector('.amount')?.textContent;
          amount = parseFloat(amountText) || 0;
        }

        // Get tax type
        let taxType = '';
        if (row.querySelector('select')) {
          // For rows in editing mode
          taxType = row.querySelector('select').value;
        } else {
          // For saved rows
          const taxText = row.querySelector('.tax')?.textContent;
          taxType = taxText || '';
        }

        subtotal += amount;

        // Calculate VAT if applicable
        if (taxType.includes('vat')) {
          const vatAmount = amount * 0.12;
          totalVat += vatAmount;
        }
      });

      console.log('Subtotal:', subtotal); // Debug log
      console.log('VAT:', totalVat); // Debug log

      // Update Sub Total
      const subTotalElement = document.querySelector('.bg-gray-50.p-6.rounded.ml-32.w-full.max-w-md.space-y-4 div:first-child span:last-child');
      if (subTotalElement) {
        subTotalElement.textContent = subtotal.toFixed(2);
      }

      // Update VAT
      const vatElement = document.querySelector('.bg-gray-50.p-6.rounded.ml-32.w-full.max-w-md.space-y-4 div:nth-child(4) span:last-child');
      if (vatElement) {
        vatElement.textContent = totalVat.toFixed(2);
      }

      // Get discount value
      const discountInput = document.querySelector('.bg-gray-50.p-6.rounded.ml-32.w-full.max-w-md.space-y-4 div:nth-child(2) input[type="number"]');
      const discountType = document.querySelector('.bg-gray-50.p-6.rounded.ml-32.w-full.max-w-md.space-y-4 div:nth-child(2) select');
      let discount = 0;
      if (discountInput && discountType) {
        const discountValue = parseFloat(discountInput.value) || 0;
        discount = discountType.value === '%' ? (subtotal * discountValue / 100) : discountValue;
      }

      // Get shipping value
      const shippingInput = document.querySelector('.bg-gray-50.p-6.rounded.ml-32.w-full.max-w-md.space-y-4 div:nth-child(3) input[type="number"]');
      const shipping = parseFloat(shippingInput?.value) || 0;

      // Get adjustment value
      const adjustmentInput = document.querySelector('.bg-gray-50.p-6.rounded.ml-32.w-full.max-w-md.space-y-4 div:nth-child(5) input[type="number"]');
      const adjustment = parseFloat(adjustmentInput?.value) || 0;

      // Calculate final total
      const finalTotal = subtotal + totalVat - discount + shipping + adjustment;

      console.log('Final Total:', finalTotal); // Debug log

      // Update Total
      const totalElement = document.querySelector('.border-t.pt-4.flex.justify-between.items-center.font-bold.text-lg.text-gray-800 span:last-child');
      if (totalElement) {
        totalElement.textContent = finalTotal.toFixed(2);
      }

      // Update discount display
      const discountDisplay = document.querySelector('.bg-gray-50.p-6.rounded.ml-32.w-full.max-w-md.space-y-4 div:nth-child(2) span:last-child');
      if (discountDisplay) {
        discountDisplay.textContent = discount.toFixed(2);
      }

      // Update shipping display
      const shippingDisplay = document.querySelector('.bg-gray-50.p-6.rounded.ml-32.w-full.max-w-md.space-y-4 div:nth-child(3) span:last-child');
      if (shippingDisplay) {
        shippingDisplay.textContent = shipping.toFixed(2);
      }

      // Update adjustment display
      const adjustmentDisplay = document.querySelector('.bg-gray-50.p-6.rounded.ml-32.w-full.max-w-md.space-y-4 div:nth-child(5) span:last-child');
      if (adjustmentDisplay) {
        adjustmentDisplay.textContent = adjustment.toFixed(2);
      }
    }

    // Add event listeners for all inputs that affect totals
    document.addEventListener('DOMContentLoaded', function() {
      // Listen for changes in discount, shipping, and adjustment inputs
      const totalInputs = document.querySelectorAll('.bg-gray-50.p-6.rounded.ml-32.w-full.max-w-md.space-y-4 input[type="number"]');
      totalInputs.forEach(input => {
        input.addEventListener('input', updateTotals);
      });

      // Listen for changes in discount type
      const discountType = document.querySelector('.bg-gray-50.p-6.rounded.ml-32.w-full.max-w-md.space-y-4 div:nth-child(2) select');
      if (discountType) {
        discountType.addEventListener('change', updateTotals);
      }

      // Initial calculation
      updateTotals();
    });
  </script>
</x-header>