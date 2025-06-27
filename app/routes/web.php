<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('tasks.index');
});

Route::get('/dashboard', function () {
    return redirect()->route('tasks.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dodaj te linie dla zarządzania zadaniami
    Route::resource('tasks', \App\Http\Controllers\TaskController::class);

    // Trasy dla linków do udostępniania
    Route::post('/tasks/{task}/share', [\App\Http\Controllers\ShareableLinkController::class, 'store'])->name('tasks.share');
    Route::delete('/shareable-links/{shareableLink}', [\App\Http\Controllers\ShareableLinkController::class, 'destroy'])->name('shareable-links.destroy');

    // Trasa dla podglądu historycznej wersji zadania
    Route::get('/tasks/{task}/history/{history}', [\App\Http\Controllers\TaskController::class, 'showHistory'])->name('tasks.history.show');
});

// Trasa dla publicznego dostępu do zadań przez link
Route::get('/shared-task/{token}', [\App\Http\Controllers\ShareableLinkController::class, 'show'])->name('shared-task.show');

require __DIR__.'/auth.php';
