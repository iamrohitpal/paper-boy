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
use App\Http\Controllers\System\OnboardingController;
use App\Http\Controllers\System\PlanController;
use App\Http\Controllers\System\RoleController;
use App\Http\Controllers\System\TenantSwitcherController;
use App\Http\Controllers\System\UserController;
use App\Http\Controllers\WhatsAppController;
use App\Models\Plan;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    $plans = Plan::where('status', 'active')->get();

    return view('welcome', compact('plans'));
})->name('home');

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
    // Onboarding
    Route::get('/onboarding/step-1', [OnboardingController::class, 'step1'])->name('onboarding.step1');
    Route::post('/onboarding/step-1', [OnboardingController::class, 'postStep1']);

    Route::get('/onboarding/step-2', [OnboardingController::class, 'step2'])->name('onboarding.step2');
    Route::post('/onboarding/step-2', [OnboardingController::class, 'postStep2']);

    Route::get('/onboarding/step-3', [OnboardingController::class, 'step3'])->name('onboarding.step3');
    Route::post('/onboarding/step-3', [OnboardingController::class, 'postStep3']);

    Route::post('/onboarding/skip', [OnboardingController::class, 'skip'])->name('onboarding.skip');

    // Tenant Switcher (Super Admin)
    Route::post('/tenant/switch/{id}', [TenantSwitcherController::class, 'switch'])->name('tenant.switch')->middleware('role:Super Admin');
    Route::post('/tenant/clear', [TenantSwitcherController::class, 'clear'])->name('tenant.clear')->middleware('role:Super Admin');

    // Plan Management (Super Admin)
    Route::resource('/system/plans', PlanController::class)->middleware('role:Super Admin');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Global Search
    Route::get('/search', [SearchController::class, 'search'])->name('search');

    // Newspapers
    Route::middleware('role_or_permission:Super Admin|view_newspapers|create_newspapers|edit_newspapers|delete_newspapers')->group(function () {
        Route::resource('newspapers', NewspaperController::class);
    });

    // Customers
    Route::middleware('role_or_permission:Super Admin|view_customers|create_customers|edit_customers|delete_customers')->group(function () {
        Route::post('customers/leaves', [CustomerController::class, 'storeLeave'])->name('customers.leaves.store');
        Route::put('customers/leaves', [CustomerController::class, 'updateLeave'])->name('customers.leaves.update');
        Route::delete('customers/leaves', [CustomerController::class, 'destroyLeave'])->name('customers.leaves.destroy');
        Route::post('customers/bulk-actions', [CustomerController::class, 'bulkActions'])->name('customers.bulk-actions');
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
        Route::post('invoices/{invoice}/whatsapp', [InvoiceController::class, 'sendWhatsapp'])->name('invoices.whatsapp');
        Route::resource('invoices', InvoiceController::class);
    });

    // Payments and Daily Collections
    Route::middleware('role_or_permission:Super Admin|view_collections|create_collections')->group(function () {
        Route::get('daily-collections', [DailyCollectionController::class, 'index'])->name('daily-collections.index');
        Route::post('daily-collections', [DailyCollectionController::class, 'store'])->name('daily-collections.store');
    });

    Route::middleware('role_or_permission:Super Admin|view_payments|create_payments|edit_payments|delete_payments')->group(function () {
        Route::resource('payments', PaymentController::class);
    });

    Route::middleware('role_or_permission:Super Admin|view_expenses|create_expenses|edit_expenses|delete_expenses')->group(function () {
        Route::resource('expenses', ExpenseController::class);
    });

    // Vendor Purchases
    Route::middleware('role_or_permission:Super Admin|view_purchases|create_purchases|delete_purchases')->group(function () {
        Route::post('purchases/settle', [PurchaseController::class, 'settleMonth'])->name('purchases.settle');
        Route::resource('purchases', PurchaseController::class)->only(['index', 'create', 'store', 'destroy']);
    });

    // Reports
    Route::middleware('role_or_permission:Super Admin|view_reports')->group(function () {
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/pdf', [ReportController::class, 'downloadPdf'])->name('reports.pdf');
    });

    // Settings and WhatsApp
    Route::middleware('role_or_permission:Super Admin|manage_settings')->group(function () {
        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::post('settings/upgrade/{plan}', [App\Http\Controllers\System\SubscriptionController::class, 'upgrade'])->name('settings.upgrade');

        // WhatsApp Integration
        Route::get('whatsapp', [WhatsAppController::class, 'index'])->name('whatsapp.index');
        Route::get('whatsapp/status', [WhatsAppController::class, 'status'])->name('whatsapp.status');
        Route::post('whatsapp/pair', [WhatsAppController::class, 'requestPairingCode'])->name('whatsapp.pair');
        Route::post('whatsapp/logout', [WhatsAppController::class, 'logout'])->name('whatsapp.logout');
    });

    // Profile (accessible to all logged-in users)
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

    // Notifications (accessible to all logged-in users)
    Route::post('notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::get('notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');

    // System (User and Role Management) - SaaS Super Admin Only
    Route::middleware('role:Super Admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
    });
});
