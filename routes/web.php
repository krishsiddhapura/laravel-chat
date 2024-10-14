<?php

use App\Http\Controllers\web\ChatController;
use App\Http\Controllers\web\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::middleware('guest:web')->group(function (){
    Route::get('login', [UserController::class, 'login'])->name('login');
    Route::get('signup', [UserController::class, 'register'])->name('register');

    Route::post('login-check', [UserController::class, 'loginCheck'])->name('login-check');
    Route::post('register-user', [UserController::class, 'registerUser'])->name('register-user');
});


Route::middleware('auth:web')->group(function (){
    Route::get('/', [ChatController::class, 'index'])->name('index');
});


Route::get('chunk-upload',[\App\Http\Controllers\api\ChunkUploader::class,'index']);
Route::post('upload',[\App\Http\Controllers\api\ChunkUploader::class,'uploadChunk'])->name('upload-chunk');
