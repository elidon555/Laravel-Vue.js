<?php

use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\StripeController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\SubscriptionPlanController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserDetailController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->post('/user', function (Request $request) {
    return new \App\Http\Resources\UserResource($request->user());
});

Route::prefix('preview')->as('preview')->group(function (){
    Route::apiResource('contents', ContentController::class)->only('index');
    Route::apiResource('subscription-plans', SubscriptionPlanController::class)->only('index');
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('user/update-profile', [UserController::class,'updateUserImages']);

    Route::apiResource('users', UserController::class);
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('permissions', PermissionController::class);
    Route::apiResource('contents', ContentController::class);
    Route::apiResource('subscription-plans', SubscriptionPlanController::class);
    Route::apiResource('payments', PaymentController::class);
    Route::apiResource('subscriptions', SubscriptionController::class);

    Route::get('user-details/current', [UserDetailController::class, 'getCurrentUserDetail']);

    Route::get('/report/subscriptions', [ReportController::class, 'subscriptions']);
    Route::get('/report/subscribers', [ReportController::class, 'subscribers']);

    Route::controller(DashboardController::class)->prefix('dashboard')->group(function(){
        Route::get('/subscribers-count','activeSubscribers');
        Route::get('/products-count','activeProducts');
        Route::get('/income-amount','totalIncome');
        Route::get('/subscriptions-by-country','subscriptionsByCountry');
        Route::get('/latest-subscribers','latestSubscribers');
        Route::get('/latest-subscriptions','latestOrders');
    });

    Route::prefix('stripe')->group(function(){
        Route::post('create-costumer', [StripeController::class, 'createCustomer']);
        Route::post('create-subscription', [StripeController::class, 'createSubscription']);
        Route::post('pay-subscription', [StripeController::class, 'paySubscription']);
        Route::post('pay-intent', [StripeController::class, 'createPaymentIntent']);
        Route::get('pay-success/{params}', [StripeController::class, 'payConfirm'])->name('pay.success');
    });

});




Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/signup', [AuthController::class, 'signup']);
