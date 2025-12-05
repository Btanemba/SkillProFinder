<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\CRUD.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace' => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::get('user/cities/{countryCode}', [\App\Http\Controllers\Admin\UserCrudController::class, 'getCities'])->name('admin.user.cities');
    Route::crud('user', 'UserCrudController');
    Route::crud('selection', 'SelectionCrudController');
    Route::crud('service', 'ServiceCrudController');
    Route::crud('client', 'ClientCrudController');
    Route::crud('provider', 'ProviderCrudController');
    Route::crud('booking', 'BookingCrudController');
}); // this should be the absolute last line of this file

/**
 * DO NOT ADD ANYTHING HERE.
 */
