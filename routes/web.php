<?php

use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TenantManagementController;
use App\Http\Controllers\SubscriptionPlanController;
use App\Http\Controllers\TrialAccountController;
use App\Http\Controllers\PaymentGatewayController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\RestaurantManagementController;
use App\Http\Controllers\MenuManagementController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\AccountingController;
use App\Http\Controllers\HRMController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SupportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingPageController::class, 'index']);

// PWA Manifest Route
Route::get('/manifest.json', function() {
    $filePath = public_path('manifest.json');
    if (file_exists($filePath)) {
        return response()->file($filePath, ['Content-Type' => 'application/manifest+json']);
    }
    return response('Manifest file not found', 404);
});

// PWA Manifest Route (legacy path)
Route::get('/public/manifest.json', function() {
    $filePath = public_path('manifest.json');
    if (file_exists($filePath)) {
        return response()->file($filePath, ['Content-Type' => 'application/manifest+json']);
    }
    return response('Manifest file not found', 404);
});

// PWA Service Worker Route
Route::get('/sw.js', function() {
    $filePath = public_path('sw.js');
    headers_sent() || header('Content-Type: application/javascript');
    if (file_exists($filePath)) {
        return response()->file($filePath, ['Content-Type' => 'application/javascript']);
    }
    return response('Service Worker file not found', 404);
});

// PWA Icons Routes
Route::get('/icons/{filename}', function($filename) {
    $filePath = public_path('icons/' . $filename);
    if (file_exists($filePath)) {
        return response()->file($filePath, ['Content-Type' => 'image/svg+xml']);
    }
    return response('Icon file not found', 404);
});

// Mobile App Download Route
Route::get('/download-app', function() {
    $filePath = public_path('downloads/nepal-restaurant-saas.apk');
    if (file_exists($filePath)) {
        return response()->download($filePath, 'nepal-restaurant-saas.apk');
    }
    return response('APK file not found', 404);
})->name('download-app');

// PWA Offline Route
Route::get('/offline', function() {
    return view('offline');
})->name('offline');

// API Ping for offline detection
Route::get('/api/ping', function() {
    return response()->json(['status' => 'online']);
})->name('api.ping');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

Route::middleware(['auth'])->prefix('settings')->name('settings.')->group(function () {
    Route::get('/billing', [DashboardController::class, 'billing'])->name('billing');
});

Route::middleware(['auth'])->prefix('subscription')->name('subscription.')->group(function () {
    Route::get('/renew', [DashboardController::class, 'renew'])->name('renew');
});

Route::middleware(['auth'])->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::post('/{id}/mark-read', [NotificationController::class, 'markAsRead'])->name('mark-read');
    Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
});

// Orders Routes
Route::middleware(['auth'])->prefix('orders')->name('orders.')->group(function () {
    Route::get('/', [OrdersController::class, 'index'])->name('index');
    Route::get('/new', [OrdersController::class, 'newOrders'])->name('new');
    Route::get('/dine-in', [OrdersController::class, 'dineIn'])->name('dine-in');
    Route::get('/takeaway', [OrdersController::class, 'takeaway'])->name('takeaway');
    Route::get('/delivery', [OrdersController::class, 'delivery'])->name('delivery');
    Route::get('/online', [OrdersController::class, 'online'])->name('online');
    Route::get('/scheduled', [OrdersController::class, 'scheduled'])->name('scheduled');
    Route::get('/history', [OrdersController::class, 'history'])->name('history');
    Route::get('/cancelled', [OrdersController::class, 'cancelled'])->name('cancelled');
    Route::get('/returned', [OrdersController::class, 'returned'])->name('returned');
});

// Restaurant Management Routes
Route::middleware(['auth'])->prefix('restaurant')->name('restaurant.')->group(function () {
    Route::get('/', [RestaurantManagementController::class, 'index'])->name('index');
    Route::get('/profile', [RestaurantManagementController::class, 'profile'])->name('profile');
    Route::get('/branches', [RestaurantManagementController::class, 'branches'])->name('branches');
    Route::get('/floors', [RestaurantManagementController::class, 'index'])->name('floors');
    Route::get('/dining-areas', [RestaurantManagementController::class, 'index'])->name('dining-areas');
    Route::get('/tables', [RestaurantManagementController::class, 'tables'])->name('tables');
    Route::get('/table-categories', [RestaurantManagementController::class, 'index'])->name('table-categories');
    Route::get('/qr-codes', [RestaurantManagementController::class, 'index'])->name('qr-codes');
    Route::get('/reservation-settings', [RestaurantManagementController::class, 'index'])->name('reservation-settings');
    Route::get('/waiters', [RestaurantManagementController::class, 'waiters'])->name('waiters');
    Route::get('/kitchen', [RestaurantManagementController::class, 'kitchen'])->name('kitchen');
    Route::get('/printers', [RestaurantManagementController::class, 'printers'])->name('printers');
    Route::get('/integrations', [RestaurantManagementController::class, 'integrations'])->name('integrations');
});

// Menu Management Routes
Route::middleware(['auth'])->prefix('menu')->name('menu.')->group(function () {
    Route::get('/', [MenuManagementController::class, 'index'])->name('index');
    Route::get('/categories', [MenuManagementController::class, 'categories'])->name('categories');
    Route::get('/items', [MenuManagementController::class, 'items'])->name('items');
    Route::get('/modifiers', [MenuManagementController::class, 'modifiers'])->name('modifiers');
    Route::get('/combos', [MenuManagementController::class, 'combos'])->name('combos');
    Route::get('/pricing', [MenuManagementController::class, 'pricing'])->name('pricing');
    Route::get('/availability', [MenuManagementController::class, 'availability'])->name('availability');
    Route::get('/qr-menu', [MenuManagementController::class, 'qrMenu'])->name('qr-menu');
});

// POS Routes
Route::middleware(['auth'])->prefix('pos')->name('pos.')->group(function () {
    Route::get('/', [POSController::class, 'index'])->name('index');
    Route::get('/full-screen', [POSController::class, 'fullScreen'])->name('full-screen');
    Route::get('/new-billing', [POSController::class, 'newBilling'])->name('new-billing');
    Route::get('/quick-billing', [POSController::class, 'quickBilling'])->name('quick-billing');
    Route::get('/hold-bills', [POSController::class, 'holdBills'])->name('hold-bills');
    Route::get('/resume-bills', [POSController::class, 'resumeBills'])->name('resume-bills');
    Route::get('/split-bills', [POSController::class, 'splitBills'])->name('split-bills');
    Route::get('/merge-bills', [POSController::class, 'mergeBills'])->name('merge-bills');
    Route::get('/reprint-bill', [POSController::class, 'reprintBill'])->name('reprint-bill');
    Route::get('/refund-bills', [POSController::class, 'refundBills'])->name('refund-bills');
    Route::get('/void-bills', [POSController::class, 'voidBills'])->name('void-bills');
    Route::get('/daily-closing', [POSController::class, 'dailyClosing'])->name('daily-closing');
});

// Kitchen Routes
Route::middleware(['auth'])->prefix('kitchen')->name('kitchen.')->group(function () {
    Route::get('/', [KitchenController::class, 'index'])->name('index');
    Route::get('/dashboard', [KitchenController::class, 'dashboard'])->name('dashboard');
    Route::get('/pending', [KitchenController::class, 'pending'])->name('pending');
    Route::get('/preparing', [KitchenController::class, 'preparing'])->name('preparing');
    Route::get('/ready', [KitchenController::class, 'ready'])->name('ready');
    Route::get('/served', [KitchenController::class, 'served'])->name('served');
    Route::get('/display', [KitchenController::class, 'display'])->name('display');
    Route::get('/tickets', [KitchenController::class, 'tickets'])->name('tickets');
    Route::get('/kot', [KitchenController::class, 'kot'])->name('kot');
    Route::get('/kds', [KitchenController::class, 'kds'])->name('kds');
    Route::post('/orders/{orderId}/status', [KitchenController::class, 'updateOrderStatus'])->name('update-status');
});

// Inventory Routes
Route::middleware(['auth'])->prefix('inventory')->name('inventory.')->group(function () {
    Route::get('/', [InventoryController::class, 'index'])->name('index');
    Route::get('/raw-materials', [InventoryController::class, 'rawMaterials'])->name('raw-materials');
    Route::get('/products', [InventoryController::class, 'products'])->name('products');
    Route::get('/warehouses', [InventoryController::class, 'warehouses'])->name('warehouses');
    Route::get('/stock-list', [InventoryController::class, 'stockList'])->name('stock-list');
    Route::get('/stock-adjustment', [InventoryController::class, 'stockAdjustment'])->name('stock-adjustment');
    Route::get('/stock-transfer', [InventoryController::class, 'stockTransfer'])->name('stock-transfer');
    Route::get('/wastage', [InventoryController::class, 'wastage'])->name('wastage');
    Route::get('/expired-items', [InventoryController::class, 'expiredItems'])->name('expired-items');
    Route::get('/stock-ledger', [InventoryController::class, 'stockLedger'])->name('stock-ledger');
});

// Purchase Routes
Route::middleware(['auth'])->prefix('purchase')->name('purchase.')->group(function () {
    Route::get('/', [PurchaseController::class, 'index'])->name('index');
    Route::get('/suppliers', [PurchaseController::class, 'suppliers'])->name('suppliers');
    Route::get('/purchase-orders', [PurchaseController::class, 'purchaseOrders'])->name('purchase-orders');
    Route::get('/purchase-receive', [PurchaseController::class, 'purchaseReceive'])->name('purchase-receive');
    Route::get('/purchase-invoice', [PurchaseController::class, 'purchaseInvoice'])->name('purchase-invoice');
    Route::get('/purchase-return', [PurchaseController::class, 'purchaseReturn'])->name('purchase-return');
    Route::get('/supplier-payments', [PurchaseController::class, 'supplierPayments'])->name('supplier-payments');
    Route::get('/due-payments', [PurchaseController::class, 'duePayments'])->name('due-payments');
});

// Customers Routes
Route::middleware(['auth'])->prefix('customers')->name('customers.')->group(function () {
    Route::get('/', [CustomersController::class, 'index'])->name('index');
    Route::get('/customer-list', [CustomersController::class, 'customerList'])->name('customer-list');
    Route::get('/membership', [CustomersController::class, 'membership'])->name('membership');
    Route::get('/loyalty-points', [CustomersController::class, 'loyaltyPoints'])->name('loyalty-points');
    Route::get('/wallet', [CustomersController::class, 'wallet'])->name('wallet');
    Route::get('/feedback', [CustomersController::class, 'feedback'])->name('feedback');
    Route::get('/reviews', [CustomersController::class, 'reviews'])->name('reviews');
});

// Delivery Routes
Route::middleware(['auth'])->prefix('delivery')->name('delivery.')->group(function () {
    Route::get('/', [DeliveryController::class, 'index'])->name('index');
    Route::get('/dashboard', [DeliveryController::class, 'dashboard'])->name('dashboard');
    Route::get('/delivery-orders', [DeliveryController::class, 'deliveryOrders'])->name('delivery-orders');
    Route::get('/delivery-partners', [DeliveryController::class, 'deliveryPartners'])->name('delivery-partners');
    Route::get('/delivery-charges', [DeliveryController::class, 'deliveryCharges'])->name('delivery-charges');
    Route::get('/rider-management', [DeliveryController::class, 'riderManagement'])->name('rider-management');
    Route::get('/delivery-reports', [DeliveryController::class, 'deliveryReports'])->name('delivery-reports');
});

// Sales Routes
Route::middleware(['auth'])->prefix('sales')->name('sales.')->group(function () {
    Route::get('/', [SalesController::class, 'index'])->name('index');
    Route::get('/daily-sales', [SalesController::class, 'dailySales'])->name('daily-sales');
    Route::get('/monthly-sales', [SalesController::class, 'monthlySales'])->name('monthly-sales');
    Route::get('/voucher', [SalesController::class, 'voucher'])->name('voucher');
    Route::get('/journal-voucher', [SalesController::class, 'journalVoucher'])->name('journal-voucher');
    Route::get('/payment-voucher', [SalesController::class, 'paymentVoucher'])->name('payment-voucher');
    Route::get('/receipt-voucher', [SalesController::class, 'receiptVoucher'])->name('receipt-voucher');
    Route::get('/contra-voucher', [SalesController::class, 'contraVoucher'])->name('contra-voucher');
});

// Accounting Routes
Route::middleware(['auth'])->prefix('accounting')->name('accounting.')->group(function () {
    Route::get('/', [AccountingController::class, 'index'])->name('index');
    Route::get('/accounts', [AccountingController::class, 'accounts'])->name('accounts');
    Route::get('/chart-of-accounts', [AccountingController::class, 'chartOfAccounts'])->name('chart-of-accounts');
    Route::get('/ledger', [AccountingController::class, 'ledger'])->name('ledger');
    Route::get('/cash-book', [AccountingController::class, 'cashBook'])->name('cash-book');
    Route::get('/bank-book', [AccountingController::class, 'bankBook'])->name('bank-book');
    Route::get('/petty-cash', [AccountingController::class, 'pettyCash'])->name('petty-cash');
    Route::get('/transactions', [AccountingController::class, 'transactions'])->name('transactions');
    Route::get('/income', [AccountingController::class, 'income'])->name('income');
    Route::get('/expenses', [AccountingController::class, 'expenses'])->name('expenses');
    Route::get('/bank-transactions', [AccountingController::class, 'bankTransactions'])->name('bank-transactions');
    Route::get('/bank-reconciliation', [AccountingController::class, 'bankReconciliation'])->name('bank-reconciliation');
    Route::get('/financial-reports', [AccountingController::class, 'financialReports'])->name('financial-reports');
    Route::get('/trial-balance', [AccountingController::class, 'trialBalance'])->name('trial-balance');
    Route::get('/profit-loss', [AccountingController::class, 'profitLoss'])->name('profit-loss');
    Route::get('/balance-sheet', [AccountingController::class, 'balanceSheet'])->name('balance-sheet');
    Route::get('/vat-report', [AccountingController::class, 'vatReport'])->name('vat-report');
    Route::get('/pan-report', [AccountingController::class, 'panReport'])->name('pan-report');
    Route::get('/tax-report', [AccountingController::class, 'taxReport'])->name('tax-report');
});

// HRM Routes
Route::middleware(['auth'])->prefix('hrm')->name('hrm.')->group(function () {
    Route::get('/', [HRMController::class, 'index'])->name('index');
    Route::get('/employees', [HRMController::class, 'employees'])->name('employees');
    Route::get('/departments', [HRMController::class, 'departments'])->name('departments');
    Route::get('/designations', [HRMController::class, 'designations'])->name('designations');
    Route::get('/attendance', [HRMController::class, 'attendance'])->name('attendance');
    Route::get('/leave', [HRMController::class, 'leave'])->name('leave');
    Route::get('/shifts', [HRMController::class, 'shifts'])->name('shifts');
    Route::get('/payroll', [HRMController::class, 'payroll'])->name('payroll');
    Route::get('/salary', [HRMController::class, 'salary'])->name('salary');
    Route::get('/advance-salary', [HRMController::class, 'advanceSalary'])->name('advance-salary');
    Route::get('/incentives', [HRMController::class, 'incentives'])->name('incentives');
});

// Reports Routes
Route::middleware(['auth'])->prefix('reports')->name('reports.')->group(function () {
    Route::get('/', [ReportsController::class, 'index'])->name('index');
    Route::get('/sales-reports', [ReportsController::class, 'salesReports'])->name('sales-reports');
    Route::get('/daily-sales', [ReportsController::class, 'dailySales'])->name('daily-sales');
    Route::get('/monthly-sales', [ReportsController::class, 'monthlySales'])->name('monthly-sales');
    Route::get('/yearly-sales', [ReportsController::class, 'yearlySales'])->name('yearly-sales');
    Route::get('/item-wise-sales', [ReportsController::class, 'itemWiseSales'])->name('item-wise-sales');
    Route::get('/category-wise-sales', [ReportsController::class, 'categoryWiseSales'])->name('category-wise-sales');
    Route::get('/purchase-reports', [ReportsController::class, 'purchaseReports'])->name('purchase-reports');
    Route::get('/purchase-summary', [ReportsController::class, 'purchaseSummary'])->name('purchase-summary');
    Route::get('/supplier-wise', [ReportsController::class, 'supplierWise'])->name('supplier-wise');
    Route::get('/purchase-return', [ReportsController::class, 'purchaseReturn'])->name('purchase-return');
    Route::get('/inventory-reports', [ReportsController::class, 'inventoryReports'])->name('inventory-reports');
    Route::get('/stock-report', [ReportsController::class, 'stockReport'])->name('stock-report');
    Route::get('/stock-ledger', [ReportsController::class, 'stockLedger'])->name('stock-ledger');
    Route::get('/low-stock', [ReportsController::class, 'lowStock'])->name('low-stock');
    Route::get('/expired-items', [ReportsController::class, 'expiredItems'])->name('expired-items');
    Route::get('/wastage-report', [ReportsController::class, 'wastageReport'])->name('wastage-report');
    Route::get('/financial-reports', [ReportsController::class, 'financialReports'])->name('financial-reports');
    Route::get('/income-report', [ReportsController::class, 'incomeReport'])->name('income-report');
    Route::get('/expense-report', [ReportsController::class, 'expenseReport'])->name('expense-report');
    Route::get('/cash-flow', [ReportsController::class, 'cashFlow'])->name('cash-flow');
    Route::get('/profit-report', [ReportsController::class, 'profitReport'])->name('profit-report');
    Route::get('/vat-report', [ReportsController::class, 'vatReport'])->name('vat-report');
    Route::get('/other-reports', [ReportsController::class, 'otherReports'])->name('other-reports');
    Route::get('/customer-report', [ReportsController::class, 'customerReport'])->name('customer-report');
    Route::get('/employee-report', [ReportsController::class, 'employeeReport'])->name('employee-report');
    Route::get('/waiter-report', [ReportsController::class, 'waiterReport'])->name('waiter-report');
    Route::get('/kitchen-report', [ReportsController::class, 'kitchenReport'])->name('kitchen-report');
    Route::get('/delivery-report', [ReportsController::class, 'deliveryReport'])->name('delivery-report');
    Route::get('/reservation-report', [ReportsController::class, 'reservationReport'])->name('reservation-report');
});

// Settings Routes
Route::middleware(['auth'])->prefix('settings')->name('settings.')->group(function () {
    Route::get('/', [SettingsController::class, 'index'])->name('index');
    Route::get('/billing', [DashboardController::class, 'billing'])->name('billing');
    Route::get('/restaurant-settings', [SettingsController::class, 'restaurantSettings'])->name('restaurant-settings');
    Route::get('/general-settings', [SettingsController::class, 'generalSettings'])->name('general-settings');
    Route::get('/branch-settings', [SettingsController::class, 'branchSettings'])->name('branch-settings');
    Route::get('/tax-vat', [SettingsController::class, 'taxVat'])->name('tax-vat');
    Route::get('/invoice-settings', [SettingsController::class, 'invoiceSettings'])->name('invoice-settings');
    Route::get('/printer-settings', [SettingsController::class, 'printerSettings'])->name('printer-settings');
    Route::get('/payment-gateway', [SettingsController::class, 'paymentGateway'])->name('payment-gateway');
    Route::get('/nepali-date', [SettingsController::class, 'nepaliDate'])->name('nepali-date');
    Route::get('/language-settings', [SettingsController::class, 'languageSettings'])->name('language-settings');
    Route::get('/user-management', [SettingsController::class, 'userManagement'])->name('user-management');
    Route::get('/users', [SettingsController::class, 'users'])->name('users');
    Route::get('/roles', [SettingsController::class, 'roles'])->name('roles');
    Route::get('/permissions', [SettingsController::class, 'permissions'])->name('permissions');
    Route::get('/system', [SettingsController::class, 'system'])->name('system');
    Route::get('/backup', [SettingsController::class, 'backup'])->name('backup');
    Route::get('/restore', [SettingsController::class, 'restore'])->name('restore');
    Route::get('/activity-logs', [SettingsController::class, 'activityLogs'])->name('activity-logs');
    Route::get('/login-history', [SettingsController::class, 'loginHistory'])->name('login-history');
    Route::get('/api-keys', [SettingsController::class, 'apiKeys'])->name('api-keys');
});

// Support Routes
Route::middleware(['auth'])->prefix('support')->name('support.')->group(function () {
    Route::get('/', [SupportController::class, 'index'])->name('index');
    Route::get('/help-center', [SupportController::class, 'helpCenter'])->name('help-center');
    Route::get('/support-tickets', [SupportController::class, 'supportTickets'])->name('support-tickets');
    Route::get('/documentation', [SupportController::class, 'documentation'])->name('documentation');
    Route::get('/contact-admin', [SupportController::class, 'contactAdmin'])->name('contact-admin');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/tenants', [TenantManagementController::class, 'index'])->name('tenants.index');
    Route::get('/tenants/create', [TenantManagementController::class, 'create'])->name('tenants.create');
    Route::post('/tenants', [TenantManagementController::class, 'store'])->name('tenants.store');
    Route::get('/tenants/{id}', [TenantManagementController::class, 'show'])->name('tenants.show');
    Route::get('/tenants/{id}/edit', [TenantManagementController::class, 'edit'])->name('tenants.edit');
    Route::put('/tenants/{id}', [TenantManagementController::class, 'update'])->name('tenants.update');
    Route::delete('/tenants/{id}', [TenantManagementController::class, 'destroy'])->name('tenants.destroy');
    Route::post('/tenants/{id}/suspend', [TenantManagementController::class, 'suspend'])->name('tenants.suspend');
    Route::post('/tenants/{id}/activate', [TenantManagementController::class, 'activate'])->name('tenants.activate');
    Route::post('/tenants/{id}/login-as', [TenantManagementController::class, 'loginAs'])->name('tenants.login-as');
    
    Route::get('/subscription-plans', [SubscriptionPlanController::class, 'index'])->name('subscription-plans.index');
    Route::get('/subscription-plans/create', [SubscriptionPlanController::class, 'create'])->name('subscription-plans.create');
    Route::post('/subscription-plans', [SubscriptionPlanController::class, 'store'])->name('subscription-plans.store');
    Route::get('/subscription-plans/{id}/edit', [SubscriptionPlanController::class, 'edit'])->name('subscription-plans.edit');
    Route::put('/subscription-plans/{id}', [SubscriptionPlanController::class, 'update'])->name('subscription-plans.update');
    Route::delete('/subscription-plans/{id}', [SubscriptionPlanController::class, 'destroy'])->name('subscription-plans.destroy');
    
    Route::get('/trial-accounts', [TrialAccountController::class, 'index'])->name('trial-accounts.index');
    
    Route::get('/payment-gateways', [PaymentGatewayController::class, 'index'])->name('payment-gateways.index');
    Route::get('/payment-gateways/{gateway}/edit', [PaymentGatewayController::class, 'edit'])->name('payment-gateways.edit');
    Route::put('/payment-gateways/{gateway}', [PaymentGatewayController::class, 'update'])->name('payment-gateways.update');
});
