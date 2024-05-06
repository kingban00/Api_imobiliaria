<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\auth\LoginJwtController;
use App\Http\Controllers\api\RealStateController;
use App\Http\Controllers\api\RealStatePhotoController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\CategoryController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('v1')->group(function () {

    Route::post('/login', [LoginJwtController::class, 'login'])->name('login');
    Route::get('/logout', [LoginJwtController::class, 'logout'])->name('logout');
    Route::get('/refresh', [LoginJwtController::class, 'refresh'])->name('refresh');

    Route::group(['middleware' => ['jwt.auth']], function (){
        
        Route::name('real_states.')->group(function () {
            Route::resource('real-states', RealStateController::class);
        });
        
        Route::name('users.')->group(function () {
            Route::resource('users', UserController::class);
        });
    
        Route::name('categories.')->group(function () {
            Route::get('categories/{id}/real-states', [CategoryController::class,'realStates']);
    
            Route::resource('categories', CategoryController::class);
        });
    
        Route::name('photos.')->prefix('photos')->group(function () {
            Route::delete('/{id}', [RealStatePhotoController::class,'remove']);
            Route::put('/set-thumb/{photoId}/{realStateId}', [RealStatePhotoController::class,'isThumb']);
        });
    });
    
});

