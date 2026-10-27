<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminPropertyController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\PropertyController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




// --- Public Routes ---


Route::post('/register', [AuthController::class, 'register'])
    ->middleware('throttle:5,1');

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:login');

Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])
    ->middleware('throttle:5,1');

Route::post('/reset-password', [AuthController::class, 'resetPassword'])
    ->middleware('throttle:5,1');

Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');


Route::get('/properties/{property}', [PropertyController::class, 'showAllProperties'])
    ->whereNumber('property');


// --- Authenticated User Routes ---


Route::middleware([
    'auth:sanctum',
    'account.active',
])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);

    Route::post('/email/verification-notification', [AuthController::class, 'resendVerificationEmail',])
        ->middleware('throttle:6,1');

    Route::get('/user', [ProfileController::class, 'showInfoUser']);

    Route::get('/profile', [ProfileController::class, 'showProfile']);
    Route::put('/profile', [ProfileController::class, 'updateProfile']);
    Route::delete('/profile', [ProfileController::class, 'destroyProfile']);
});



Route::get('/properties', [PropertyController::class, 'indexProperty']);



// --- Admin Routes ---


Route::middleware([
    'auth:sanctum',
    'account.active',
    'role:admin',
])
    ->prefix('admin')
    ->group(function () {

        Route::patch(
            '/properties/{property}/approve',
            [AdminPropertyController::class, 'approve']
        );

        Route::patch(
            '/users/{user}/suspend',
            [AdminUserController::class, 'suspend']
        );
    });



// --- Landlord Routes ---


Route::middleware([
    'auth:sanctum',
    'account.active',
    'verified',
    'role:landlord',
])->group(function () {

    Route::post(
        '/properties',
        [PropertyController::class, 'storeProperty']
    );

    Route::put(
        '/properties/{property}',
        [PropertyController::class, 'updateProperty']
    );

    Route::delete(
        '/properties/{property}',
        [PropertyController::class, 'destroyProperty']
    );

    Route::post(
        '/properties/{property}/submit-for-review',
        [PropertyController::class, 'submitForReview']
    );
});
