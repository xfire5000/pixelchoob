<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ListCaseController;
use App\Http\Controllers\ListItemController;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    HandlePrecognitiveRequests::class,
])->group(function () {
    Route::inertia('/dashboard', 'Dashboard')->name('dashboard');
    Route::resource('list-cases', ListCaseController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::controller(ListCaseController::class)->prefix('list-cases')->group(function () {
        Route::get('api/{type}', 'apiIndex')->name('list-cases.index.api');
        Route::get('duplicate/{id}', 'duplicate')->name('list-cases.duplicate');
    });
    Route::resource('list-items', ListItemController::class);
    Route::resource('invoices', InvoiceController::class);
});
