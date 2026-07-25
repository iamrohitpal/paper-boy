<?php

use App\Http\Controllers\AssignPaperController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DailyCollectionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExtraNewspaperController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\NewspaperController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard (accessible to all logged-in users)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Global Search
    Route::get('/search', [SearchController::class, 'search'])->name('search');

    // Newspapers
    Route::resource('newspapers', NewspaperController::class)->middleware('can:manage_newspapers');

    // Customers
    Route::middleware('can:manage_customers')->group(function () {
        Route::post('customers/leaves', [CustomerController::class, 'storeLeave'])->name('customers.leaves.store');
        Route::put('customers/leaves', [CustomerController::class, 'updateLeave'])->name('customers.leaves.update');
        Route::delete('customers/leaves', [CustomerController::class, 'destroyLeave'])->name('customers.leaves.destroy');
        Route::post('customers/{customer}/generate-bill', [CustomerController::class, 'generateBill'])->name('customers.generate-bill');
        Route::resource('customers', CustomerController::class);
        
        // Subscriptions
        Route::post('subscriptions/bulk-update', [SubscriptionController::class, 'bulkUpdate'])->name('subscriptions.bulk-update');
        Route::resource('subscriptions', SubscriptionController::class);

        // Extra Newspapers
        Route::resource('extra-newspapers', ExtraNewspaperController::class);

        // Assign Paper Quick Action
        Route::get('assign-paper', [AssignPaperController::class, 'create'])->name('assign-paper.create');
        Route::post('assign-paper', [AssignPaperController::class, 'store'])->name('assign-paper.store');

        // Invoices
        Route::post('invoices/generate', [InvoiceController::class, 'generate'])->name('invoices.generate');
        Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'downloadPdf'])->name('invoices.pdf');
        Route::resource('invoices', InvoiceController::class);
    });

    // Payments and Daily Collections
    Route::middleware('can:manage_collections')->group(function () {
        Route::get('daily-collections', [DailyCollectionController::class, 'index'])->name('daily-collections.index');
        Route::post('daily-collections', [DailyCollectionController::class, 'store'])->name('daily-collections.store');
    });

    Route::middleware('can:manage_payments')->group(function () {
        Route::resource('payments', PaymentController::class);
        // Expenses
        Route::resource('expenses', ExpenseController::class);
    });

    // Vendor Purchases
    Route::middleware('can:manage_purchases')->group(function () {
        Route::post('purchases/settle', [PurchaseController::class, 'settleMonth'])->name('purchases.settle');
        Route::resource('purchases', PurchaseController::class)->only(['index', 'create', 'store', 'destroy']);
    });

    // Reports
    Route::middleware('can:view_reports')->group(function () {
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/pdf', [ReportController::class, 'downloadPdf'])->name('reports.pdf');
    });

    // Settings
    Route::middleware('can:manage_settings')->group(function () {
        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');
    });

    // Profile (accessible to all logged-in users)
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

    // Notifications (accessible to all logged-in users)
    Route::post('notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::get('notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');

    // System (User and Role Management)
    // Handled intrinsically by controller logic and sidebar visibility (only owner_id === null)
    Route::resource('users', \App\Http\Controllers\System\UserController::class);
    Route::resource('roles', \App\Http\Controllers\System\RoleController::class);
});
