<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ListItemController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',

])->group(function () {
    Route::inertia('/dashboard', 'Dashboard')->name('dashboard');
    // list-cases
    Route::inertia('list-case', 'ListCase')->name('list-case.index');
    // list-items
    Route::resource('list-items', ListItemController::class);
    // invoices
    Route::resource('invoices', InvoiceController::class);
});
