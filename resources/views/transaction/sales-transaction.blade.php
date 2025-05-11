<x-header :title="'Sales Transactions'">
<div class="p-10">
<div class="w-full mx-auto">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Sales Receipts</h1>
      <div class="flex space-x-4">
        <button id="deleteSelected" class="hidden bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded">
          Delete Selected
        </button>
        <a href="{{ route('pos.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded">+ New Sale</a>
      </div>
    </div>

    <!-- Period Filter Dropdown -->
    <div class="relative inline-block text-left mb-4">
        <button id="periodDropdownButton" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <span class="font-bold">VIEW BY: </span> PERIOD: <span id="selectedPeriod" class="ml-1">This Month</span>
            <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.586l3.71-4.356a.75.75 0 011.14.976l-4.25 5a.75.75 0 01-1.14 0l-4.25-5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
            </svg>
        </button>
        <div id="periodDropdown" class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10">
            <div class="py-1">
                <a href="{{ request()->fullUrlWithQuery(['period' => 'today']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Today</a>
                <a href="{{ request()->fullUrlWithQuery(['period' => 'week']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">This Week</a>
                <a href="{{ request()->fullUrlWithQuery(['period' => 'month']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">This Month</a>
                <a href="{{ request()->fullUrlWithQuery(['period' => 'quarter']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">This Quarter</a>
                <a href="{{ request()->fullUrlWithQuery(['period' => 'year']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">This Year</a>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto bg-white rounded-lg shadow">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
              <input type="checkbox" id="selectAll" class="form-checkbox h-4 w-4 text-blue-600">
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Receipt Date</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sales Receipt #</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment Method</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created By</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          @foreach($sales as $sale)
          <tr>
            <td class="px-6 py-4 whitespace-nowrap">
              <input type="checkbox" class="sale-checkbox form-checkbox h-4 w-4 text-blue-600" value="{{ $sale->SaleID }}">
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span>{{ $sale->SaleDate->format('d M Y') }}</span>
            </td>
            <td class="px-6 py-4 text-blue-600 hover:underline cursor-pointer">
              <a href="{{ route('sales.show', $sale->SaleID) }}">SR-{{ str_pad($sale->SaleID, 5, '0', STR_PAD_LEFT) }}</a>
            </td>
            <td class="px-6 py-4">{{ $sale->customer->CustomerCode ?? 'Walk-in Customer' }}</td>
            <td class="px-6 py-4">₱{{ number_format($sale->TotalAmount, 2) }}</td>
            <td class="px-6 py-4">{{ $sale->PaymentMethod }}</td>
            <td class="px-6 py-4 text-green-600 font-semibold">PAID</td>
            <td class="px-6 py-4">{{ $sale->clerk->name ?? 'System' }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const periodDropdownButton = document.getElementById('periodDropdownButton');
      const periodDropdown = document.getElementById('periodDropdown');
      const selectedPeriod = document.getElementById('selectedPeriod');
      const selectAllCheckbox = document.getElementById('selectAll');
      const saleCheckboxes = document.querySelectorAll('.sale-checkbox');
      const deleteSelectedBtn = document.getElementById('deleteSelected');

      // Set initial selected period based on URL parameter
      const urlParams = new URLSearchParams(window.location.search);
      const period = urlParams.get('period');
      if (period) {
        const periodText = period.charAt(0).toUpperCase() + period.slice(1);
        selectedPeriod.textContent = periodText;
      }

      periodDropdownButton.addEventListener('click', function() {
        periodDropdown.classList.toggle('hidden');
      });

      // Close dropdown when clicking outside
      document.addEventListener('click', function(event) {
        if (!periodDropdownButton.contains(event.target) && !periodDropdown.contains(event.target)) {
          periodDropdown.classList.add('hidden');
        }
      });

      // Update selected period text when clicking on dropdown items
      periodDropdown.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', function(e) {
          const periodText = this.textContent;
          selectedPeriod.textContent = periodText;
        });
      });

      // Select all functionality
      if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
          saleCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
          });
          updateDeleteButton();
        });
      }

      // Individual checkbox functionality
      saleCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateDeleteButton);
      });

      // Update delete button visibility
      function updateDeleteButton() {
        if (deleteSelectedBtn) {
          const checkedBoxes = document.querySelectorAll('.sale-checkbox:checked');
          deleteSelectedBtn.classList.toggle('hidden', checkedBoxes.length === 0);
        }
      }

      // Delete selected sales
      if (deleteSelectedBtn) {
        deleteSelectedBtn.addEventListener('click', async function() {
          const checkedBoxes = document.querySelectorAll('.sale-checkbox:checked');
          const saleIds = Array.from(checkedBoxes).map(cb => cb.value);

          if (!confirm('Are you sure you want to delete the selected sales?')) {
            return;
          }

          try {
            const response = await fetch('/sales/bulk-delete', {
              method: 'DELETE',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
              },
              body: JSON.stringify({ saleIds })
            });

            const data = await response.json();
            console.log('Server response:', data);

            if (response.ok) {
              window.location.reload();
            } else {
              console.error('Server error response:', {
                status: response.status,
                statusText: response.statusText,
                data: data
              });
              alert(data.message || `Error deleting sales (${response.status}): ${response.statusText}`);
            }
          } catch (error) {
            console.error('Network or parsing error:', error);
            alert('Network error while deleting sales. Please check your connection and try again.');
          }
        });
      }
    });
  </script>

</x-header>
