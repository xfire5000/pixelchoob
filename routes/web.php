<?php

use App\Http\Controllers\StaticPagesController;
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
Route::controller(StaticPagesController::class)->group(function () {
    Route::get('/', 'index')->name('home.index');
    Route::post('contact', 'contactForm')->name('contact.send');
});
