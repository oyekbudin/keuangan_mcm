<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransaksiCon;
use App\Http\Controllers\TujuanTransaksiController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

/*Route::get('/dashboard', function () {
    //return view('transaksi');
})->middleware(['auth', 'verified'])->name('dashboard');*/

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [TransaksiCon::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Tujuan Transaksi
    Route::post('/tujuan-transaksi', [TujuanTransaksiController::class, 'store'])
        ->name('tujuan-transaksi.store');

    //Transaksi
    Route::post('/transaksi', [TransaksiCon::class, 'store'])
    ->name('transaksi.store');
    Route::delete('/transaksi/{transaksi}', [TransaksiCon::class, 'destroy'])
    ->name('transaksi.destroy');


});

require __DIR__ . '/auth.php';
