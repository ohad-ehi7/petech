<x-header :title="'Point of Sale'">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="flex h-screen bg-gray-50">
        <!-- Left Section: Product Selection -->
        <div class="w-2/3 p-6 overflow-y-auto">
            <!-- Search and Filter Bar -->
            <div class="mb-6 flex items-center space-x-4">
                <div class="flex-1">
                    <div class="relative">
                        <input type="text" 
                               id="searchInput"
                               placeholder="Search products..." 
                               class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <i class="fa-solid fa-search absolute left-3 top-3 text-gray-400"></i>
      </div>
      </div>
                <select id="categoryFilter" class="px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->CategoryID }}">{{ $category->CategoryName }}</option>
                    @endforeach
        </select>
      </div>

            <!-- Product Grid -->
            <div id="productGrid" class="grid grid-cols-3 gap-4">
                @foreach($products as $product)
                <div class="product-card bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow p-4 cursor-pointer" 
                     data-product-id="{{ $product->ProductID }}"
                     data-product-name="{{ strtolower($product->ProductName) }}"
                     data-category-id="{{ $product->CategoryID }}"
                     onclick="showQuantityModal({{ $product->ProductID }}, '{{ $product->ProductName }}', {{ $product->SellingPrice }}, {{ $product->inventory->QuantityOnHand ?? 0 }})">
                    <div class="aspect-square mb-3 bg-gray-100 rounded-lg overflow-hidden">
                        @if($product->Product_Image)
                            <img src="{{ asset('storage/' . $product->Product_Image) }}" 
                                 alt="{{ $product->ProductName }}" 
                                 class="w-full h-full object-contain">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fa-solid fa-image text-gray-400 text-4xl"></i>
                            </div>
                        @endif
                    </div>
                    <h3 class="font-medium text-gray-900 truncate">{{ $product->ProductName }}</h3>
                    <p class="text-sm text-gray-500 mb-2">SKU: {{ $product->SKU }}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-semibold text-blue-600">₱{{ number_format($product->SellingPrice, 2) }}</span>
                        <span class="text-sm text-gray-500">Stock: {{ $product->inventory->QuantityOnHand ?? 0 }}</span>
    </div>
                </div>
                @endforeach
                </div>
              </div>

        <!-- Right Section: Cart and Checkout -->
        <div class="w-1/3 bg-white border-l border-gray-200 flex flex-col">
            <!-- Cart Header -->
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">Current Sale</h2>
                <div class="mt-2 flex items-center space-x-2">
                    <input type="text" 
                           id="customerName"
                           placeholder="Customer Name" 
                           class="flex-1 px-3 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <button onclick="generateCustomerName()" class="px-3 py-2 text-blue-600 hover:bg-blue-50 rounded-lg" title="Generate Customer Name">
                        <i class="fa-solid fa-user-plus"></i>
                    </button>
                </div>
            </div>

            <!-- Cart Items -->
            <div class="flex-1 overflow-y-auto p-6">
                <div id="cart-items" class="space-y-4">
                    <!-- Cart items will be dynamically added here -->
                </div>
    </div>

            <!-- Cart Summary -->
            <div class="border-t border-gray-200 p-6 bg-gray-50">
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium" id="subtotal">₱0.00</span>
      </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">VAT (12%)</span>
                        <span class="font-medium" id="vat">₱0.00</span>
        </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Discount</span>
          <div class="flex items-center space-x-2">
                            <input type="number" 
                                   class="w-20 px-2 py-1 rounded border border-gray-300 text-right" 
                                   value="0" 
                                   min="0" 
                                   step="0.01">
                            <select class="px-2 py-1 rounded border border-gray-300">
                                <option value="%">%</option>
                                <option value="PHP">₱</option>
            </select>
          </div>
        </div>
                    <div class="border-t border-gray-200 pt-3">
        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold">Total</span>
                            <span class="text-2xl font-bold text-blue-600" id="total">₱0.00</span>
        </div>
        </div>
        </div>

                <!-- Payment Buttons -->
                <div class="mt-6 space-y-3">
                    <button onclick="processPayment()" class="w-full bg-blue-600 text-white py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                        <i class="fa-solid fa-cash-register mr-2"></i>Process Payment
                    </button>
                    <button onclick="printReceipt()" class="w-full bg-gray-100 text-gray-700 py-3 rounded-lg font-medium hover:bg-gray-200 transition-colors" disabled>
                        <i class="fa-solid fa-print mr-2"></i>Print Receipt
                    </button>
        </div>
      </div>
    </div>
    </div>

    <!-- Quantity Selection Modal -->
    <div id="quantityModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-96 shadow-2xl transform transition-all">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900" id="modalProductName"></h3>
                <button onclick="closeQuantityModal()" class="text-gray-400 hover:text-gray-500 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
        </div>

            <div class="mb-4">
                <div class="flex justify-between text-sm text-gray-600 mb-2">
                    <span>Price:</span>
                    <span id="modalProductPrice"></span>
                </div>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Available Stock:</span>
                    <span id="modalProductStock"></span>
          </div>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>In Cart:</span>
                    <span id="modalProductInCart">0</span>
        </div>
      </div>

    <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                <div class="flex items-center space-x-3">
                    <button onclick="decrementQuantity()" class="w-10 h-10 rounded-lg border border-gray-300 flex items-center justify-center hover:bg-gray-50">
                        <i class="fa-solid fa-minus"></i>
                    </button>
                    <input type="number" 
                           id="quantityInput" 
                           class="w-20 text-center border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                           value="1" 
                           min="1">
                    <button onclick="incrementQuantity()" class="w-10 h-10 rounded-lg border border-gray-300 flex items-center justify-center hover:bg-gray-50">
                        <i class="fa-solid fa-plus"></i>
                    </button>
          </div>
          </div>

            <div class="flex justify-end space-x-3">
                <button onclick="closeQuantityModal()" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                    Cancel
                </button>
                <button onclick="addToCart()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Add to Cart
                </button>
          </div>
        </div>
      </div>

    <!-- Cash Payment Modal -->
    <div id="cashPaymentModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-96 shadow-2xl transform transition-all">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Cash Payment</h3>
                <button onclick="closeCashPaymentModal()" class="text-gray-400 hover:text-gray-500 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
      </div>

            <div class="space-y-4">
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Total Amount:</span>
                    <span id="paymentTotal" class="font-semibold"></span>
                </div>
                
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Amount Received</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">₱</span>
                        <input type="number" 
                               id="amountReceived" 
                               class="w-full pl-8 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="0.00"
                               step="0.01"
                               min="0">
    </div>
                </div>

                <div class="flex justify-between text-sm text-gray-600">
                    <span>Change:</span>
                    <span id="changeAmount" class="font-semibold">₱0.00</span>
              </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button onclick="closeCashPaymentModal()" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                    Cancel
                </button>
                <button onclick="completeCashPayment()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Complete Payment
                </button>
            </div>
          </div>
          </div>

    <script>
        let cart = [];
        let currentProduct = null;
        let maxStock = 0;
        let originalStock = 0;

        function showQuantityModal(productId, productName, price, stock) {
            currentProduct = {
                id: productId,
                name: productName,
                price: price
            };
            
            // Calculate available stock by subtracting items in cart
            const itemsInCart = cart.filter(item => item.id === productId)
                                  .reduce((sum, item) => sum + item.quantity, 0);
            originalStock = stock;
            maxStock = stock - itemsInCart;

            document.getElementById('modalProductName').textContent = productName;
            document.getElementById('modalProductPrice').textContent = `₱${price.toFixed(2)}`;
            document.getElementById('modalProductStock').textContent = maxStock;
            document.getElementById('modalProductInCart').textContent = itemsInCart;
            document.getElementById('quantityInput').value = 1;
            
            const modal = document.getElementById('quantityModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        }

        function closeQuantityModal() {
            const modal = document.getElementById('quantityModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = ''; // Restore scrolling
            currentProduct = null;
        }

        function incrementQuantity() {
            const input = document.getElementById('quantityInput');
            const newValue = parseInt(input.value) + 1;
            if (newValue <= maxStock) {
                input.value = newValue;
            }
        }

        function decrementQuantity() {
            const input = document.getElementById('quantityInput');
            const newValue = parseInt(input.value) - 1;
            if (newValue >= 1) {
                input.value = newValue;
            }
        }

        function addToCart() {
            if (!currentProduct) return;

            const quantity = parseInt(document.getElementById('quantityInput').value);
            if (quantity < 1 || quantity > maxStock) return;

            // Add to cart array
            cart.push({
                ...currentProduct,
                quantity: quantity
            });

            // Update cart display
            updateCart();
            
            // Close modal
            closeQuantityModal();
        }

        function updateCart() {
            const cartItems = document.getElementById('cart-items');
            cartItems.innerHTML = '';

            // Group items by product ID
            const groupedItems = cart.reduce((groups, item) => {
                if (!groups[item.id]) {
                    groups[item.id] = {
                        ...item,
                        totalQuantity: 0
                    };
                }
                groups[item.id].totalQuantity += item.quantity;
                return groups;
            }, {});

            // Display grouped items
            Object.values(groupedItems).forEach((item, index) => {
                const itemElement = document.createElement('div');
                itemElement.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg';
                itemElement.innerHTML = `
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900">${item.name}</h4>
                        <div class="text-sm text-gray-500">
                            ${item.totalQuantity} × ₱${item.price.toFixed(2)}
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="font-medium text-gray-900">₱${(item.totalQuantity * item.price).toFixed(2)}</span>
                        <button onclick="removeFromCart(${item.id})" class="text-red-500 hover:text-red-600">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                `;
                cartItems.appendChild(itemElement);
            });

      updateTotals();
    }

        function removeFromCart(productId) {
            // Remove all instances of this product from cart
            cart = cart.filter(item => item.id !== productId);
            updateCart();
        }

        function updateTotals() {
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const vat = subtotal * 0.12;
            const discount = calculateDiscount();
            const total = subtotal + vat - discount;

            document.getElementById('subtotal').textContent = `₱${subtotal.toFixed(2)}`;
            document.getElementById('vat').textContent = `₱${vat.toFixed(2)}`;
            document.getElementById('total').textContent = `₱${total.toFixed(2)}`;
        }

        function calculateDiscount() {
            const discountInput = document.querySelector('input[type="number"]');
            const discountType = document.querySelector('select');
            const discountValue = parseFloat(discountInput.value) || 0;
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            
            if (discountType.value === '%') {
                return (subtotal * discountValue / 100);
            }
            return discountValue;
        }

        // Close modal when clicking outside
        document.getElementById('quantityModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeQuantityModal();
            }
        });

        // Handle quantity input validation
        document.getElementById('quantityInput').addEventListener('input', function(e) {
            let value = parseInt(e.target.value);
            if (isNaN(value) || value < 1) {
                e.target.value = 1;
            } else if (value > maxStock) {
                e.target.value = maxStock;
            }
        });

        // Search and Filter Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const categoryFilter = document.getElementById('categoryFilter');
            const productCards = document.querySelectorAll('.product-card');

            function filterProducts() {
                const searchTerm = searchInput.value.toLowerCase();
                const selectedCategory = categoryFilter.value;

                productCards.forEach(card => {
                    const productName = card.dataset.productName;
                    const categoryId = card.dataset.categoryId;
                    
                    const matchesSearch = productName.includes(searchTerm);
                    const matchesCategory = !selectedCategory || categoryId === selectedCategory;

                    if (matchesSearch && matchesCategory) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }

            // Add event listeners for search and filter
            searchInput.addEventListener('input', filterProducts);
            categoryFilter.addEventListener('change', filterProducts);

            // Initial filter
            filterProducts();
        });

        // Customer Name Generation
        function generateCustomerName() {
            const timestamp = new Date().getTime();
            const randomNum = Math.floor(Math.random() * 1000);
            const customerName = `Customer-${timestamp}-${randomNum}`;
            document.getElementById('customerName').value = customerName;
        }

        // Payment Processing
        function processPayment() {
            const customerName = document.getElementById('customerName').value.trim();
            if (!customerName) {
                alert('Please enter or generate a customer name');
                return;
            }

            if (cart.length === 0) {
                alert('Cart is empty');
                return;
            }

            // Calculate total amount
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const vat = subtotal * 0.12;
            const total = subtotal + vat;

            // Show cash payment modal
            document.getElementById('paymentTotal').textContent = `₱${total.toFixed(2)}`;
            document.getElementById('amountReceived').value = '';
            document.getElementById('changeAmount').textContent = '₱0.00';
            
            const modal = document.getElementById('cashPaymentModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeCashPaymentModal() {
            const modal = document.getElementById('cashPaymentModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        // Handle amount received input
        document.getElementById('amountReceived').addEventListener('input', function(e) {
            const total = parseFloat(document.getElementById('paymentTotal').textContent.replace('₱', ''));
            const received = parseFloat(e.target.value) || 0;
            const change = received - total;
            
            document.getElementById('changeAmount').textContent = `₱${change >= 0 ? change.toFixed(2) : '0.00'}`;
        });

        function updateStockDisplay(productId, newStock) {
            // Update the stock display in the product card
            const productCard = document.querySelector(`.product-card[data-product-id="${productId}"]`);
            if (productCard) {
                const stockDisplay = productCard.querySelector('.text-sm.text-gray-500:last-child');
                if (stockDisplay) {
                    stockDisplay.textContent = `Stock: ${newStock}`;
                }
            }
        }

        function refreshAllStock() {
            const productCards = document.querySelectorAll('.product-card');
            productCards.forEach(card => {
                const productId = card.dataset.productId;
                fetch(`/api/products/${productId}/stock`)
                    .then(response => response.json())
                    .then(stockData => {
                        updateStockDisplay(productId, stockData.quantity);
                    })
                    .catch(error => console.error('Error updating stock:', error));
            });
        }

        function completeCashPayment() {
            const total = parseFloat(document.getElementById('paymentTotal').textContent.replace('₱', ''));
            const received = parseFloat(document.getElementById('amountReceived').value) || 0;
            
            if (received < total) {
                alert('Amount received is less than the total amount');
                return;
            }

            // Prepare sale data
            const saleData = {
                customer_name: document.getElementById('customerName').value.trim(),
                total_amount: total,
                discount_amount: calculateDiscount(),
                items: cart.map(item => ({
                    product_id: item.id,
                    quantity: item.quantity,
                    price: item.price
                }))
            };

            // Show loading state
            const completeButton = document.querySelector('button[onclick="completeCashPayment()"]');
            const originalText = completeButton.innerHTML;
            completeButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processing...';
            completeButton.disabled = true;

            // Send to server
            fetch('/sales/process', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(saleData)
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) {
                    // Handle validation errors
                    if (response.status === 422 && data.errors) {
                        const errorMessages = Object.values(data.errors).flat();
                        throw new Error(errorMessages.join('\n'));
                    }
                    throw new Error(data.message || 'Error processing sale');
                }
                return data;
            })
            .then(data => {
                if (data.success) {
                    // Enable receipt printing and close modal
                    document.querySelector('button[onclick="printReceipt()"]').disabled = false;
                    closeCashPaymentModal();
                    
                    // Clear the cart
                    cart = [];
                    updateCart();

                    // Show success message
                    alert('Payment processed successfully!');
                    
                    // Refresh the page to update stock
                    window.location.reload();
                } else {
                    throw new Error(data.message || 'Error processing sale');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error processing sale: ' + error.message);
            })
            .finally(() => {
                // Reset button state
                completeButton.innerHTML = originalText;
                completeButton.disabled = false;
            });
        }

        // Update receipt printing to include sale ID
        function printReceipt() {
            const customerName = document.getElementById('customerName').value;
            const date = new Date().toLocaleString();
            const receiptNumber = `REC-${Date.now()}`;
            
            // Create receipt content
            const receiptContent = `
                <div style="font-family: Arial, sans-serif; max-width: 300px; margin: 0 auto; padding: 20px;">
                    <h2 style="text-align: center; margin-bottom: 20px;">Sales Receipt</h2>
                    <div style="margin-bottom: 10px;">
                        <strong>Receipt #:</strong> ${receiptNumber}
                    </div>
                    <div style="margin-bottom: 10px;">
                        <strong>Date:</strong> ${date}
                    </div>
                    <div style="margin-bottom: 10px;">
                        <strong>Customer:</strong> ${customerName}
                    </div>
                    <hr style="margin: 15px 0;">
                    <div style="margin-bottom: 10px;">
                        ${cart.map(item => `
                            <div style="margin-bottom: 5px;">
                                ${item.name} x ${item.quantity} = ₱${(item.price * item.quantity).toFixed(2)}
                            </div>
                        `).join('')}
                    </div>
                    <hr style="margin: 15px 0;">
                    <div style="text-align: right;">
                        <div style="margin-bottom: 5px;">
                            <strong>Subtotal:</strong> ₱${cart.reduce((sum, item) => sum + (item.price * item.quantity), 0).toFixed(2)}
                        </div>
                        <div style="margin-bottom: 5px;">
                            <strong>VAT (12%):</strong> ₱${(cart.reduce((sum, item) => sum + (item.price * item.quantity), 0) * 0.12).toFixed(2)}
                        </div>
                        <div style="font-size: 1.2em; font-weight: bold;">
                            <strong>Total:</strong> ₱${(cart.reduce((sum, item) => sum + (item.price * item.quantity), 0) * 1.12).toFixed(2)}
                        </div>
                    </div>
                    <div style="text-align: center; margin-top: 20px;">
                        Thank you for your purchase!
                    </div>
                </div>
            `;

            // Create a new window for printing
            const printWindow = window.open('', '_blank');
            printWindow.document.write(receiptContent);
            printWindow.document.close();
            printWindow.print();
        }
  </script>
</x-header>