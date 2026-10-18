<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminPropertyController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register'])
    ->middleware('throttle:5,1');


Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:login');


Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])
    ->middleware('throttle:5,1');


Route::post('/reset-password', [AuthController::class, 'resetPassword'])
    ->middleware('throttle:10,1');


Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');




Route::middleware([
    'auth:sanctum',
    'account.active',
])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);


    Route::post('/change-password', [AuthController::class, 'changePassword']);


    Route::post('/email/verification-notification', [
        AuthController::class,
        'resendVerificationEmail',
    ])->middleware('throttle:6,1');


    Route::get('/user', function (Request $request) {
        return new UserResource($request->user());
    });


    Route::get('/profile', [ProfileController::class, 'show']);

    Route::put('/profile', [ProfileController::class, 'update']);

    Route::delete('/profile', [ProfileController::class, 'destroy']);
});



////                            ADMIN                           ////

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
