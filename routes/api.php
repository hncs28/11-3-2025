<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CMS\CMSActivitiesController;



Route::get('/activities',[CMSActivitiesController::class,'index'])->name('get.activities');
Route::delete('/delete/activities/{actID}',[CMSActivitiesController::class,'delete']);
Route::post('/post/activities',[CMSActivitiesController::class,'post']);
Route::put('/update/activities/{actID}',[CMSActivitiesController::class,'update']);
Route::get('/activities/{actID}',[CMSActivitiesController::class,'find']);