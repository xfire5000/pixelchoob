<?php

use App\Http\Controllers\ContactsController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ListCaseController;
use App\Http\Controllers\ListItemController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TicketsController;
use App\Http\Controllers\UserAddressInfoController;
use App\Http\Controllers\UsersController;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you permission register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware(['auth:sanctum', HandlePrecognitiveRequests::class])->group(function () {
    // list-cases
    Route::resource('list-cases', ListCaseController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::controller(ListCaseController::class)->prefix('list-cases')->group(function () {
        Route::get('duplicate/{id}', 'duplicate')->name('list-cases.duplicate');
        Route::get('restore/{id}', 'restore')->name('list-cases.restore');
        Route::post('send/{id}', 'send')->name('list-cases.send');
    });
    // contacts
    Route::resource('contacts', ContactsController::class)->only(['index', 'store', 'destroy']);
    Route::get('users', [ContactsController::class, 'usersIndex'])->name('search.contacts.index');
    // list-items
    Route::resource('list-items', ListItemController::class)->only(['store', 'destroy', 'update']);
    // invoices
    Route::resource('invoices', InvoiceController::class)->only(['store']);
    // settings
    Route::resource('settings', SettingController::class)->only(['store', 'show'])
        ->middleware(['edit-settings', 'view-settings']);
    // tickets
    Route::resource('tickets', TicketsController::class)->only(['index', 'store']);
    // address
    Route::resource('address', UserAddressInfoController::class)->only(['index', 'store', 'update', 'destroy']);
    // users
    Route::resource('users', UsersController::class)->only(['store', 'update', 'destroy'])
        ->middleware(['permission:add-users', 'permission:edit-users', 'permission:delete-users']);
});
