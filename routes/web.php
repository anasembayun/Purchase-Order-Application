<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


/**
 * Auth routes
 */
Route::group(['namespace' => 'Auth'], function () {

    // Authentication Routes...
    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login');
    Route::get('logout', 'LoginController@logout')->name('logout');

    // Registration Routes...
    if (config('auth.users.registration')) {
        Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
        Route::post('register', 'RegisterController@register');
    }

    // Password Reset Routes...
    Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'ResetPasswordController@reset');

    // Confirmation Routes...
    if (config('auth.users.confirm_email')) {
        Route::get('confirm/{user_by_code}', 'ConfirmController@confirm')->name('confirm');
        Route::get('confirm/resend/{user_by_email}', 'ConfirmController@sendEmail')->name('confirm.send');
    }

    // Social Authentication Routes...
    Route::get('social/redirect/{provider}', 'SocialLoginController@redirect')->name('social.redirect');
    Route::get('social/login/{provider}', 'SocialLoginController@login')->name('social.login');
});

/**
 * Backend routes
 */
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => 'admin'], function () {

    //Dashboard
    Route::get('/', 'DashboardController@index')->name('dashboard');

    //Reporting
    Route::get('/reporting', 'DashboardController@reportIndex')->name('reporting');
    Route::get('report/product-price-grouping','DashboardController@getProductPriceGrouping')->name('report.product.price.grouping');
    Route::get('report/product-all-data', 'DashboardController@getDataAllProducts')->name('report.product.all.data');

    //Report
    Route::get('/report', 'DashboardController@getReport')->name('report');
    Route::get('report/product-price', 'DashboardController@getAllProductPrice')->name('report.product.price');
    

    //Users
    Route::get('users', 'UserController@index')->name('users');
    Route::get('users/restore', 'UserController@restore')->name('users.restore');
    Route::get('users/{id}/restore', 'UserController@restoreUser')->name('users.restore-user');
    Route::get('users/{user}', 'UserController@show')->name('users.show');
    Route::get('users/{user}/edit', 'UserController@edit')->name('users.edit');
    Route::put('users/{user}', 'UserController@update')->name('users.update');
    Route::any('users/{id}/destroy', 'UserController@destroy')->name('users.destroy');
    Route::get('permissions', 'PermissionController@index')->name('permissions');
    Route::get('permissions/{user}/repeat', 'PermissionController@repeat')->name('permissions.repeat');
    Route::get('dashboard/log-chart', 'DashboardController@getLogChartData')->name('dashboard.log.chart');
    Route::get('dashboard/registration-chart', 'DashboardController@getRegistrationChartData')->name('dashboard.registration.chart');

    //Product
    Route::get('product', 'PurchaseOrderController@ProductIndex')->name('products'); //with datatable
    Route::get('product/index', 'PurchaseOrderController@getProductList')->name('products.index');//product without datatable
    Route::post('product/import', 'PurchaseOrderController@ProductImport')->name('products.import');
    Route::get('product/export', 'PurchaseOrderController@ProductExport')->name('products.export');
    Route::get('product/{id}', 'PurchaseOrderController@getProductShow')->name('products.show');
    Route::get('product/price/{id}', 'PurchaseOrderController@getProductPrice')->name('getPrice');
    Route::get('product/{id}/edit', 'PurchaseOrderController@getProductEdit')->name('products.edit');
    Route::get('product/{id}/destroy', 'PurchaseOrderController@getProductDestroy')->name('products.destroy');

    //Purchase Order Line
    Route::get('purchase-order-lines', 'PurchaseOrderController@purchaseOrderLineList')->name('purchase.order.lines');
    Route::get('purchase-order-lines/create', 'PurchaseOrderController@purchaseOrderLineCreate')->name('purchase.order.lines.create');
    Route::post('purchase-order-lines/store', 'PurchaseOrderController@purchaseOrderLineStore')->name('purchase.order.lines.store');
    Route::get('purchase-order-lines/{id}', 'PurchaseOrderController@purchaseOrderLineShow')->name('purchase.order.lines.show');
    Route::get('purchase-order-lines/{id}/edit', 'PurchaseOrderController@purchaseOrderLineEdit')->name('purchase.order.lines.edit');
    Route::post('purchase-order-lines/{id}/update', 'PurchaseOrderController@purchaseOrderLineUpdate')->name('purchase.order.lines.update');
    Route::get('purchase-order-lines/{id}/destroy', 'PurchaseOrderController@purchaseOrderLineDestroy')->name('purchase.order.lines.destroy');

    //Purchase Request
    Route::get('purchase-request', 'PurchaseOrderController@purchaseRequestList')->name('purchase.request');
    Route::get('purchase-request/create', 'PurchaseOrderController@purchaseRequestCreate')->name('purchase.request.create');
    Route::post('purchase-request/store', 'PurchaseOrderController@purchaseRequestStore')->name('purchase.request.store');
    Route::get('purchase-request/{id}', 'PurchaseOrderController@purchaseRequestShow')->name('purchase.request.show');
    Route::get('purchase-request/{id}/edit', 'PurchaseOrderController@purchaseRequestEdit')->name('purchase.request.edit');
    Route::post('purchase-request/{id}/update', 'PurchaseOrderController@purchaseRequestUpdate')->name('purchase.request.update');
    Route::get('purchase-request/{id}/destroy', 'PurchaseOrderController@purchaseRequestDestroy')->name('purchase.request.destroy');
});


Route::get('/', 'HomeController@index');

/**
 * Membership
 */
Route::group(['as' => 'protection.'], function () {
    Route::get('membership', 'MembershipController@index')->name('membership')->middleware('protection:' . config('protection.membership.product_module_number') . ',protection.membership.failed');
    Route::get('membership/access-denied', 'MembershipController@failed')->name('membership.failed');
    Route::get('membership/clear-cache/', 'MembershipController@clearValidationCache')->name('membership.clear_validation_cache');
});

Route::get('/complete-registration', 'Auth\RegisterController@completeRegistration');

Route::post('/2fa', function(){
    return redirect(URL()->previous());
})->name('2fa')->middleware('2fa');

Route::get('tes/2fa', 'MembershipController@index')->name('tes.2fa')->middleware(['auth', '2fa']);
