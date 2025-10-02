<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\SlideController as AdminSlideController;
use App\Http\Controllers\Admin\FooterSettingController;
use App\Http\Controllers\Admin\HomeSettingController;
use App\Http\Controllers\Admin\AboutSettingController;
use App\Http\Controllers\Admin\ContactSettingController;
use App\Http\Controllers\Admin\WebsiteSettingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\Admin\CustomerRequestController as AdminCustomerRequestController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// About page
Route::get('/about', [HomeController::class, 'about'])->name('about');

// Packages page
Route::get('/packages', [HomeController::class, 'packages'])->name('packages');
Route::get('/packages/{slug}', [HomeController::class, 'showPackage'])->name('packages.show');

// Cart & Checkout
Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout.show');
Route::post('/checkout', [CartController::class, 'place'])->name('checkout.place');

// Auth pages (simple static views for now)
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Admin login (separate URL with dedicated design)
Route::get('/admin/login', function () {
    return view('auth.admin-login');
})->name('admin.login');

// Services pages
Route::get('/services', [\App\Http\Controllers\Frontend\ServiceController::class, 'index'])->name('services.index');
Route::get('/services/category/{category}', [\App\Http\Controllers\Frontend\ServiceController::class, 'index'])->name('services.category');
Route::get('/services/{slug}', [\App\Http\Controllers\Frontend\ServiceController::class, 'show'])->name('services.show');

// Contact page
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [ContactController::class, 'submitForm'])->name('contact.submit');

// Admin routes (protected)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('requests', [AdminCustomerRequestController::class, 'index'])->name('requests.index');
    Route::get('requests/create', [AdminCustomerRequestController::class, 'create'])->name('requests.create');
    Route::post('requests', [AdminCustomerRequestController::class, 'store'])->name('requests.store');
    Route::patch('requests/{customerRequest}/status', [AdminCustomerRequestController::class, 'updateStatus'])->name('requests.status');
    Route::get('requests/{customerRequest}', [AdminCustomerRequestController::class, 'show'])->name('requests.show');
    Route::get('requests/{customerRequest}/edit', [AdminCustomerRequestController::class, 'edit'])->name('requests.edit');
    Route::put('requests/{customerRequest}', [AdminCustomerRequestController::class, 'update'])->name('requests.update');
    Route::post('requests/{customerRequest}/convert', [AdminCustomerRequestController::class, 'convertToServiceOrder'])->name('requests.convert');
    Route::resource('slides', AdminSlideController::class);
    Route::get('settings/footer', [FooterSettingController::class, 'edit'])->name('settings.footer.edit');
    Route::put('settings/footer', [FooterSettingController::class, 'update'])->name('settings.footer.update');
    Route::get('settings/home', [HomeSettingController::class, 'edit'])->name('settings.home.edit');
    Route::put('settings/home', [HomeSettingController::class, 'update'])->name('settings.home.update');
    Route::get('settings/about', [AboutSettingController::class, 'edit'])->name('settings.about.edit');
    Route::put('settings/about', [AboutSettingController::class, 'update'])->name('settings.about.update');
    Route::get('settings/contact', [ContactSettingController::class, 'edit'])->name('settings.contact.edit');
    Route::put('settings/contact', [ContactSettingController::class, 'update'])->name('settings.contact.update');
    Route::get('settings/website', [WebsiteSettingController::class, 'edit'])->name('settings.website');
    Route::put('settings/website', [WebsiteSettingController::class, 'update'])->name('settings.website.update');
    
    // Customer Messages
    Route::get('messages', [ContactController::class, 'index'])->name('messages.index');
    Route::get('messages/{message}', [ContactController::class, 'show'])->name('messages.show');
    Route::delete('messages/{message}', [ContactController::class, 'destroy'])->name('messages.destroy');
    
    // Notifications
    Route::get('notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('notifications/api', [\App\Http\Controllers\Admin\NotificationController::class, 'getNotifications'])->name('notifications.api');
    Route::post('notifications/{id}/read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('notifications/read-all', [\App\Http\Controllers\Admin\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    
    // Profile Management
    Route::get('profile', [\App\Http\Controllers\Admin\ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
    Route::get('password', [\App\Http\Controllers\Admin\ProfileController::class, 'showPasswordForm'])->name('profile.password');
    Route::put('password', [\App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('profile.password.update');
    
});

// Service Categories and Services Management (admin prefix but no name prefix)
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Service Categories Management
    Route::resource('service-categories', \App\Http\Controllers\Admin\ServiceCategoryController::class);
    
    // Services Management
    Route::resource('services', \App\Http\Controllers\Admin\ServiceController::class)->names('admin.services');
    // Add custom slug-based routes for services
    Route::get('services/s/{slug}', [\App\Http\Controllers\Admin\ServiceController::class, 'showBySlug'])->name('admin.services.slug');
    
    // Package Categories Management
    Route::resource('package-categories', \App\Http\Controllers\Admin\PackageCategoryController::class)->names('admin.package-categories');
    
    // Website Development Packages
    Route::get('packages/website-development', [\App\Http\Controllers\Admin\PackageController::class, 'websiteDevelopment'])
        ->name('admin.packages.website-development');
    
    // Digital Marketing Packages
    Route::get('packages/digital-marketing', [\App\Http\Controllers\Admin\PackageController::class, 'digitalMarketing'])
        ->name('admin.packages.digital-marketing');
    
    // Packages Management
    Route::resource('packages', \App\Http\Controllers\Admin\PackageController::class)->names('admin.packages');
    // Add custom slug-based routes for packages
    Route::get('packages/p/{slug}', [\App\Http\Controllers\Admin\PackageController::class, 'showBySlug'])->name('admin.packages.slug');
    
    // Package Orders Management
    Route::get('package-orders', [\App\Http\Controllers\Admin\PackageOrderController::class, 'index'])
        ->name('admin.package-orders.index');
    Route::get('package-orders/{packageOrder}', [\App\Http\Controllers\Admin\PackageOrderController::class, 'show'])
        ->name('admin.package-orders.show');
    Route::get('package-orders/{packageOrder}/edit', [\App\Http\Controllers\Admin\PackageOrderController::class, 'edit'])
        ->name('admin.package-orders.edit');
    Route::put('package-orders/{packageOrder}', [\App\Http\Controllers\Admin\PackageOrderController::class, 'update'])
        ->name('admin.package-orders.update');
    Route::patch('package-orders/{packageOrder}/status', [\App\Http\Controllers\Admin\PackageOrderController::class, 'updateStatus'])
        ->name('admin.package-orders.update-status');
    Route::post('package-orders/{packageOrder}/accept', [\App\Http\Controllers\Admin\PackageOrderController::class, 'accept'])
        ->name('admin.package-orders.accept');
    Route::post('package-orders/{packageOrder}/payment', [\App\Http\Controllers\Admin\PackageOrderController::class, 'processPayment'])
        ->name('admin.package-orders.process-payment');
    Route::post('package-orders/{packageOrder}/complete', [\App\Http\Controllers\Admin\PackageOrderController::class, 'markCompleted'])
        ->name('admin.package-orders.complete');
    
    // Service Orders Management
    Route::get('service-orders', [\App\Http\Controllers\Admin\ServiceOrderController::class, 'index'])
        ->name('admin.service-orders.index');
    Route::get('service-orders/{serviceOrder}', [\App\Http\Controllers\Admin\ServiceOrderController::class, 'show'])
        ->name('admin.service-orders.show');
    Route::get('service-orders/{serviceOrder}/edit', [\App\Http\Controllers\Admin\ServiceOrderController::class, 'edit'])
        ->name('admin.service-orders.edit');
    Route::put('service-orders/{serviceOrder}', [\App\Http\Controllers\Admin\ServiceOrderController::class, 'update'])
        ->name('admin.service-orders.update');
    Route::patch('service-orders/{serviceOrder}/status', [\App\Http\Controllers\Admin\ServiceOrderController::class, 'updateStatus'])
        ->name('admin.service-orders.update-status');
    Route::post('service-orders/{serviceOrder}/accept', [\App\Http\Controllers\Admin\ServiceOrderController::class, 'accept'])
        ->name('admin.service-orders.accept');
    Route::post('service-orders/{serviceOrder}/payment', [\App\Http\Controllers\Admin\ServiceOrderController::class, 'processPayment'])
        ->name('admin.service-orders.process-payment');
    Route::post('service-orders/{serviceOrder}/complete', [\App\Http\Controllers\Admin\ServiceOrderController::class, 'markCompleted'])
        ->name('admin.service-orders.complete');
        
    // Transactions Management
    Route::get('transactions', [\App\Http\Controllers\Admin\TransactionController::class, 'index'])
        ->name('transactions.index');
    Route::get('transactions/{transaction}', [\App\Http\Controllers\Admin\TransactionController::class, 'show'])
        ->name('transactions.show');
        
    // Manual Payments Management
    Route::get('manual-payments', [\App\Http\Controllers\Admin\ManualPaymentController::class, 'index'])
        ->name('admin.manual-payments.index');
    Route::get('manual-payments/{manualPayment}', [\App\Http\Controllers\Admin\ManualPaymentController::class, 'show'])
        ->name('admin.manual-payments.show');
    Route::post('manual-payments/{manualPayment}/approve', [\App\Http\Controllers\Admin\ManualPaymentController::class, 'approve'])
        ->name('admin.manual-payments.approve');
    Route::post('manual-payments/{manualPayment}/reject', [\App\Http\Controllers\Admin\ManualPaymentController::class, 'reject'])
        ->name('admin.manual-payments.reject');
        
    // Customer Management
    Route::resource('customers', \App\Http\Controllers\Admin\CustomerController::class)->names([
        'index' => 'admin.customers.index',
        'create' => 'admin.customers.create',
        'store' => 'admin.customers.store',
        'show' => 'admin.customers.show',
        'edit' => 'admin.customers.edit',
        'update' => 'admin.customers.update',
        'destroy' => 'admin.customers.destroy',
    ]);
    Route::patch('customers/{customer}/status', [\App\Http\Controllers\Admin\CustomerController::class, 'updateStatus'])
        ->name('admin.customers.update-status');
});

// Customer dashboard (protected)
Route::middleware('auth')->group(function(){
    Route::get('/customer', [\App\Http\Controllers\Customer\DashboardController::class, 'index'])->name('customer.dashboard');

    // Customer Requests
    Route::get('/customer/requests', [RequestController::class, 'index'])->name('customer.requests.index');
    
    // Customer Orders
    Route::get('/customer/orders', [\App\Http\Controllers\Customer\OrderController::class, 'index'])->name('customer.orders.index');
    Route::get('/customer/orders/{order}', [\App\Http\Controllers\Customer\OrderController::class, 'show'])->name('customer.orders.show');
    Route::post('/customer/orders/{order}/payment', [\App\Http\Controllers\Customer\OrderController::class, 'processPayment'])->name('customer.orders.process-payment');
    
    // Customer Service Orders
    Route::get('/customer/service-orders', [\App\Http\Controllers\Customer\ServiceOrderController::class, 'index'])->name('customer.service-orders.index');
    Route::get('/customer/service-orders/{serviceOrder}', [\App\Http\Controllers\Customer\ServiceOrderController::class, 'show'])->name('customer.service-orders.show');
    Route::post('/customer/service-orders/{serviceOrder}/payment', [\App\Http\Controllers\Customer\ServiceOrderController::class, 'processPayment'])->name('customer.service-orders.process-payment');
    Route::get('/customer/requests/create', [RequestController::class, 'create'])->name('customer.requests.create');
    Route::post('/customer/requests', [RequestController::class, 'store'])->name('customer.requests.store');
    Route::get('/customer/requests/{customerRequest}', [RequestController::class, 'show'])->name('customer.requests.show');
    Route::get('/customer/requests/{customerRequest}/edit', [RequestController::class, 'edit'])->name('customer.requests.edit');
    Route::put('/customer/requests/{customerRequest}', [RequestController::class, 'update'])->name('customer.requests.update');
    Route::delete('/customer/requests/{customerRequest}', [RequestController::class, 'destroy'])->name('customer.requests.destroy');

    // Quick Request entry: after login, redirect back to home with flag to open modal
    Route::get('/quick-request', function(){
        return redirect(url('/').'?qr=1#quick-request');
    })->name('quick-request');
    
    // Profile Management
    Route::get('/profile', [\App\Http\Controllers\Customer\ProfileController::class, 'show'])->name('customer.profile.show');
    Route::put('/profile', [\App\Http\Controllers\Customer\ProfileController::class, 'update'])->name('customer.profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\Customer\ProfileController::class, 'updatePassword'])->name('customer.profile.password.update');
    Route::post('/profile/image', [\App\Http\Controllers\Customer\ProfileController::class, 'updateImage'])->name('customer.profile.image.update');
    Route::delete('/profile/image', [\App\Http\Controllers\Customer\ProfileController::class, 'deleteImage'])->name('customer.profile.image.delete');
    
    // Fund Management
    Route::get('/fund', [\App\Http\Controllers\Customer\FundController::class, 'index'])->name('customer.fund.index');
    Route::get('/fund/create', [\App\Http\Controllers\Customer\FundController::class, 'create'])->name('customer.fund.create');
    Route::post('/fund', [\App\Http\Controllers\Customer\FundController::class, 'store'])->name('customer.fund.store');
    Route::get('/fund/{fundRequest}', [\App\Http\Controllers\Customer\FundController::class, 'show'])->name('customer.fund.show');
    Route::get('/fund/{fundRequest}/ssl-payment', [\App\Http\Controllers\Customer\FundController::class, 'sslPayment'])->name('customer.fund.ssl-payment');
    Route::post('/fund/{fundRequest}/ssl-success', [\App\Http\Controllers\Customer\FundController::class, 'sslSuccess'])->name('customer.fund.ssl-success');
      Route::post('/fund/{fundRequest}/ssl-fail', [\App\Http\Controllers\Customer\FundController::class, 'sslFail'])->name('customer.fund.ssl-fail');
      // Allow GET as well to avoid 405 from gateways that use GET
      Route::get('/fund/{fundRequest}/ssl-success', [\App\Http\Controllers\Customer\FundController::class, 'sslSuccess']);
      Route::get('/fund/{fundRequest}/ssl-fail', [\App\Http\Controllers\Customer\FundController::class, 'sslFail']);
      
      // Custom Service Management
      Route::get('/custom-service', [\App\Http\Controllers\Customer\CustomServiceController::class, 'index'])->name('customer.custom-service.index');
      Route::get('/custom-service/create', [\App\Http\Controllers\Customer\CustomServiceController::class, 'create'])->name('customer.custom-service.create');
      Route::post('/custom-service', [\App\Http\Controllers\Customer\CustomServiceController::class, 'store'])->name('customer.custom-service.store');
      Route::get('/custom-service/{customServiceRequest}', [\App\Http\Controllers\Customer\CustomServiceController::class, 'show'])->name('customer.custom-service.show');
      Route::get('/custom-service/{customServiceRequest}/ssl-payment', [\App\Http\Controllers\Customer\CustomServiceController::class, 'sslPayment'])->name('customer.custom-service.ssl-payment');
      Route::post('/custom-service/{customServiceRequest}/ssl-success', [\App\Http\Controllers\Customer\CustomServiceController::class, 'sslSuccess'])->name('customer.custom-service.ssl-success');
       Route::post('/custom-service/{customServiceRequest}/ssl-fail', [\App\Http\Controllers\Customer\CustomServiceController::class, 'sslFail'])->name('customer.custom-service.ssl-fail');
       // Allow GET as well to avoid 405
       Route::get('/custom-service/{customServiceRequest}/ssl-success', [\App\Http\Controllers\Customer\CustomServiceController::class, 'sslSuccess']);
       Route::get('/custom-service/{customServiceRequest}/ssl-fail', [\App\Http\Controllers\Customer\CustomServiceController::class, 'sslFail']);
       
       // Payment Routes
       Route::get('/payment/{type}/{id}/options', [\App\Http\Controllers\Customer\PaymentController::class, 'showPaymentOptions'])->name('customer.payment.options');
       Route::match(['get', 'post'], '/payment/{type}/{id}/ssl', [\App\Http\Controllers\Customer\PaymentController::class, 'processSSLPayment'])->name('customer.payment.ssl');
       Route::get('/payment/{type}/{id}/manual', [\App\Http\Controllers\Customer\PaymentController::class, 'showManualPaymentForm'])->name('customer.payment.manual');
       Route::post('/payment/{type}/{id}/manual', [\App\Http\Controllers\Customer\PaymentController::class, 'processManualPayment'])->name('customer.payment.manual.submit');
   });

// SSL Commerz callback routes (outside of any middleware group to avoid CSRF issues)
Route::match(['get','post'], '/customer/payment/ssl/success/{type}/{id}', [\App\Http\Controllers\Customer\PaymentController::class, 'sslSuccess'])
    ->name('customer.payment.ssl.success');
Route::match(['get','post'], '/customer/payment/ssl/fail/{type}/{id}', [\App\Http\Controllers\Customer\PaymentController::class, 'sslFail'])
    ->name('customer.payment.ssl.fail');
Route::match(['get','post'], '/customer/payment/ssl/cancel/{type}/{id}', [\App\Http\Controllers\Customer\PaymentController::class, 'sslCancel'])
    ->name('customer.payment.ssl.cancel');
Route::match(['get','post'], '/customer/payment/ssl/ipn', [\App\Http\Controllers\Customer\PaymentController::class, 'sslIpn'])
    ->name('customer.payment.ssl.ipn');

// Payment success page (no auth required)
Route::get('/payment/success', [\App\Http\Controllers\PaymentSuccessController::class, 'show'])
    ->name('payment.success.page');
Route::get('/payment/success/redirect', [\App\Http\Controllers\PaymentSuccessController::class, 'redirectToDashboard'])
    ->name('payment.success.redirect');

// Admin Fund Request Management Routes
 Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
     Route::get('/fund-requests', [\App\Http\Controllers\Admin\FundRequestController::class, 'index'])->name('admin.fund-requests.index');
     Route::get('/fund-requests/{fundRequest}', [\App\Http\Controllers\Admin\FundRequestController::class, 'show'])->name('admin.fund-requests.show');
     Route::get('/fund-requests/{fundRequest}/edit', [\App\Http\Controllers\Admin\FundRequestController::class, 'edit'])->name('admin.fund-requests.edit');
     Route::put('/fund-requests/{fundRequest}', [\App\Http\Controllers\Admin\FundRequestController::class, 'update'])->name('admin.fund-requests.update');
     Route::post('/fund-requests/{fundRequest}/approve', [\App\Http\Controllers\Admin\FundRequestController::class, 'approve'])->name('admin.fund-requests.approve');
     Route::post('/fund-requests/{fundRequest}/reject', [\App\Http\Controllers\Admin\FundRequestController::class, 'reject'])->name('admin.fund-requests.reject');
     Route::get('/fund-requests-stats', [\App\Http\Controllers\Admin\FundRequestController::class, 'statistics'])->name('admin.fund-requests.statistics');
     
     // Admin Custom Service Request Management Routes
     Route::get('/custom-service-requests', [\App\Http\Controllers\Admin\CustomServiceRequestController::class, 'index'])->name('admin.custom-service-requests.index');
     Route::get('/custom-service-requests/{customServiceRequest}', [\App\Http\Controllers\Admin\CustomServiceRequestController::class, 'show'])->name('admin.custom-service-requests.show');
     Route::post('/custom-service-requests/{customServiceRequest}/update-status', [\App\Http\Controllers\Admin\CustomServiceRequestController::class, 'updateStatus'])->name('admin.custom-service-requests.update-status');
     Route::post('/custom-service-requests/{customServiceRequest}/assign', [\App\Http\Controllers\Admin\CustomServiceRequestController::class, 'assign'])->name('admin.custom-service-requests.assign');
     Route::get('/custom-service-requests/stats', [\App\Http\Controllers\Admin\CustomServiceRequestController::class, 'getStats'])->name('admin.custom-service-requests.stats');
     
    // Admin Transaction Routes
   Route::get('/transactions', [\App\Http\Controllers\Admin\TransactionController::class, 'index'])->name('admin.transactions.index');
   Route::get('/transactions/{transaction}', [\App\Http\Controllers\Admin\TransactionController::class, 'show'])->name('admin.transactions.show');
   Route::get('/transactions/{transaction}/edit', [\App\Http\Controllers\Admin\TransactionController::class, 'edit'])->name('admin.transactions.edit');
   Route::put('/transactions/{transaction}', [\App\Http\Controllers\Admin\TransactionController::class, 'update'])->name('admin.transactions.update');
   Route::post('/transactions/bulk-update', [\App\Http\Controllers\Admin\TransactionController::class, 'bulkUpdate'])->name('admin.transactions.bulk-update');
   
   // SSL Verification Routes
   Route::post('/transactions/{transactionId}/verify-ssl', [\App\Http\Controllers\Admin\TransactionController::class, 'verifyIndividualSsl'])->name('admin.transactions.verify-ssl');
   Route::post('/transactions/{transactionId}/update-status', [\App\Http\Controllers\Admin\TransactionController::class, 'updateSslStatus'])->name('admin.transactions.update-status');
   Route::post('/transactions/bulk-verify-ssl', [\App\Http\Controllers\Admin\TransactionController::class, 'bulkVerifySsl'])->name('admin.transactions.bulk-verify-ssl');
 });

