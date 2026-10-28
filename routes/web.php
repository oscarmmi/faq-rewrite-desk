<?php

use App\Http\Controllers\RewriteDeskController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RewriteDeskController::class, 'index'])->name('home');
Route::post('/rewrite', [RewriteDeskController::class, 'store'])->name('rewrites.store');
