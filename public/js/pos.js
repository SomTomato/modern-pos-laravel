document.addEventListener("DOMContentLoaded", function() {
    // --- ELEMENT DECLARATIONS ---
    const productList = document.getElementById('product-list');
    const cartItemsEl = document.getElementById('cart-items');
    const cartTotalEl = document.getElementById('cart-total');
    const completeSaleBtn = document.getElementById('complete-sale-btn');
    const productSearch = document.getElementById('product-search');
    const customerSearchInput = document.getElementById('customer-search');
    const customerResultsDiv = document.getElementById('customer-results');
    const selectedCustomerNameEl = document.getElementById('customer-name');
    
    // Modal Elements
    const confirmationModal = document.getElementById('confirmation-modal');
    if (!confirmationModal) return; // Stop if modal is not on the page
    const modalCloseBtn = confirmationModal.querySelector('.close-modal');
    const modalCartSummary = document.getElementById('modal-cart-summary');
    const modalGrandTotal = document.getElementById('modal-grand-total');
    const paymentMethodBtns = confirmationModal.querySelectorAll('.payment-method-btn');
    const bankSelectionDiv = document.getElementById('bank-selection');
    const bankSelect = document.getElementById('payment-provider');
    const confirmPayBtn = document.getElementById('confirm-pay-btn');
    const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');


    // --- STATE VARIABLES ---
    let cart = [];
    let selectedCustomerId = 1; // Default to 'General Customer'
    let selectedPaymentMethod = 'cash';
    let searchTimeout;

    // --- EVENT LISTENERS ---
    if (productList) { productList.addEventListener('click', handleProductClick); }
    if (cartItemsEl) { cartItemsEl.addEventListener('click', handleCartClick); }
    if (productSearch) { productSearch.addEventListener('keyup', handleProductSearch); }
    
    if (completeSaleBtn) { completeSaleBtn.addEventListener('click', openConfirmationModal); }
    if (confirmPayBtn) { confirmPayBtn.addEventListener('click', processSale); }

    // Customer search listeners
    if (customerSearchInput) {
        customerSearchInput.addEventListener('focus', () => searchCustomers(''));
        customerSearchInput.addEventListener('keyup', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => searchCustomers(customerSearchInput.value), 300);
        });
    }
    if (customerResultsDiv) { customerResultsDiv.addEventListener('click', handleCustomerSelect); }
    document.addEventListener('click', (e) => {
        if (customerSearchInput && !customerSearchInput.contains(e.target) && !customerResultsDiv.contains(e.target)) {
            customerResultsDiv.style.display = 'none';
        }
    });

    // Payment method selection listeners
    paymentMethodBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            paymentMethodBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            selectedPaymentMethod = this.dataset.method;
            bankSelectionDiv.style.display = (selectedPaymentMethod === 'card' || selectedPaymentMethod === 'qr') ? 'block' : 'none';
        });
    });

    // Modal closing listeners
    if (modalCloseBtn) { modalCloseBtn.addEventListener('click', () => confirmationModal.style.display = 'none'); }
    window.addEventListener('click', (e) => {
        if (e.target == confirmationModal) { confirmationModal.style.display = 'none'; }
    });

    // --- FUNCTIONS ---

    function openConfirmationModal() {
        if (cart.length === 0) { alert("Cart is empty!"); return; }
        let summaryHTML = '<ul class="modal-summary-list">';
        let grandTotal = 0;
        cart.forEach(item => {
            const itemTotal = item.price * item.quantity;
            grandTotal += itemTotal;
            summaryHTML += `<li><span>${item.name} <strong>(x${item.quantity})</strong></span> <span>$${itemTotal.toFixed(2)}</span></li>`;
        });
        summaryHTML += '</ul>';
        modalCartSummary.innerHTML = summaryHTML;
        modalGrandTotal.textContent = `$${grandTotal.toFixed(2)}`;
        confirmationModal.style.display = 'block';
    }

    function processSale() {
        if (cart.length === 0) { alert("Cart is empty."); return; }
        
        // **LARAVEL CHANGE**: Get the CSRF token from the meta tag
        const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';
        if (!csrfToken) {
            alert('Security token not found. Please refresh the page.');
            return;
        }

        const paymentProvider = (selectedPaymentMethod === 'card' || selectedPaymentMethod === 'qr') ? bankSelect.value : null;
        const saleData = {
            cart: cart,
            customerId: selectedCustomerId,
            paymentMethod: selectedPaymentMethod,
            paymentProvider: paymentProvider
        };

        // **LARAVEL CHANGE**: Update fetch URL and add CSRF token header
        fetch('/ajax/sales', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken // Add Laravel's security token
            },
            body: JSON.stringify(saleData)
        })
        .then(response => {
            if (!response.ok) {
                // Handle server errors (like 500 internal server error)
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Sale completed successfully!');
                // **LARAVEL CHANGE**: Redirect to the new invoice route
                window.location.href = `/invoice/${data.sale_id}`;
            } else {
                alert('Error completing sale: ' + (data.message || 'Unknown error.'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('A critical error occurred. Please check the console for details.');
        });
    }

    // Customer-related functions
    function handleCustomerSelect(e) {
        const target = e.target.closest('.customer-result-item');
        if (target) {
            selectedCustomerId = target.dataset.id;
            selectedCustomerNameEl.textContent = target.dataset.name;
            customerResultsDiv.style.display = 'none';
            customerSearchInput.value = '';
        }
    }

    function searchCustomers(term) {
        // **LARAVEL CHANGE**: Update fetch URL to the new route
        fetch(`/ajax/search-customers?term=${encodeURIComponent(term)}`)
            .then(res => res.json())
            .then(data => {
                let html = '';
                customerResultsDiv.style.display = 'block';
                if (data.length > 0) {
                    data.forEach(customer => {
                        html += `<div class="customer-result-item" data-id="${customer.id}" data-name="${customer.name}">${customer.name} (${customer.phone_number})</div>`;
                    });
                } else {
                    html = (term.length > 0) ? '<div class="customer-result-item-none">No customers found.</div>' : '<div class="customer-result-item-none">Showing all customers...</div>';
                }
                customerResultsDiv.innerHTML = html;
            });
    }

    // Product and Cart related functions
    function handleProductClick(e) {
        const card = e.target.closest('.product-card');
        if (card) {
            const product = {
                id: card.dataset.id,
                name: card.dataset.name,
                price: parseFloat(card.dataset.price)
            };
            addItemToCart(product);
        }
    }

    function handleCartClick(e) {
        const target = e.target;
        const productId = target.dataset.id;
        if (!productId) return;
        if (target.classList.contains('qty-increase')) {
            updateQuantity(productId, 1);
        } else if (target.classList.contains('qty-decrease')) {
            updateQuantity(productId, -1);
        } else if (target.classList.contains('btn-remove-item')) {
            removeItemFromCart(productId);
        }
    }

    function handleProductSearch() {
        const term = productSearch.value.toLowerCase().trim();
        document.querySelectorAll('.product-card').forEach(product => {
            const name = product.dataset.name.toLowerCase();
            const sku = product.dataset.sku ? product.dataset.sku.toLowerCase() : '';
            product.style.display = (name.includes(term) || sku.includes(term)) ? 'block' : 'none';
        });
    }

    function addItemToCart(product) {
        const existingItem = cart.find(item => item.id === product.id);
        if (existingItem) {
            existingItem.quantity++;
        } else {
            cart.push({ ...product, quantity: 1 });
        }
        renderCart();
    }

    function updateQuantity(productId, change) {
        const item = cart.find(item => item.id === productId);
        if (item) {
            item.quantity += change;
            if (item.quantity <= 0) {
                removeItemFromCart(productId);
            } else {
                renderCart();
            }
        }
    }

    function removeItemFromCart(productId) {
        cart = cart.filter(item => item.id !== productId);
        renderCart();
    }
    
    function renderCart() {
        if (!cartItemsEl || !cartTotalEl) return;
        cartItemsEl.innerHTML = '';
        let total = 0;
        if (cart.length === 0) {
            cartItemsEl.innerHTML = '<p>Select products from the left to begin.</p>';
        } else {
            cart.forEach(item => {
                const itemTotal = item.price * item.quantity;
                total += itemTotal;
                cartItemsEl.innerHTML += `
                    <div class="cart-item">
                        <div class="cart-item-details">
                            <span class="cart-item-name">${item.name}</span>
                            <span class="cart-item-price">$${item.price.toFixed(2)}</span>
                        </div>
                        <div class="cart-item-controls">
                            <button class="btn-qty qty-decrease" data-id="${item.id}">-</button>
                            <span class="cart-item-quantity">${item.quantity}</span>
                            <button class="btn-qty qty-increase" data-id="${item.id}">+</button>
                        </div>
                        <div class="cart-item-total-price">$${itemTotal.toFixed(2)}</div>
                        <button class="btn-remove-item" data-id="${item.id}">&times;</button>
                    </div>`;
            });
        }
        cartTotalEl.textContent = `$${total.toFixed(2)}`;
    }
});
