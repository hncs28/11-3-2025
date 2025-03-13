<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CMS\CMSActivitiesController;
use App\Http\Controllers\AuthController;
Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:api')->group(function () {
    Route::get('/activities', [CMSActivitiesController::class, 'index']);
    Route::delete('/delete/activities/{actID}', [CMSActivitiesController::class, 'delete']);
    Route::post('/post/activities', [CMSActivitiesController::class, 'post']);
    Route::put('/update/activities/{actID}', [CMSActivitiesController::class, 'update']);
    Route::get('/activities/{actID}', [CMSActivitiesController::class, 'find']);
});