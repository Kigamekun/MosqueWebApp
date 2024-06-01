<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{DashboardController, BlogController, ActivityController, ReservasiController, InfaqController, ZakatController, PrayerTimeController};

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);

Route::get('/getFile/{folder}/{filename}', function ($folder,$filename) {
    return response()->file(storage_path('app/public/').$folder.'/'.$filename);
});

Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');


Route::prefix('blog')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('blog.index');
    Route::post('/', [BlogController::class, 'store'])->middleware('auth:sanctum')->name('blog.store');
    Route::get('/{id}', [BlogController::class, 'show'])->middleware('auth:sanctum')->name('blog.show');
    Route::post('/{id}', [BlogController::class, 'update'])->middleware('auth:sanctum')->name('blog.update');
    Route::delete('/{id}', [BlogController::class, 'destroy'])->middleware('auth:sanctum')->name('blog.destroy');
});
Route::prefix('activity')->group(function () {
    Route::get('/', [ActivityController::class, 'index'])->name('activity.index');
    Route::post('/', [ActivityController::class, 'store'])->middleware('auth:sanctum')->name('activity.store');
    Route::get('/{id}', [ActivityController::class, 'show'])->middleware('auth:sanctum')->name('activity.show');
    Route::post('/{id}', [ActivityController::class, 'update'])->middleware('auth:sanctum')->name('activity.update');
    Route::delete('/{id}', [ActivityController::class, 'destroy'])->middleware('auth:sanctum')->name('activity.destroy');
});
Route::prefix('reservasi')->group(function () {
    Route::get('/', [ReservasiController::class, 'index'])->name('reservasi.index');
    Route::post('/', [ReservasiController::class, 'store'])->middleware('auth:sanctum')->name('reservasi.store');
    Route::get('/{id}', [ReservasiController::class, 'show'])->middleware('auth:sanctum')->name('reservasi.show');
    Route::post('/{id}', [ReservasiController::class, 'update'])->middleware('auth:sanctum')->name('reservasi.update');
    Route::delete('/{id}', [ReservasiController::class, 'destroy'])->middleware('auth:sanctum')->name('reservasi.destroy');
});
Route::prefix('infaq')->group(function () {
    Route::get('/', [InfaqController::class, 'index'])->name('infaq.index');
    Route::post('/', [InfaqController::class, 'store'])->middleware('auth:sanctum')->name('infaq.store');
    Route::get('/{infaq}', [InfaqController::class, 'show'])->middleware('auth:sanctum')->name('infaq.show');
    Route::patch('/{infaq}', [InfaqController::class, 'update'])->middleware('auth:sanctum')->name('infaq.update');
    Route::delete('/{infaq}', [InfaqController::class, 'destroy'])->middleware('auth:sanctum')->name('infaq.destroy');
});
Route::prefix('zakat')->group(function () {
    Route::get('/', [ZakatController::class, 'index'])->name('zakat.index');
    Route::post('/bayar', [ZakatController::class, 'bayar'])->name('zakat.bayar');
    Route::post('/change-status/{id}', [ZakatController::class, 'changeStatus'])->middleware('auth:sanctum')->name('zakat.change-status');


    Route::post('/', [ZakatController::class, 'store'])->name('zakat.store');
    Route::get('/{id}', [ZakatController::class, 'show'])->middleware('auth:sanctum')->name('zakat.show');
    Route::patch('/{id}', [ZakatController::class, 'update'])->middleware('auth:sanctum')->name('zakat.update');
    Route::delete('/{id}', [ZakatController::class, 'destroy'])->middleware('auth:sanctum')->name('zakat.destroy');
});
Route::prefix('prayer-time')->group(function () {
    Route::get('/', [PrayerTimeController::class, 'index'])->name('prayer-time.index');
    Route::post('/', [PrayerTimeController::class, 'store'])->middleware('auth:sanctum')->name('prayer-time.store');
    Route::get('/{prayer-time}', [PrayerTimeController::class, 'show'])->middleware('auth:sanctum')->name('prayer-time.show');
    Route::patch('/{prayer-time}', [PrayerTimeController::class, 'update'])->middleware('auth:sanctum')->name('prayer-time.update');
    Route::delete('/{prayer-time}', [PrayerTimeController::class, 'destroy'])->middleware('auth:sanctum')->name('prayer-time.destroy');
});

