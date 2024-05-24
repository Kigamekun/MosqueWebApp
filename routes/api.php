<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{BlogController, ActivityController, ReservasiController, InfaqController, ZakatController, PrayerTimeController};

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);

Route::get('/getFile/{folder}/{filename}', function ($folder,$filename) {
    return response()->file(storage_path('app/public/').$folder.'/'.$filename);
});

Route::prefix('blog')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('blog.index');
    Route::post('/', [BlogController::class, 'store'])->name('blog.store');
    Route::get('/{id}', [BlogController::class, 'show'])->name('blog.show');
    Route::post('/{id}', [BlogController::class, 'update'])->name('blog.update');
    Route::delete('/{id}', [BlogController::class, 'destroy'])->name('blog.destroy');
});
Route::prefix('activity')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [ActivityController::class, 'index'])->name('activity.index');
    Route::post('/', [ActivityController::class, 'store'])->name('activity.store');
    Route::get('/{id}', [ActivityController::class, 'show'])->name('activity.show');
    Route::post('/{id}', [ActivityController::class, 'update'])->name('activity.update');
    Route::delete('/{id}', [ActivityController::class, 'destroy'])->name('activity.destroy');
});
Route::prefix('reservasi')->group(function () {
    Route::get('/', [ReservasiController::class, 'index'])->name('reservasi.index');
    Route::post('/', [ReservasiController::class, 'store'])->name('reservasi.store');
    Route::get('/{id}', [ReservasiController::class, 'show'])->name('reservasi.show');
    Route::post('/{id}', [ReservasiController::class, 'update'])->name('reservasi.update');
    Route::delete('/{id}', [ReservasiController::class, 'destroy'])->name('reservasi.destroy');
});
Route::prefix('infaq')->group(function () {
    Route::get('/', [InfaqController::class, 'index'])->name('infaq.index');
    Route::post('/', [InfaqController::class, 'store'])->name('infaq.store');
    Route::get('/{infaq}', [InfaqController::class, 'show'])->name('infaq.show');
    Route::patch('/{infaq}', [InfaqController::class, 'update'])->name('infaq.update');
    Route::delete('/{infaq}', [InfaqController::class, 'destroy'])->name('infaq.destroy');
});
Route::prefix('zakat')->group(function () {
    Route::get('/', [ZakatController::class, 'index'])->name('zakat.index');
    Route::post('/', [ZakatController::class, 'store'])->name('zakat.store');
    Route::get('/{zakat}', [ZakatController::class, 'show'])->name('zakat.show');
    Route::patch('/{zakat}', [ZakatController::class, 'update'])->name('zakat.update');
    Route::delete('/{zakat}', [ZakatController::class, 'destroy'])->name('zakat.destroy');
});
Route::prefix('prayer-time')->group(function () {
    Route::get('/', [PrayerTimeController::class, 'index'])->name('prayer-time.index');
    Route::post('/', [PrayerTimeController::class, 'store'])->name('prayer-time.store');
    Route::get('/{prayer-time}', [PrayerTimeController::class, 'show'])->name('prayer-time.show');
    Route::patch('/{prayer-time}', [PrayerTimeController::class, 'update'])->name('prayer-time.update');
    Route::delete('/{prayer-time}', [PrayerTimeController::class, 'destroy'])->name('prayer-time.destroy');
});

