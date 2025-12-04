<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;

// Публичные маршруты
Route::get('/', [VideoController::class, 'index'])->name('home');
Route::resource('videos', VideoController::class)->only(['index', 'show', 'create', 'store']);

// Профиль
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Тестовые маршруты
Route::get('/test-admin-controller', [AdminController::class, 'dashboard'])->name('test.admin');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/videos', [\App\Http\Controllers\Admin\AdminController::class, 'videos'])->name('videos');
    Route::get('/videos/create', [\App\Http\Controllers\Admin\AdminController::class, 'createVideo'])->name('videos.create');
    Route::post('/videos', [\App\Http\Controllers\Admin\AdminController::class, 'storeVideo'])->name('videos.store');
    Route::get('/videos/{video}/edit', [\App\Http\Controllers\Admin\AdminController::class, 'editVideo'])->name('videos.edit');
    Route::put('/videos/{video}', [\App\Http\Controllers\Admin\AdminController::class, 'updateVideo'])->name('videos.update');
    Route::delete('/videos/{video}', [\App\Http\Controllers\Admin\AdminController::class, 'destroyVideo'])->name('videos.destroy');
    Route::post('/videos/{video}/approve', [\App\Http\Controllers\Admin\AdminController::class, 'approveVideo'])->name('videos.approve');

    Route::get('/users', [\App\Http\Controllers\Admin\AdminController::class, 'users'])->name('users');
    Route::put('/users/{user}/role', [\App\Http\Controllers\Admin\AdminController::class, 'updateUserRole'])->name('users.update-role');
});
require __DIR__.'/auth.php';
