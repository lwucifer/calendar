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
Auth::routes();
Route::get('/', function () {
    return view('auth/login');
});
Route::get('/home', function () {
    return redirect('.');
});
Route::get('/dashboard', function () {
    return view('auth/login');
});
Route::get('/access-ip', function (\Illuminate\Http\Request $request) {
    return view('auth/access', ['ip' => $request->ip()]);
});

// for window APP

Route::get('/api/plan/getPlanInfo', 'API\PlanController@getPlanInfo');
Route::get('/api/user/getName', 'API\UserController@getName');

// unlock-account
Route::get('unlock-account', 'UnlockAccountController@unlockAccount')->name('account.unlock');
Route::post('unlock-account', 'UnlockAccountController@postUnlockAccount')->name('post.account.unlock');
Route::middleware(['web'])->group(function () {
    Route::middleware(['access.ip', 'password_expired'])->group(function () {
        // Main app controller
        Route::get('/', 'MainAppController@dashboard')->name('dashboard');
        Route::get('/mail/send', 'MailController@send');
        // Store
        Route::get('/api/store/search', 'API\StoreController@search');
        Route::get('/api/store/export/all', 'API\StoreController@exports');
        Route::post('/api/store/import', 'API\StoreController@import');
        Route::get('/api/store/list', 'API\StoreController@list');
        Route::get('/api/store/listSelect', 'API\StoreController@listSelect');
        Route::get('/api/store/enable_list', 'API\StoreController@listEnable');
        Route::post('/api/store/enable/{id}', 'API\StoreController@enable');
        Route::post('/api/store/disable/{id}', 'API\StoreController@disable');
        Route::resource('/api/store', 'API\StoreController');
        // Role
        Route::get('/api/user-role', 'API\RoleController@userRole');
        Route::get('/api/role-name', 'API\RoleController@getRoleName');
        Route::resource('/api/role', 'API\RoleController');
        // Unlock account
        Route::get('/api/unlock/search', 'API\UnlockController@search');
        Route::resource('/api/unlock', 'API\UnlockController');
        // Cash
        Route::resource('/api/cash','API\CashController');
        Route::middleware(['manager'])->group(function () {
            // User
            Route::get('/api/user/export/all', 'API\UserController@exports');
            Route::post('/api/user/import', 'API\UserController@import');
            Route::get('/api/user/search', 'API\UserController@search');
            Route::get('/api/user/manager_list', 'API\UserController@getListManager');
            Route::post('/api/user/enable/{id}', 'API\UserController@enable');
            Route::post('/api/user/disable/{id}', 'API\UserController@disable');
            Route::resource('/api/fid', 'API\FNumberController');
            // Photo
            Route::get('/api/photo/list', 'API\PhotoController@list');
            Route::get('/api/photo/listSelect', 'API\PhotoController@listSelect');
            Route::get('/api/photo/search', 'API\PhotoController@search');
            Route::post('/api/photo/enable/{id}', 'API\PhotoController@enable');
            Route::post('/api/photo/disable/{id}', 'API\PhotoController@disable');
            Route::get('/api/photo/export/all', 'API\PhotoController@exports');
            Route::post('/api/photo/import', 'API\PhotoController@import');
            Route::resource('/api/photo', 'API\PhotoController');
            // Campaign
            Route::get('/api/campaign/copy/{id}', 'API\CampaignController@copy');
            Route::get('/api/campaign/listSelect', 'API\CampaignController@listSelect');
            Route::get('/api/campaign/search', 'API\CampaignController@search');
            Route::post('/api/campaign/enable/{id}', 'API\CampaignController@enable');
            Route::post('/api/campaign/disable/{id}', 'API\CampaignController@disable');
            Route::get('/api/campaign/export/all', 'API\CampaignController@exports');
            Route::post('/api/campaign/import', 'API\CampaignController@import');
            Route::get('/api/campaign/stores/{id}', 'API\CampaignController@getLinkBooking');
            // Plan
            Route::post('/api/plan/import', 'API\PlanController@import');
            Route::post('/api/plan/updateCustomer', 'API\PlanController@updateDataCustomer');
            // Dashboard
            Route::get('/api/dashboard','API\DashboardController@dashboard');
            // Mail template
            Route::get('/api/email-template/list', 'API\MailTemplateController@list');
            Route::resource('/api/email-template','API\MailTemplateController');
        });
        Route::middleware(['user'])->group(function () {
            // Plan
            Route::get('/api/campaign/list', 'API\CampaignController@list');
            Route::get('/api/plan/search', 'API\PlanController@search');
            Route::post('/api/plan/enable/{id}', 'API\PlanController@enable');
            Route::post('/api/plan/disable/{id}', 'API\PlanController@disable');
            Route::post('/api/plan/status/{id}/{status}', 'API\PlanController@changeStatus');
            Route::get('/api/plan/export/all', 'API\PlanController@exports');
            Route::get('/api/plan/export/pdf', 'API\PlanController@exportPdfMultiple');
            Route::resource('/api/plan', 'API\PlanController');
            Route::resource('/api/campaign', 'API\CampaignController');
            // Change-password api
            Route::post('/api/change-password', 'API\ChangePasswordAPIController@changePassword');
            // User
            Route::resource('/api/user', 'API\UserController');
        });
        Route::middleware(['admin'])->group(function () {
            Route::get('/api/admin', 'API\AdminController@getAdmin');
            Route::post('/api/admin', 'API\AdminController@postAdmin');
        });
    });
    //expired password
    Route::get('password-expired', 'Auth\ExpiredPasswordController@expired')->name('password.expired');
    // change password
    Route::get('/change-password', 'Auth\ChangePasswordController@changePassword')->name('password.change');
    Route::post('/change-password', 'Auth\ChangePasswordController@postChangePassword')->name('password.post_change');
});

Route::get('/store/calendar/{id}','StoreCalendarController@getCalendar')->name('getCalendar');

Route::get('/calendar/{compaign}/{store}','BookCalendarController@getCalendar')->name('getCalendar');
Route::get('/calendar/search/{compaign}/{store}','BookCalendarController@searchCalendar')->name('searchCalendar');
Route::get('/campaign','BookCalendarController@getBooking')->name('getBooking');
Route::post('/campaign/search','BookCalendarController@search')->name('searchCampaign');

// frontend
Route::get('/booking/notify', function () {
    return view('frontend/notify');
});
Route::get('/booking/confirm/{token}', function () {
    return view('frontend/confirm');
});

Route::get('/plan/phone/confirm/{token}', 'API\PlanController@phoneConfirm');

Route::get('/booking/cancel/{token}', 'API\PlanController@infoCancel');
Route::get('/booking/info/{token}', 'API\PlanController@info');
Route::post('/api/plan/cancel/{token}', 'API\PlanController@cancel')->name('plan.cancel');
Route::post('/api/plan/create', 'API\PlanController@create')->name('plan.create');
Route::post('/api/plan/confirm/{token}', 'API\PlanController@confirm')->name('plan.confirm');

Route::get('api/testtel/{tel}','API\PlanController@testtel');

Route::get('api/reserveget','API\PlanController@reserveget');
Route::get('/plans/store/{id}','StoreCalendarController@dataStore');

Route::get('/plan/edit/{token}', 'API\PlanController@customerEdit');
Route::post('api/plan/customerupdate', 'API\PlanController@customerUpdate')->name('plan.update');

Route::get('/logout','Auth\LoginController@logout')->name('logout');


