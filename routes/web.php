<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Auth::routes();
// Admin
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function () {
    // define your admin routes here
    Route::get('users', [AdminController::class, 'users'])->name('admin.users');
    // Content
    Route::get('/', [ContentController::class, 'index'])->name('content.index');
    Route::get('/content/create', [ContentController::class, 'create'])->name('content.create');
    Route::post('/content', [ContentController::class, 'store'])->name('content.store');
    Route::get('/content/{id}', [ContentController::class, 'show'])->name('content.show');
    Route::get('/content/{id}/edit', [ContentController::class, 'edit'])->name('content.edit');
    Route::put('/content/{id}', [ContentController::class, 'update'])->name('content.update');
    Route::delete('/content/{id}', [ContentController::class, 'destroy'])->name('content.destroy');

});

// Payments
Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
Route::get('/payments/{id}', [PaymentController::class, 'show'])->name('payments.show');
Route::get('/payments/{id}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
Route::put('/payments/{id}', [PaymentController::class, 'update'])->name('payments.update');
Route::delete('/payments/{id}', [PaymentController::class, 'destroy'])->name('payments.destroy');

// Subscription
Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
Route::get('/subscriptions/create', [SubscriptionController::class, 'create'])->name('subscriptions.create');
Route::post('/subscriptions', [SubscriptionController::class, 'store'])->name('subscriptions.store');
Route::get('/subscriptions/{id}', [SubscriptionController::class, 'show'])->name('subscriptions.show');
Route::get('/subscriptions/{id}/edit', [SubscriptionController::class, 'edit'])->name('subscriptions.edit');
Route::put('/subscriptions/{id}', [SubscriptionController::class, 'update'])->name('subscriptions.update');
Route::delete('/subscriptions/{id}', [SubscriptionController::class, 'destroy'])->name('subscriptions.destroy');


//Route::post('/stripe/create', [StripeController::class, 'create'])->name('stripe.create');
