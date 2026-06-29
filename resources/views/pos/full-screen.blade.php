@extends('layouts.pos')

@section('title', 'POS - Full Screen')

@section('content')
<!-- POS Header -->
<div class="pos-header">
    <div class="pos-logo">
        <i class="fas fa-utensils"></i>
        <span>{{ $restaurant->name ?? 'Restaurant' }}</span>
    </div>
    <div class="pos-nav">
        <button class="nav-btn active" data-nav="pos">
            <i class="fas fa-cash-register"></i>
            <span>POS</span>
        </button>
        <button class="nav-btn" data-nav="orders">
            <i class="fas fa-shopping-cart"></i>
            <span>Order</span>
        </button>
        <button class="nav-btn" data-nav="table">
            <i class="fas fa-chair"></i>
            <span>Table</span>
        </button>
        <button class="nav-btn" data-nav="kot">
            <i class="fas fa-fire"></i>
            <span>KOT</span>
        </button>
    </div>
    <div class="pos-exit">
        <button class="exit-btn" onclick="exitPOSMode()">
            <i class="fas fa-sign-out-alt"></i>
            <span>Exit POS Mode</span>
        </button>
    </div>
</div>

<!-- Order Type Selection -->
<div class="order-type-bar">
    <div class="order-type-buttons">
        <button class="order-type-btn active" data-type="dine-in">
            <i class="fas fa-utensils"></i>
            <span>Dine In</span>
        </button>
        <button class="order-type-btn" data-type="delivery">
            <i class="fas fa-truck"></i>
            <span>Delivery</span>
        </button>
        <button class="order-type-btn" data-type="pickup">
            <i class="fas fa-shopping-bag"></i>
            <span>Pickup</span>
        </button>
    </div>
</div>

<!-- Category Filters -->
<div class="category-filters">
    <button class="filter-btn active" data-filter="all">Show All</button>
    <button class="filter-btn" data-filter="lunch">Lunch</button>
    <button class="filter-btn" data-filter="beverages">Beverages</button>
    <button class="filter-btn" data-filter="breakfast">Breakfast</button>
    <button class="filter-btn" data-filter="snacks">Snacks</button>
</div>

<!-- Main Content -->
<div class="pos-main">
    <!-- Left Panel - Categories -->
    <div class="pos-left-panel">
        <div class="category-search">
            <input type="text" placeholder="Search categories..." id="categorySearch">
        </div>
        <div class="category-list">
            <div class="category-item active" data-category="all">
                <span class="icon">🍽️</span>
                <span class="name">All Items</span>
            </div>
            @foreach($categories as $category)
            <div class="category-item" data-category="{{ $category->slug }}">
                <span class="icon">{{ $category->icon ?? '🍽️' }}</span>
                <span class="name">{{ $category->name }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Center Panel - Food Items -->
    <div class="pos-center-panel">
        <div class="pos-center-header">
            <h2 id="currentCategory">All Items</h2>
            <div class="item-search">
                <input type="text" placeholder="Search items or scan barcode..." id="itemSearch">
                <button class="action-btn info" style="min-width: auto;">
                    <i class="fas fa-barcode"></i>
                </button>
            </div>
        </div>
        <div class="food-grid" id="foodGrid">
            @foreach($menuItems as $item)
            <div class="food-item {{ $item->is_available ? '' : 'outrofstock' }}" 
                 data-item-id="{{ $item->id }}" 
                 data-category="{{ $item->category->slug ?? 'other' }}"
                 data-price="{{ $item->price }}"
                 data-name="{{ $item->name }}">
                @if($item->image)
                <img src="{{ asset('storage/menu/' . $item->image) }}" alt="{{ $item->name }}" class="food-item-image" onerror="this.src='https://via.placeholder.com/200x100/667eea/ffffff?text={{ $item->name }}'">
                @else
                <img src="https://via.placeholder.com/200x100/667eea/ffffff?text={{ $item->name }}" alt="{{ $item->name }}" class="food-item-image">
                @endif
                <div class="food-item-details">
                    <div class="food-item-name">{{ $item->name }}</div>
                    <div class="food-item-price">Rs. {{ number_format($item->price) }}</div>
                    <div class="food-item-meta">
                        <span class="stock-status {{ $item->is_available ? '' : 'out' }}">{{ $item->is_available ? 'In Stock' : 'Out of Stock' }}</span>
                        <div class="veg-icon {{ $item->is_vegetarian ? '' : 'non-veg' }}">●</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Right Panel - Current Order -->
    <div class="pos-right-panel">
        <div class="order-header">
            <h3><i class="fas fa-shopping-cart"></i> Current Order</h3>
            <div class="table-dropdown">
                <select class="form-control" id="tableSelect" onchange="handleTableChange()">
                    <option value="">Select Table</option>
                    @for($i = 1; $i <= 20; $i++)
                    <option value="{{ $i }}">Table {{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>
        <div class="order-items" id="orderItems">
            <div class="order-item" data-order-item-id="1">
                <div class="order-item-header">
                    <span class="order-item-name">Margherita Pizza</span>
                    <span class="order-item-remove"><i class="fas fa-times"></i></span>
                </div>
                <div class="order-item-qty">
                    <button class="qty-btn minus">-</button>
                    <span class="qty-value">2</span>
                    <button class="qty-btn plus">+</button>
                    <span class="order-item-price" style="margin-left: auto;">Rs. 900</span>
                </div>
                <div class="order-item-note">Extra cheese</div>
            </div>
            <div class="order-item" data-order-item-id="2">
                <div class="order-item-header">
                    <span class="order-item-name">Chicken Momo (10 pcs)</span>
                    <span class="order-item-remove"><i class="fas fa-times"></i></span>
                </div>
                <div class="order-item-qty">
                    <button class="qty-btn minus">-</button>
                    <span class="qty-value">1</span>
                    <button class="qty-btn plus">+</button>
                    <span class="order-item-price" style="margin-left: auto;">Rs. 180</span>
                </div>
            </div>
            <div class="order-item" data-order-item-id="3">
                <div class="order-item-header">
                    <span class="order-item-name">Coca Cola (500ml)</span>
                    <span class="order-item-remove"><i class="fas fa-times"></i></span>
                </div>
                <div class="order-item-qty">
                    <button class="qty-btn minus">-</button>
                    <span class="qty-value">2</span>
                    <button class="qty-btn plus">+</button>
                    <span class="order-item-price" style="margin-left: auto;">Rs. 160</span>
                </div>
            </div>
        </div>
        <div class="order-summary">
            <div class="summary-row">
                <span>Subtotal</span>
                <span>Rs. 1,240</span>
            </div>
            <div class="summary-row">
                <span>VAT (13%)</span>
                <span>Rs. 161</span>
            </div>
            <div class="summary-row">
                <span>Service Charge (10%)</span>
                <span>Rs. 124</span>
            </div>
            <div class="summary-row">
                <span>Discount</span>
                <span>- Rs. 50</span>
            </div>
            <div class="summary-row total">
                <span>Grand Total</span>
                <span>Rs. 1,475</span>
            </div>
        </div>
    </div>
</div>

<!-- Bottom Action Buttons -->
<div class="pos-bottom-bar">
    <button class="action-btn primary" onclick="newOrder()">
        <i class="fas fa-plus"></i> New Order
    </button>
    <button class="action-btn warning" onclick="holdOrder()">
        <i class="fas fa-pause"></i> Hold Order
    </button>
    <button class="action-btn info" onclick="resumeOrder()">
        <i class="fas fa-play"></i> Resume Order
    </button>
    <button class="action-btn info" onclick="splitBill()">
        <i class="fas fa-divide"></i> Split Bill
    </button>
    <button class="action-btn info" onclick="mergeBill()">
        <i class="fas fa-object-group"></i> Merge Bill
    </button>
    <button class="action-btn info" onclick="transferTable()">
        <i class="fas fa-exchange-alt"></i> Transfer Table
    </button>
    <button class="action-btn success" onclick="printKOT()">
        <i class="fas fa-print"></i> Print KOT
    </button>
    <button class="action-btn success" onclick="printBill()">
        <i class="fas fa-receipt"></i> Print Bill
    </button>
    <button class="action-btn primary" onclick="showPaymentModal()">
        <i class="fas fa-credit-card"></i> Payment
    </button>
    <button class="action-btn danger" onclick="cancelOrder()">
        <i class="fas fa-times"></i> Cancel Order
    </button>
</div>

<!-- Payment Modal -->
<div class="payment-modal" id="paymentModal">
    <div class="payment-modal-content">
        <div class="payment-modal-header">
            <h3><i class="fas fa-credit-card"></i> Payment</h3>
        </div>
        <div class="payment-modal-body">
            <div class="payment-methods">
                <div class="payment-method active" data-method="cash">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>Cash</span>
                </div>
                <div class="payment-method" data-method="card">
                    <i class="fas fa-credit-card"></i>
                    <span>Card</span>
                </div>
                <div class="payment-method" data-method="esewa">
                    <i class="fas fa-mobile-alt"></i>
                    <span>eSewa</span>
                </div>
                <div class="payment-method" data-method="khalti">
                    <i class="fas fa-wallet"></i>
                    <span>Khalti</span>
                </div>
                <div class="payment-method" data-method="fonepay">
                    <i class="fas fa-qrcode"></i>
                    <span>FonePay</span>
                </div>
                <div class="payment-method" data-method="connectips">
                    <i class="fas fa-university"></i>
                    <span>ConnectIPS</span>
                </div>
            </div>
            <div class="payment-amount">
                <label>Total Amount</label>
                <input type="text" value="1,475" readonly>
            </div>
            <div class="payment-amount">
                <label>Received Amount</label>
                <input type="number" id="receivedAmount" placeholder="Enter amount" oninput="calculateChange()">
            </div>
            <div class="change-display">
                <div class="label">Change to Return</div>
                <div class="amount" id="changeAmount">Rs. 0</div>
            </div>
        </div>
        <div class="payment-modal-footer">
            <button class="btn-cancel" onclick="hidePaymentModal()">Cancel</button>
            <button class="btn-confirm" onclick="processPayment()">
                <i class="fas fa-check"></i> Confirm & Print
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
// POS Navigation
document.querySelectorAll('.nav-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        const nav = this.dataset.nav;
        // Handle navigation based on nav type
        if (nav === 'pos') {
            // Show POS view
        } else if (nav === 'orders') {
            window.location.href = '/orders';
        } else if (nav === 'table') {
            window.location.href = '/tables';
        } else if (nav === 'kot') {
            window.location.href = '/kitchen/kot';
        }
    });
});

// Order Type Selection
document.querySelectorAll('.order-type-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.order-type-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        const orderType = this.dataset.type;
        const tableSelect = document.getElementById('tableSelect');
        
        // Show/hide table dropdown based on order type
        if (orderType === 'dine-in') {
            tableSelect.style.display = 'block';
        } else {
            tableSelect.style.display = 'none';
            tableSelect.value = '';
        }
    });
});

// Table Selection
function handleTableChange() {
    const tableSelect = document.getElementById('tableSelect');
    const selectedTable = tableSelect.value;
    
    if (selectedTable) {
        console.log('Table selected:', selectedTable);
        // You can add logic here to handle table selection
    }
}

// Category Filters
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        const filter = this.dataset.filter;
        const foodItems = document.querySelectorAll('.food-item');
        
        foodItems.forEach(item => {
            if (filter === 'all') {
                item.style.display = 'block';
            } else {
                // Filter based on category or other criteria
                const category = item.dataset.category;
                if (category === filter) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            }
        });
    });
});

// Table Selection
function showTableModal() {
    $('#tableModal').modal('show');
}

function selectTable(tableNumber) {
    document.getElementById('selectedTable').textContent = 'Table ' + tableNumber;
    
    // Update table card selection
    document.querySelectorAll('.table-card').forEach(card => {
        card.classList.remove('selected');
    });
    document.querySelector(`.table-card[data-table="${tableNumber}"]`).classList.add('selected');
    
    // Close modal after selection
    setTimeout(() => {
        $('#tableModal').modal('hide');
    }, 300);
}

// Exit POS Mode
function exitPOSMode() {
    if (confirm('Are you sure you want to exit POS mode?')) {
        window.location.href = '/dashboard';
    }
}

// Category Selection
document.querySelectorAll('.category-item').forEach(item => {
    item.addEventListener('click', function() {
        document.querySelectorAll('.category-item').forEach(i => i.classList.remove('active'));
        this.classList.add('active');
        
        const category = this.dataset.category;
        document.getElementById('currentCategory').textContent = this.querySelector('.name').textContent;
        
        // Filter food items
        document.querySelectorAll('.food-item').forEach(foodItem => {
            if (category === 'all' || foodItem.dataset.category === category) {
                foodItem.style.display = 'block';
            } else {
                foodItem.style.display = 'none';
            }
        });
    });
});

// Food Item Selection
document.querySelectorAll('.food-item').forEach(item => {
    item.addEventListener('click', function() {
        if (this.classList.contains('outrofstock')) return;
        
        const itemId = this.dataset.itemId;
        const itemName = this.dataset.name;
        const itemPrice = this.dataset.price;
        
        addToOrder(itemId, itemName, itemPrice);
    });
});

// Add item to order
function addToOrder(itemId, itemName, itemPrice) {
    const orderItems = document.getElementById('orderItems');
    
    // Check if item already exists
    let existingItem = document.querySelector(`[data-order-item-id="${itemId}"]`);
    
    if (existingItem) {
        const qtyValue = existingItem.querySelector('.qty-value');
        const currentQty = parseInt(qtyValue.textContent);
        qtyValue.textContent = currentQty + 1;
        updateOrderItemPrice(existingItem, itemPrice, currentQty + 1);
    } else {
        const newItem = document.createElement('div');
        newItem.className = 'order-item';
        newItem.dataset.orderItemId = itemId;
        newItem.innerHTML = `
            <div class="order-item-header">
                <span class="order-item-name">${itemName}</span>
                <span class="order-item-remove"><i class="fas fa-times"></i></span>
            </div>
            <div class="order-item-qty">
                <button class="qty-btn minus">-</button>
                <span class="qty-value">1</span>
                <button class="qty-btn plus">+</button>
                <span class="order-item-price" style="margin-left: auto;">${itemPrice}</span>
            </div>
        `;
        orderItems.appendChild(newItem);
        
        // Add event listeners
        newItem.querySelector('.order-item-remove').addEventListener('click', function() {
            newItem.remove();
            updateTotals();
        });
        
        newItem.querySelectorAll('.qty-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const qtyValue = newItem.querySelector('.qty-value');
                let qty = parseInt(qtyValue.textContent);
                
                if (this.classList.contains('plus')) {
                    qty++;
                } else if (this.classList.contains('minus') && qty > 1) {
                    qty--;
                }
                
                qtyValue.textContent = qty;
                updateOrderItemPrice(newItem, itemPrice, qty);
            });
        });
    }
    
    updateTotals();
}

function updateOrderItemPrice(item, price, qty) {
    const priceNum = parseInt(price.replace(/[^0-9]/g, ''));
    const totalPrice = priceNum * qty;
    item.querySelector('.order-item-price').textContent = 'Rs. ' + totalPrice;
    updateTotals();
}

function updateTotals() {
    // Calculate totals from order items
    let subtotal = 0;
    document.querySelectorAll('.order-item').forEach(item => {
        const price = item.querySelector('.order-item-price').textContent;
        subtotal += parseInt(price.replace(/[^0-9]/g, ''));
    });
    
    const vat = Math.round(subtotal * 0.13);
    const serviceCharge = Math.round(subtotal * 0.10);
    const discount = 50;
    const grandTotal = subtotal + vat + serviceCharge - discount;
    
    const summary = document.querySelector('.order-summary');
    summary.innerHTML = `
        <div class="summary-row">
            <span>Subtotal</span>
            <span>Rs. ${subtotal.toLocaleString()}</span>
        </div>
        <div class="summary-row">
            <span>VAT (13%)</span>
            <span>Rs. ${vat.toLocaleString()}</span>
        </div>
        <div class="summary-row">
            <span>Service Charge (10%)</span>
            <span>Rs. ${serviceCharge.toLocaleString()}</span>
        </div>
        <div class="summary-row">
            <span>Discount</span>
            <span>- Rs. ${discount.toLocaleString()}</span>
        </div>
        <div class="summary-row total">
            <span>Grand Total</span>
            <span>Rs. ${grandTotal.toLocaleString()}</span>
        </div>
    `;
}

// Payment Modal
function showPaymentModal() {
    document.getElementById('paymentModal').classList.add('show');
}

function hidePaymentModal() {
    document.getElementById('paymentModal').classList.remove('show');
}

// Payment Method Selection
document.querySelectorAll('.payment-method').forEach(method => {
    method.addEventListener('click', function() {
        document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('active'));
        this.classList.add('active');
    });
});

function calculateChange() {
    const received = parseInt(document.getElementById('receivedAmount').value) || 0;
    const total = 1475; // This should be dynamic
    const change = received - total;
    
    document.getElementById('changeAmount').textContent = 
        change >= 0 ? 'Rs. ' + change.toLocaleString() : 'Insufficient Amount';
}

function processPayment() {
    alert('Payment processed successfully!');
    hidePaymentModal();
}

// Action Buttons
function newOrder() {
    if (confirm('Start a new order? Current order will be cleared.')) {
        document.getElementById('orderItems').innerHTML = '';
        updateTotals();
    }
}

function holdOrder() {
    alert('Order held successfully!');
}

function resumeOrder() {
    alert('Resume order functionality');
}

function splitBill() {
    alert('Split bill functionality');
}

function mergeBill() {
    alert('Merge bill functionality');
}

function transferTable() {
    alert('Transfer table functionality');
}

function printKOT() {
    alert('KOT sent to kitchen!');
}

function printBill() {
    alert('Bill printed!');
}

function cancelOrder() {
    if (confirm('Are you sure you want to cancel this order?')) {
        document.getElementById('orderItems').innerHTML = '';
        updateTotals();
    }
}

// Keyboard Shortcuts
document.addEventListener('keydown', function(e) {
    if (e.key === 'F1') {
        e.preventDefault();
        newOrder();
    } else if (e.key === 'F2') {
        e.preventDefault();
        holdOrder();
    } else if (e.key === 'F3') {
        e.preventDefault();
        resumeOrder();
    } else if (e.key === 'F4') {
        e.preventDefault();
        showPaymentModal();
    } else if (e.key === 'F5') {
        e.preventDefault();
        printKOT();
    } else if (e.key === 'F6') {
        e.preventDefault();
        printBill();
    } else if (e.key === 'F12') {
        e.preventDefault();
        cancelOrder();
    }
});

// Category Search
document.getElementById('categorySearch').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    document.querySelectorAll('.category-item').forEach(item => {
        const name = item.querySelector('.name').textContent.toLowerCase();
        if (name.includes(searchTerm)) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
});

// Item Search
document.getElementById('itemSearch').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    document.querySelectorAll('.food-item').forEach(item => {
        const name = item.querySelector('.food-item-name').textContent.toLowerCase();
        if (name.includes(searchTerm)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
});
</script>
@endpush
