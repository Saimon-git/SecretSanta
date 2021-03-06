<?php

use App\Http\Controllers\FibonnacciController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SantaController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/santa', [SantaController::class, 'index']);
Route::get('/fibonacci', [FibonnacciController::class, 'index']);
