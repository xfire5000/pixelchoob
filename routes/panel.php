<?php

use App\Http\Controllers\ListItemController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', '\App\Http\Controllers\DashboardController@index')->name('dashboard');
    // list-cases
    Route::inertia('list-case', 'ListCase')->name('list-case.index');
    // list-items
    Route::controller(ListItemController::class)->prefix('list-items')->group(function () {
        Route::get('{id}', 'index')->name('list-items.index');
        Route::get('export', 'export')->name('list-items.export');
    });
    // invoices
    Route::get('invoices', '\App\Http\Controllers\InvoiceController@index')->name('invoices.index');
    // settings
    Route::inertia('settings', 'Settings/index')->name('settings.index');
});
