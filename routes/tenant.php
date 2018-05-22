<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 4/28/2018
 * Time: 7:01 PM
 */

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here is where you can register 'tenant' routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "tenant" middleware group. Now create something great!
|
*/
Route::group(['prefix' => '/company', 'as' => 'tenant.'], function () {
    /**
     * Projects Main (Resource) Routes
     */
    Route::resource('/projects', 'ProjectController');

    Route::group(['prefix' => '/account', 'as' => 'account.'], function () {
        /**
         * Settings Routes
         */
        Route::resource('/settings', 'TenantSettingsController')->only('index', 'store');

        /**
         * Profile Routes
         */
        Route::resource('/profile', 'TenantProfileController')->only('index', 'store');

        /**
         * Delete Routes
         */
        Route::resource('/delete', 'TenantDeleteController')->only('index', 'store');
    });

    /**
     * Account Routes
     */
    Route::resource('/account', 'TenantAccountController')->only('index', 'store');

    /**
     * --------------------------------------------------------------------------
     * Dashboard
     * --------------------------------------------------------------------------
     *
     * All other tenant routes should go above this one
     */
    Route::get('/{company}', 'DashboardController@index')->name('dashboard');
});
