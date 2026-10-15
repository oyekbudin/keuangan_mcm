<?php

use App\Http\Controllers\ImportDraftController;
use App\Http\Controllers\ImportTransaksiController;
use App\Http\Controllers\LaporanCon;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransaksiCon;
use App\Http\Controllers\TujuanTransaksiController;
use App\Http\Controllers\AkunController;
use Illuminate\Support\Facades\Route;


/*Route::get('/', function () {
    return view('welcome');
});*/

/*Route::get('/dashboard', function () {
    //return view('transaksi');
})->middleware(['auth', 'verified'])->name('dashboard');*/

Route::middleware('auth')->group(function () {
    Route::get('/', [TransaksiCon::class, 'index']);
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

    //AkunController
    Route::get('/akun', [AkunController::class, 'index'])
        ->name('akun.index');

    Route::post('/akun', [AkunController::class, 'store'])
        ->name('akun.store');

    Route::delete('/akun/{akun}', [AkunController::class, 'destroy'])
        ->name('akun.destroy');

    //LaporanCon
    Route::get('/laporan', [LaporanCon::class, 'index'])->name('laporan.index');

    Route::get('/laporan/bos/{tahun}/{bulan}', [LaporanCon::class, 'bos'])
        ->name('laporan.bos.pdf');

    Route::get('/laporan/bos/{tahun}/{bulan}/excel', [
        LaporanCon::class,
        'exportExcel',
    ])->name('laporan.bos.excel');

    Route::get('/laporan/bos/{tahun}/{bulan}/zip', [
        LaporanCon::class,
        'exportZip',
    ])->name('laporan.bos.zip');

    //ImportTransaksiController
    Route::get('/import-transaksi', [
        ImportTransaksiController::class,
        'index'
    ])->name('import-transaksi.index');
    Route::get('/import-transaksi/template', [
        ImportTransaksiController::class,
        'template'
    ])->name('import-transaksi.template');
    Route::post('/import-transaksi/preview', [
        App\Http\Controllers\ImportTransaksiController::class,
        'preview',
    ])->name('import-transaksi.preview');

    Route::post('/import-transaksi/konfirmasi', [
        App\Http\Controllers\ImportTransaksiController::class,
        'simpan',
    ])->name('import-transaksi.simpan');

    //ImportDraftController
    Route::get('/import-transaksi/drafts', [
        ImportDraftController::class,
        'index',
    ])->name('import-drafts.index');

    Route::post('/import-transaksi/drafts', [
        ImportDraftController::class,
        'store',
    ])->name('import-drafts.store');

    Route::get('/import-transaksi/drafts/{draft}', [
        ImportDraftController::class,
        'show',
    ])->name('import-drafts.show');

    Route::delete('/import-transaksi/drafts/{draft}', [
        ImportDraftController::class,
        'destroy',
    ])->name('import-drafts.destroy');
    Route::patch(
        '/import-transaksi/drafts/{draft}/items/{item}/checkbox',
        [ImportDraftController::class, 'toggleCheckbox']
    )->name('import-drafts.items.checkbox');
    Route::post(
        '/import-transaksi/drafts/{draft}/checkboxes',
        [ImportDraftController::class, 'saveCheckboxes']
    )->name('import-drafts.checkboxes.save');
    Route::patch(
        '/import-transaksi/drafts/{draft}/transaksi/{transaksi}/tujuan',
        [ImportDraftController::class, 'updateTujuanTransaksi']
    )->name('import-drafts.transaksi.tujuan.update');
    Route::patch('/transaksi/{transaksi}/tujuan', [
        TransaksiCon::class,
        'updateTujuan'
    ])->name('transaksi.update-tujuan');

    Route::get(
        '/import-drafts/{draft}/tujuan',
        [ImportDraftController::class, 'tujuan']
    )->name('import-drafts.tujuan');

    Route::post(
        '/import-drafts/{draft}/tujuan',
        [ImportDraftController::class, 'simpanTujuan']
    )->name('import-drafts.tujuan.simpan');

    Route::post(
        '/import-drafts/{draft}/tujuan/autosave',
        [ImportDraftController::class, 'autosaveTujuan']
    )->name('import-drafts.tujuan.autosave');
    Route::post(
        '/import-drafts/{draft}/simpan-transaksi',
        [ImportDraftController::class, 'simpanTransaksi']
    )->name('import-drafts.simpan-transaksi');




});

require __DIR__ . '/auth.php';
