<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserController::class, 'index'])->name('users.index');
Route::post('/users-import', [UserController::class, 'import'])->name('users.import');
