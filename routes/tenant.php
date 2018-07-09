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

    Route::group(['prefix' => '/manage'], function () {

        /**
         * Job Namespace Routes
         */
        Route::group(['namespace' => 'Job'], function () {

            /**
             * Jobs Group Routes
             */
            Route::group(['prefix' => '/jobs', 'as' => 'jobs.'], function () {

                /**
                 * Jobs Route
                 */
                Route::get('/index', 'JobIndexController@index');

                /**
                 * Job Areas Route
                 */
                Route::get('/areas', 'JobIndexController@areas');

                /**
                 * Job Group Routes
                 */
                Route::group(['prefix' => '/{job}'], function () {

                    /**
                     * Job Applications Group Routes
                     */
                    Route::group(['prefix' => 'applications', 'as' => 'applications.'], function () {

                        /**
                         * Job Application Group Routes
                         */
                        Route::group(['prefix' => '{jobApplication}'], function () {

                            /**
                             * Job Reject Store Route
                             */
                            Route::post('/reject/store', 'JobApplicationRejectController@store')->name('reject.store');

                            /**
                             * Job Reject Update Route
                             */
                            Route::put('/reject/update', 'JobApplicationRejectController@update')->name('reject.update');

                            /**
                             * Job Reject Destroy Route
                             */
                            Route::delete('/reject/destroy', 'JobApplicationRejectController@destroy')->name('reject.destroy');

                            /**
                             * Job Accept Store Route
                             */
                            Route::post('/accept/store', 'JobApplicationAcceptController@store')->name('accept.store');

                            /**
                             * Job Accept Update Route
                             */
                            Route::put('/accept/update', 'JobApplicationAcceptController@update')->name('accept.update');

                            /**
                             * Job Accept Destroy Route
                             */
                            Route::delete('/accept/destroy', 'JobApplicationAcceptController@destroy')->name('accept.destroy');
                        });
                    });

                    /**
                     * Job Applications Resource Routes
                     */
                    Route::apiResource('/applications', 'JobApplicationController', [
                        'parameters' => [
                            'applications' => 'jobApplication'
                        ]
                    ])->only('index', 'show');

                    /**
                     * Job Deadline Store Route
                     */
                    Route::post('/deadline', 'JobDeadlineController@store');

                    /**
                     * Job Deadline Restore Route
                     */
                    Route::put('/deadline', 'JobDeadlineController@restore');

                    /**
                     * Job Checkout Routes
                     */
                    Route::resource('/checkout', 'JobCheckoutController')->only('index', 'store');

                    /**
                     * Categories Routes
                     */
                    Route::resource('/categories', 'JobCategoryController', [
                        'parameters' => [
                            'categories' => 'jobCategory'
                        ]
                    ])->except('show', 'update');

                    /**
                     * Requirements Routes
                     */
                    Route::resource('/requirements', 'JobRequirementController', [
                        'parameters' => [
                            'requirements' => 'jobRequirement'
                        ]
                    ])->except('show');

                    /**
                     * Languages Routes
                     */
                    Route::resource('/languages', 'JobLanguageController', [
                        'parameters' => [
                            'languages' => 'jobSkillable'
                        ]
                    ])->except('show');

                    /**
                     * Skills Routes
                     */
                    Route::resource('/skills', 'JobSkillController', [
                        'parameters' => [
                            'skills' => 'jobSkill'
                        ]
                    ])->except('show');

                    /**
                     * Education Routes
                     */
                    Route::resource('/education', 'JobEducationController', [
                        'parameters' => [
                            'education' => 'jobEducation'
                        ]
                    ])->except('show');

                    /**
                     * Job Status Route
                     */
                    Route::post('/status', 'JobStatusController@toggleStatus');
                });

                /**
                 * Job Store Route
                 */
                Route::post('/{job}', 'JobController@store')->name('job.store');
            });

            /**
             * Jobs Main (Resource) Routes
             */
            Route::resource('/jobs', 'JobController')->except('store', 'edit');
        });
    });

    /**
     * Projects Main (Resource) Routes
     */
    Route::resource('/projects', 'ProjectController');

    /**
     * Tenant Account Routes
     */
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
     * Switch Tenant Route
     */
    Route::get('/{company}', 'TenantSwitchController@switch')->name('switch');

    /**
     * --------------------------------------------------------------------------
     * Dashboard
     * --------------------------------------------------------------------------
     *
     * All other tenant routes should go above this one
     */
    Route::get('/', 'DashboardController@index')->name('dashboard');
});
