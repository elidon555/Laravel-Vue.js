<?php

use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StripeController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return new \App\Http\Resources\UserResource($request->user());
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('users', UserController::class);
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('permissions', PermissionController::class);
    Route::apiResource('contents', ContentController::class);

    Route::as('stripe')->prefix('stripe')->group(function(){
        Route::post('create-costumer', [StripeController::class, 'createCustomer']);
        Route::post('create-subscription', [StripeController::class, 'createSubscription']);
        Route::post('pay-subscription', [StripeController::class, 'paySubscription']);
    });

});




Route::post('/logout', [\App\Http\Controllers\Auth\AuthController::class, 'logout']);
Route::post('/login', [\App\Http\Controllers\Auth\AuthController::class, 'login']);
Route::post('/signup', [\App\Http\Controllers\Auth\AuthController::class, 'signup']);
