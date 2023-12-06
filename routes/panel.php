<?php

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
    Route::get('list-items/{id}', [ListItemController::class, 'index'])->name('list-items.index');
});
