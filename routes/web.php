<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('CMS/activities/activities');
})->name('dashboard');
use App\Http\Controllers\CMS\CMSActivitiesController;



Route::get('/activities',[CMSActivitiesController::class,'index'])->name('get.activities');
Route::delete('/delete/activities/{actID}',[CMSActivitiesController::class,'delete'])->name('delete');
Route::post('/post/activities',[CMSActivitiesController::class,'post'])->name('post');
Route::put('/update/activities/{actID}',[CMSActivitiesController::class,'update'])->name('update');
Route::get('/activities/{actID}',[CMSActivitiesController::class,'find'])->name('find');

Route::get('/loginform',function(){
    return view('auth.login');
})->name('loginform');