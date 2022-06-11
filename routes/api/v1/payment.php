<?php

/*
|--------------------------------------------------------------------------
| User API Routes
|--------------------------------------------------------------------------
|
| These routes are prefixed with 'api/v1'.
| These routes use the root namespace 'App\Http\Controllers\Api\V1'.
|
 */
use App\Base\Constants\Auth\Role;

/*
 * These routes are prefixed with 'api/v1/payment'.
 * These routes use the root namespace 'App\Http\Controllers\Api\V1\Payment'.
 * These routes use the middleware group 'auth'.
 */
Route::prefix('payment')->namespace('Payment')->middleware('auth')->group(function () {

    /**
     * These routes use the middleware group 'role'.
     * These routes are accessible only by a user with the 'user' role.
     */
    Route::middleware(role_middleware(Role::mobileAppRoles()))->group(function () {
        // Card apis
        Route::post('card/add', 'PaymentController@addCard');
        Route::get('card/list', 'PaymentController@listCards');
        Route::post('card/make/default', 'PaymentController@makeDefaultCard');
        Route::delete('card/delete/{card}', 'PaymentController@deleteCard');
        // Braintree api token get list
        Route::get('client/token', 'PaymentController@getClientToken');
        // Add money to wallet

        Route::prefix('wallet')->group(function () {
            Route::post('add/money', 'PaymentController@addMoneyToWallet');
            Route::get('history', 'PaymentController@walletHistory');
            Route::get('withdrawal-requests','PaymentController@withDrawalRequests');
            Route::post('request-for-withdrawal','PaymentController@requestForWithdrawal');
        });


    /**
     * Braintree Payment Gateway
     * 
     * */
    Route::prefix('braintree')->namespace('Braintree')->group(function(){

        Route::get('client/token', 'BraintreeController@getClientToken');
        Route::post('add/money', 'BraintreeController@addMoneyToWallet');

    });

    /**
     * Stripe Payment Gateway
     * 
     * */
    Route::prefix('stripe')->namespace('Stripe')->group(function(){

        Route::post('intent', 'StripeController@createStripeIntent');
        Route::post('add/money', 'StripeController@addMoneyToWallet');

    });

    /**
     * Razerpay Payment Gateway
     * 
     * */
    Route::prefix('razerpay')->namespace('Razerpay')->group(function(){

        Route::post('add/money', 'RazerpayController@addMoneyToWallet');

    });


    });

    
});
