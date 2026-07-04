<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\MealController;
use App\Http\Controllers\API\FavoriteController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\AddressController;
use App\Http\Controllers\API\PaymentMethodController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\ReviewController;



Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/categories/{id}/meals', [MealController::class, 'categoryMeals']);
Route::get('/search', [MealController::class, 'search']);


Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/verify-reset-otp', [AuthController::class, 'verifyResetOtp']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    //profile 
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);  
    
    // address
    Route::apiResource('addresses', AddressController::class);


    // favourite
    Route::get('/favorites', [FavoriteController::class, 'index']);
Route::post('/favorites', [FavoriteController::class, 'store']);
Route::delete('/favorites/{mealId}', [FavoriteController::class, 'destroy']);


  //paymentmethods
  Route::get('/payment-methods', [PaymentMethodController::class, 'index']);
  Route::get('/payment-methods/{id}', [PaymentMethodController::class, 'show']);
  //payment 
  Route::get('/payments', [PaymentController::class,'index']);
  Route::get('/payments/{id}', [PaymentController::class,'show']);


  //notifications:
Route::get('/notifications', [NotificationController::class, 'index']);
Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);


});
Route::post('/resend-otp', [AuthController::class, 'resendOtp']);
Route::apiResource('categories', CategoryController::class);


Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('meals', MealController::class);

    // cart part
Route::get('/cart', [CartController::class, 'index']);

Route::post('/cart', [CartController::class, 'store']);

Route::put('/cart/{id}', [CartController::class, 'update']);

Route::delete('/cart/{id}', [CartController::class, 'destroy']);

Route::delete('/cart', [CartController::class, 'clear']);

//order part
Route::get('/orders', [OrderController::class, 'index']);

Route::get('/orders/{id}', [OrderController::class, 'show']);

Route::post('/orders', [OrderController::class, 'store']);

// track order
Route::get('/orders/{id}/track', [OrderController::class, 'track']);

//update status of order
Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus']);

//review the order
Route::get('/meals/{meal}/reviews', [ReviewController::class, 'index']);
Route::post('/reviews', [ReviewController::class, 'store']);
Route::put('/reviews/{id}', [ReviewController::class, 'update']);
Route::delete('/reviews/{id}', [ReviewController::class, 'destroy']);

});