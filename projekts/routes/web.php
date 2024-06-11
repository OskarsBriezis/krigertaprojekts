<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MouseMovementController;

Route::middleware(['auth'])->group(function () {
    Route::post('/save-movements', [MouseMovementController::class, 'saveMovements']);
    Route::get('/replay/{id}', [MouseMovementController::class, 'replay'])->name('replay.show');
    Route::get('/replay-list', [MouseMovementController::class, 'userReplays'])->name('replay.get');
    Route::delete('/delete-replay/{id}', [MouseMovementController::class, 'deleteReplay'])->name('replay.delete');
});

Route::get('/', function () {
    return view('welcome');
})->middleware(['auth', 'verified']);

Route::get('/dashboard', function () {
    return view('welcome');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
