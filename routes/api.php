<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// PWA FCM Token Registration
Route::post('/fcm-token', [AuthController::class, 'registerFcmToken'])->middleware('auth');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);

    // Super Admin Routes
    Route::middleware('super_admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', 'App\Http\Controllers\Api\Admin\DashboardController@index');
        Route::get('/revenue', 'App\Http\Controllers\Api\Admin\DashboardController@revenue');
        Route::get('/tenants', 'App\Http\Controllers\Api\Admin\DashboardController@tenants');
        
        // Tenants Management
        Route::prefix('tenants')->group(function () {
            Route::get('/', 'App\Http\Controllers\Api\Admin\TenantController@index');
            Route::post('/', 'App\Http\Controllers\Api\Admin\TenantController@store');
            Route::get('/{id}', 'App\Http\Controllers\Api\Admin\TenantController@show');
            Route::put('/{id}', 'App\Http\Controllers\Api\Admin\TenantController@update');
            Route::delete('/{id}', 'App\Http\Controllers\Api\Admin\TenantController@destroy');
            Route::post('/{id}/suspend', 'App\Http\Controllers\Api\Admin\TenantController@suspend');
            Route::post('/{id}/activate', 'App\Http\Controllers\Api\Admin\TenantController@activate');
            Route::post('/{id}/upgrade', 'App\Http\Controllers\Api\Admin\TenantController@upgradeSubscription');
            Route::get('/dashboard', 'App\Http\Controllers\Api\Admin\TenantController@dashboard');
        });

        // Subscription Plans
        Route::prefix('subscription-plans')->group(function () {
            Route::get('/', 'App\Http\Controllers\Api\Admin\SubscriptionPlanController@index');
            Route::post('/', 'App\Http\Controllers\Api\Admin\SubscriptionPlanController@store');
            Route::get('/{id}', 'App\Http\Controllers\Api\Admin\SubscriptionPlanController@show');
            Route::put('/{id}', 'App\Http\Controllers\Api\Admin\SubscriptionPlanController@update');
            Route::delete('/{id}', 'App\Http\Controllers\Api\Admin\SubscriptionPlanController@destroy');
        });
    });

    // Owner Routes
    Route::middleware('owner')->prefix('owner')->group(function () {
        Route::get('/dashboard', 'App\Http\Controllers\Api\Owner\DashboardController@index');
        
        // Restaurants
        Route::prefix('restaurants')->group(function () {
            Route::get('/', 'App\Http\Controllers\Api\Owner\RestaurantController@index');
            Route::post('/', 'App\Http\Controllers\Api\Owner\RestaurantController@store');
            Route::get('/{id}', 'App\Http\Controllers\Api\Owner\RestaurantController@show');
            Route::put('/{id}', 'App\Http\Controllers\Api\Owner\RestaurantController@update');
            Route::delete('/{id}', 'App\Http\Controllers\Api\Owner\RestaurantController@destroy');
        });

        // Branches
        Route::prefix('branches')->group(function () {
            Route::get('/', 'App\Http\Controllers\Api\Owner\BranchController@index');
            Route::post('/', 'App\Http\Controllers\Api\Owner\BranchController@store');
            Route::get('/{id}', 'App\Http\Controllers\Api\Owner\BranchController@show');
            Route::put('/{id}', 'App\Http\Controllers\Api\Owner\BranchController@update');
            Route::delete('/{id}', 'App\Http\Controllers\Api\Owner\BranchController@destroy');
        });
    });

    // Menu Management
    Route::prefix('menu')->group(function () {
        Route::prefix('categories')->group(function () {
            Route::get('/', 'App\Http\Controllers\Api\Menu\CategoryController@index');
            Route::post('/', 'App\Http\Controllers\Api\Menu\CategoryController@store');
            Route::get('/{id}', 'App\Http\Controllers\Api\Menu\CategoryController@show');
            Route::put('/{id}', 'App\Http\Controllers\Api\Menu\CategoryController@update');
            Route::delete('/{id}', 'App\Http\Controllers\Api\Menu\CategoryController@destroy');
        });

        Route::prefix('items')->group(function () {
            Route::get('/', 'App\Http\Controllers\Api\Menu\ItemController@index');
            Route::post('/', 'App\Http\Controllers\Api\Menu\ItemController@store');
            Route::get('/{id}', 'App\Http\Controllers\Api\Menu\ItemController@show');
            Route::put('/{id}', 'App\Http\Controllers\Api\Menu\ItemController@update');
            Route::delete('/{id}', 'App\Http\Controllers\Api\Menu\ItemController@destroy');
        });
    });

    // Orders
    Route::prefix('orders')->group(function () {
        Route::get('/', 'App\Http\Controllers\Api\OrderController@index');
        Route::post('/', 'App\Http\Controllers\Api\OrderController@store');
        Route::get('/{id}', 'App\Http\Controllers\Api\OrderController@show');
        Route::put('/{id}', 'App\Http\Controllers\Api\OrderController@update');
        Route::delete('/{id}', 'App\Http\Controllers\Api\OrderController@destroy');
        Route::post('/{id}/cancel', 'App\Http\Controllers\Api\OrderController@cancel');
        Route::post('/{id}/complete', 'App\Http\Controllers\Api\OrderController@complete');
        Route::post('/{id}/payment', 'App\Http\Controllers\Api\OrderController@addPayment');
    });

    // Tables
    Route::prefix('tables')->group(function () {
        Route::get('/', 'App\Http\Controllers\Api\TableController@index');
        Route::post('/', 'App\Http\Controllers\Api\TableController@store');
        Route::get('/{id}', 'App\Http\Controllers\Api\TableController@show');
        Route::put('/{id}', 'App\Http\Controllers\Api\TableController@update');
        Route::delete('/{id}', 'App\Http\Controllers\Api\TableController@destroy');
        Route::post('/{id}/mark-available', 'App\Http\Controllers\Api\TableController@markAvailable');
        Route::post('/{id}/mark-occupied', 'App\Http\Controllers\Api\TableController@markOccupied');
    });

    // Kitchen Display
    Route::prefix('kitchen')->group(function () {
        Route::get('/orders', 'App\Http\Controllers\Api\KitchenController@index');
        Route::put('/orders/{id}', 'App\Http\Controllers\Api\KitchenController@update');
        Route::post('/orders/{id}/start', 'App\Http\Controllers\Api\KitchenController@startPreparing');
        Route::post('/orders/{id}/ready', 'App\Http\Controllers\Api\KitchenController@markReady');
        Route::post('/orders/{id}/served', 'App\Http\Controllers\Api\KitchenController@markServed');
    });

    // Inventory
    Route::prefix('inventory')->group(function () {
        Route::prefix('items')->group(function () {
            Route::get('/', 'App\Http\Controllers\Api\Inventory\ItemController@index');
            Route::post('/', 'App\Http\Controllers\Api\Inventory\ItemController@store');
            Route::get('/{id}', 'App\Http\Controllers\Api\Inventory\ItemController@show');
            Route::put('/{id}', 'App\Http\Controllers\Api\Inventory\ItemController@update');
            Route::delete('/{id}', 'App\Http\Controllers\Api\Inventory\ItemController@destroy');
        });

        Route::prefix('purchases')->group(function () {
            Route::get('/', 'App\Http\Controllers\Api\Inventory\PurchaseController@index');
            Route::post('/', 'App\Http\Controllers\Api\Inventory\PurchaseController@store');
            Route::get('/{id}', 'App\Http\Controllers\Api\Inventory\PurchaseController@show');
            Route::put('/{id}', 'App\Http\Controllers\Api\Inventory\PurchaseController@update');
        });

        Route::prefix('suppliers')->group(function () {
            Route::get('/', 'App\Http\Controllers\Api\Inventory\SupplierController@index');
            Route::post('/', 'App\Http\Controllers\Api\Inventory\SupplierController@store');
            Route::get('/{id}', 'App\Http\Controllers\Api\Inventory\SupplierController@show');
            Route::put('/{id}', 'App\Http\Controllers\Api\Inventory\SupplierController@update');
            Route::delete('/{id}', 'App\Http\Controllers\Api\Inventory\SupplierController@destroy');
        });
    });

    // Accounting
    Route::prefix('accounting')->group(function () {
        Route::prefix('fiscal-years')->group(function () {
            Route::get('/', 'App\Http\Controllers\Api\Accounting\FiscalYearController@index');
            Route::post('/', 'App\Http\Controllers\Api\Accounting\FiscalYearController@store');
            Route::get('/{id}', 'App\Http\Controllers\Api\Accounting\FiscalYearController@show');
            Route::put('/{id}', 'App\Http\Controllers\Api\Accounting\FiscalYearController@update');
            Route::get('/current', 'App\Http\Controllers\Api\Accounting\FiscalYearController@getCurrent');
            Route::post('/{id}/close', 'App\Http\Controllers\Api\Accounting\FiscalYearController@close');
        });

        Route::prefix('chart-of-accounts')->group(function () {
            Route::get('/', 'App\Http\Controllers\Api\Accounting\ChartOfAccountsController@index');
            Route::post('/', 'App\Http\Controllers\Api\Accounting\ChartOfAccountsController@store');
            Route::get('/{id}', 'App\Http\Controllers\Api\Accounting\ChartOfAccountsController@show');
            Route::put('/{id}', 'App\Http\Controllers\Api\Accounting\ChartOfAccountsController@update');
            Route::get('/tree', 'App\Http\Controllers\Api\Accounting\ChartOfAccountsController@getTree');
        });

        Route::prefix('journals')->group(function () {
            Route::get('/', 'App\Http\Controllers\Api\Accounting\JournalController@index');
            Route::post('/', 'App\Http\Controllers\Api\Accounting\JournalController@store');
            Route::get('/{id}', 'App\Http\Controllers\Api\Accounting\JournalController@show');
            Route::get('/trial-balance', 'App\Http\Controllers\Api\Accounting\JournalController@trialBalance');
        });
    });

    // Reports
    Route::prefix('reports')->group(function () {
        Route::get('/sales', 'App\Http\Controllers\Api\ReportController@salesReport');
        Route::get('/purchase', 'App\Http\Controllers\Api\ReportController@purchaseReport');
        Route::get('/inventory', 'App\Http\Controllers\Api\ReportController@inventoryReport');
        Route::get('/profit-loss', 'App\Http\Controllers\Api\ReportController@profitLossReport');
        Route::get('/daily', 'App\Http\Controllers\Api\ReportController@dailyReport');
        Route::get('/monthly', 'App\Http\Controllers\Api\ReportController@monthlyReport');
    });

    // Nepali Date
    Route::prefix('nepali-date')->group(function () {
        Route::post('/to-bs', 'App\Http\Controllers\Api\NepaliDateController@convertToBs');
        Route::post('/to-ad', 'App\Http\Controllers\Api\NepaliDateController@convertToAd');
        Route::get('/fiscal-year', 'App\Http\Controllers\Api\NepaliDateController@getFiscalYear');
        Route::get('/current-fiscal-year', 'App\Http\Controllers\Api\NepaliDateController@getCurrentFiscalYear');
        Route::post('/calculate-age', 'App\Http\Controllers\Api\NepaliDateController@calculateAge');
        Route::post('/format', 'App\Http\Controllers\Api\NepaliDateController@formatDate');
        Route::post('/convert-digits', 'App\Http\Controllers\Api\NepaliDateController@convertDigits');
    });

    // Payment
    Route::prefix('payment')->group(function () {
        Route::post('/initiate', 'App\Http\Controllers\Api\PaymentController@initiate');
        Route::post('/verify', 'App\Http\Controllers\Api\PaymentController@verify');
        Route::post('/refund', 'App\Http\Controllers\Api\PaymentController@refund');
        Route::get('/status', 'App\Http\Controllers\Api\PaymentController@status');
        Route::get('/gateways', 'App\Http\Controllers\Api\PaymentController@gateways');
    });

    // HRM
    Route::prefix('hrm')->group(function () {
        Route::prefix('employees')->group(function () {
            Route::get('/', 'App\Http\Controllers\Api\Hrm\EmployeeController@index');
            Route::post('/', 'App\Http\Controllers\Api\Hrm\EmployeeController@store');
            Route::get('/{id}', 'App\Http\Controllers\Api\Hrm\EmployeeController@show');
            Route::put('/{id}', 'App\Http\Controllers\Api\Hrm\EmployeeController@update');
            Route::delete('/{id}', 'App\Http\Controllers\Api\Hrm\EmployeeController@destroy');
        });

        Route::prefix('attendance')->group(function () {
            Route::get('/', 'App\Http\Controllers\Api\Hrm\AttendanceController@index');
            Route::post('/', 'App\Http\Controllers\Api\Hrm\AttendanceController@store');
            Route::put('/{id}', 'App\Http\Controllers\Api\Hrm\AttendanceController@update');
            Route::post('/check-in', 'App\Http\Controllers\Api\Hrm\AttendanceController@checkIn');
            Route::post('/{id}/check-out', 'App\Http\Controllers\Api\Hrm\AttendanceController@checkOut');
            Route::get('/today', 'App\Http\Controllers\Api\Hrm\AttendanceController@today');
        });

        Route::prefix('payroll')->group(function () {
            Route::get('/', 'App\Http\Controllers\Api\Hrm\PayrollController@index');
            Route::post('/', 'App\Http\Controllers\Api\Hrm\PayrollController@store');
            Route::get('/{id}', 'App\Http\Controllers\Api\Hrm\PayrollController@show');
            Route::put('/{id}', 'App\Http\Controllers\Api\Hrm\PayrollController@update');
            Route::post('/{id}/mark-paid', 'App\Http\Controllers\Api\Hrm\PayrollController@markPaid');
            Route::post('/bulk', 'App\Http\Controllers\Api\Hrm\PayrollController@generateBulk');
        });
    });

    // CRM
    Route::prefix('crm')->group(function () {
        Route::prefix('customers')->group(function () {
            Route::get('/', 'App\Http\Controllers\Api\Crm\CustomerController@index');
            Route::post('/', 'App\Http\Controllers\Api\Crm\CustomerController@store');
            Route::get('/{id}', 'App\Http\Controllers\Api\Crm\CustomerController@show');
            Route::put('/{id}', 'App\Http\Controllers\Api\Crm\CustomerController@update');
            Route::delete('/{id}', 'App\Http\Controllers\Api\Crm\CustomerController@destroy');
            Route::post('/{id}/loyalty-points', 'App\Http\Controllers\Api\Crm\CustomerController@addLoyaltyPoints');
            Route::post('/{id}/redeem-points', 'App\Http\Controllers\Api\Crm\CustomerController@redeemLoyaltyPoints');
        });

        Route::prefix('reviews')->group(function () {
            Route::get('/', 'App\Http\Controllers\Api\Crm\ReviewController@index');
            Route::post('/', 'App\Http\Controllers\Api\Crm\ReviewController@store');
            Route::get('/{id}', 'App\Http\Controllers\Api\Crm\ReviewController@show');
            Route::put('/{id}', 'App\Http\Controllers\Api\Crm\ReviewController@update');
            Route::post('/{id}/approve', 'App\Http\Controllers\Api\Crm\ReviewController@approve');
            Route::post('/{id}/reject', 'App\Http\Controllers\Api\Crm\ReviewController@reject');
            Route::get('/average-rating', 'App\Http\Controllers\Api\Crm\ReviewController@getAverageRating');
        });

        Route::prefix('feedback')->group(function () {
            Route::get('/', 'App\Http\Controllers\Api\Crm\FeedbackController@index');
            Route::post('/', 'App\Http\Controllers\Api\Crm\FeedbackController@store');
            Route::get('/{id}', 'App\Http\Controllers\Api\Crm\FeedbackController@show');
            Route::put('/{id}', 'App\Http\Controllers\Api\Crm\FeedbackController@update');
            Route::delete('/{id}', 'App\Http\Controllers\Api\Crm\FeedbackController@destroy');
            Route::post('/{id}/resolve', 'App\Http\Controllers\Api\Crm\FeedbackController@resolve');
            Route::get('/stats', 'App\Http\Controllers\Api\Crm\FeedbackController@getStats');
        });
    });

    // Security
    Route::prefix('security')->group(function () {
        Route::post('/2fa/enable', 'App\Http\Controllers\Api\Security\TwoFactorController@enable');
        Route::post('/2fa/disable', 'App\Http\Controllers\Api\Security\TwoFactorController@disable');
        Route::post('/2fa/verify', 'App\Http\Controllers\Api\Security\TwoFactorController@verify');
    });

    // Orders Dashboard
    Route::get('/orders/dashboard', 'App\Http\Controllers\Api\OrderController@dashboard');

    // Kitchen Dashboard
    Route::get('/kitchen/dashboard', 'App\Http\Controllers\Api\KitchenController@dashboard');

    // Inventory Low Stock
    Route::get('/inventory/items/low-stock', 'App\Http\Controllers\Api\Inventory\ItemController@lowStock');
});

// Public Routes for QR Menu
Route::get('/public/menu/{restaurant_id}', 'App\Http\Controllers\Api\Public\MenuController@show');
Route::get('/public/menu/{restaurant_id}/qr/{table_id}', 'App\Http\Controllers\Api\Public\MenuController@showByQR');
