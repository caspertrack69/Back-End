<?php

use App\Http\Controllers\Api\V1\Agent\Auth\AgentAuthController;
use App\Http\Controllers\Api\V1\Customer\Auth\PasswordResetController;
use App\Http\Controllers\Api\V1\Agent\Auth\PasswordResetController as AgentPasswordResetController;
use App\Http\Controllers\Api\V1\Agent\TransactionController as AgentTransactionController;
use App\Http\Controllers\Api\V1\BannerController;
use App\Http\Controllers\Api\V1\ConfigController;
use App\Http\Controllers\Api\V1\Customer\Auth\CustomerAuthController;
use App\Http\Controllers\Api\V1\Customer\TransactionController;
use App\Http\Controllers\Api\V1\GeneralController;
use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\Api\V1\OTPController;
use Illuminate\Support\Facades\Route;


Route::group(['namespace' => 'Api\V1', 'middleware' => ['deviceVerify']], function () {

    //Check user type
    Route::group(['middleware' => ['inactiveAuthCheck', 'trackLastActiveAt', 'auth:api']], function () {
        Route::post('check-customer', [GeneralController::class, 'check_customer']);
        Route::post('check-agent', [GeneralController::class, 'check_agent']);

    });

    //Customer [Route Group]
    Route::group(['prefix' => 'customer', 'namespace' => 'Auth'], function () {

        Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {
            //Authentication
            Route::post('check-phone', [CustomerAuthController::class, 'check_phone']);
            Route::post('verify-phone', [CustomerAuthController::class, 'verify_phone']);
            Route::post('register', [CustomerAuthController::class, 'registration']);
            Route::post('resend-otp', [CustomerAuthController::class, 'resend_otp']);
            Route::post('login', [CustomerAuthController::class, 'login']);

            //FORGET PASSWORD
            Route::post('forgot-password', [PasswordResetController::class, 'reset_password_request']);
            Route::post('verify-token', [PasswordResetController::class, 'verify_token']);
            Route::put('reset-password', [PasswordResetController::class, 'reset_password_submit']);
        });
        Route::group(['middleware' => ['inactiveAuthCheck', 'trackLastActiveAt', 'auth:api', 'customerAuth']], function () {
            Route::get('get-customer', [CustomerAuthController::class, 'get_customer']);
            Route::get('get-purpose', [CustomerAuthController::class, 'get_purpose']);
            Route::get('get-banner', [BannerController::class, 'get_customer_banner']);
            Route::get('linked-website', [CustomerAuthController::class, 'linked_website']);
            Route::get('get-notification', [NotificationController::class, 'get_customer_notification']);
            Route::get('get-requested-money', [CustomerAuthController::class, 'get_requested_money']);
            Route::get('get-own-requested-money', [CustomerAuthController::class, 'get_own_requested_money']);
            Route::delete('remove-account', [CustomerAuthController::class, 'remove_account']);
            Route::put('update-kyc-information', [CustomerAuthController::class, 'update_kyc_information']);

            //OTP
            Route::post('check-otp', [OTPController::class, 'check_otp']);
            Route::post('verify-otp', [OTPController::class, 'verify_otp']);
            //PIN
            Route::post('verify-pin', [CustomerAuthController::class, 'verify_pin']);
            Route::post('change-pin', [CustomerAuthController::class, 'change_pin']);
            //2factor
            Route::post('update-two-factor', [CustomerAuthController::class, 'update_two_factor']);
            //fcm-token
            Route::put('update-fcm-token', [CustomerAuthController::class, 'update_fcm_token']);
            //logout
            Route::post('logout', [CustomerAuthController::class, 'logout']);
            //profile
            Route::put('update-profile', [CustomerAuthController::class, 'update_profile']);

            //transactions
            Route::post('send-money', [TransactionController::class, 'send_money']);
            Route::post('cash-out', [TransactionController::class, 'cash_out']);
            Route::post('request-money', [TransactionController::class, 'request_money']);
            Route::post('request-money/{slug}', [TransactionController::class, 'request_money_status']);
            Route::post('add-money', [TransactionController::class, 'add_money']);
            Route::get('transaction-history', [TransactionController::class, 'transaction_history']);
        });
        Route::post('find-customer', [CustomerAuthController::class, 'find_customer']);

    });

    //Agents [Route Group]
    Route::group(['prefix' => 'agent', 'namespace' => 'Auth'], function () {

        Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {
            //Authentication
            //Route::post('check-phone', [AgentAuthController::class, 'check_phone']);
            //Route::post('verify-phone', [AgentAuthController::class, 'verify_phone']);
            //Route::post('register', [AgentAuthController::class, 'registration']);
            Route::post('login', [AgentAuthController::class, 'login']);

            //forgot password
            Route::post('forgot-password', [AgentPasswordResetController::class, 'reset_password_request']);
            Route::post('verify-token', [AgentPasswordResetController::class, 'verify_token']);
            Route::put('reset-password', [AgentPasswordResetController::class, 'reset_password_submit']);
        });
        Route::group(['middleware' => ['inactiveAuthCheck', 'trackLastActiveAt', 'auth:api', 'agentAuth']], function () {
            Route::get('get-agent', [AgentAuthController::class, 'get_agent']);
            Route::get('get-notification', [NotificationController::class, 'get_agent_notification']);
            Route::get('get-banner', [BannerController::class, 'get_agent_banner']);
            Route::get('linked-website', [AgentAuthController::class, 'linked_website']);
            Route::get('get-requested-money', [AgentAuthController::class, 'get_requested_money']);
            Route::put('update-kyc-information', [CustomerAuthController::class, 'update_kyc_information']);

            //OTP
            Route::post('check-otp', [OTPController::class, 'check_otp']);
            Route::post('verify-otp', [OTPController::class, 'verify_otp']);
            //PIN
            Route::post('verify-pin', [AgentAuthController::class, 'verify_pin']);
            Route::post('change-pin', [AgentAuthController::class, 'change_pin']);
            //2factor
            Route::post('update-two-factor', [AgentAuthController::class, 'update_two_factor']);
            //fcm-token
            Route::put('update-fcm-token', [AgentAuthController::class, 'update_fcm_token']);
            //logout
            Route::post('logout', [AgentAuthController::class, 'logout']);
            //profile
            Route::put('update-profile', [AgentAuthController::class, 'update_profile']);

            //transaction
//            Route::post('cash-in', [AgentTransactionController::class, 'cash_in']);


            Route::post('send-money', [AgentTransactionController::class, 'cash_in']);
            Route::post('cash-out', [AgentTransactionController::class, 'cash_out']);
            Route::post('request-money', [AgentTransactionController::class, 'request_money']);
            Route::post('request-money/{slug}', [AgentTransactionController::class, 'request_money_status']);
            Route::post('add-money', [AgentTransactionController::class, 'add_money']);
            Route::get('transaction-history', [AgentTransactionController::class, 'transaction_history']);
        });

    });

    //Configuration
    Route::get('/config', [ConfigController::class, 'configuration']);
    //FAQ
    Route::get('/faq', [GeneralController::class, 'faq']);
});
