<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


// 1. Halaman login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// 2. Halaman utama redirect ke login
Route::get('/', function () {
    return redirect('/login');
});

// 3. Middleware auth untuk melindungi route di bawah
Route::middleware('auth')->group(function () {
   // ADMIN
    Route::get('/beranda-admin', [App\Http\Controllers\BerandaController::class, 'index'])->name('admin.beranda');
    // CRUD hanya admin (bisa dibatasi pakai middleware role jika mau)
    Route::resource('/ikan', App\Http\Controllers\IkanController::class);
    Route::resource('/supplier', App\Http\Controllers\SupplierController::class);
    Route::resource('/pasar', App\Http\Controllers\PasarController::class);
    Route::resource('/konsumen', App\Http\Controllers\KonsumenController::class);
    Route::resource('/beli', App\Http\Controllers\BeliController::class);
    Route::get('/get-ikan-supplier/{supplier_id}', [App\Http\Controllers\BeliController::class, 'getIkanBySupplier']);
    Route::resource('/jual', App\Http\Controllers\JualController::class);
    Route::get('/beli/invoice/{id}', [App\Http\Controllers\BeliController::class, 'invoice'])->name('beli.invoice');
    Route::get('/beli/invoice-pdf/{id}', [App\Http\Controllers\BeliController::class, 'cetakInvoicePDF'])->name('beli.invoice.pdf');
    Route::get('/jual/invoice/{jual}', [App\Http\Controllers\JualController::class, 'invoice'])->name('jual.invoice');
    Route::get('/jual/invoice/{jual}/pdf', [App\Http\Controllers\JualController::class, 'invoicePdf'])->name('jual.invoice.pdf');
    // Laporan Pembelian
    Route::get('/laporan/pembelian', [App\Http\Controllers\LaporanPembelianController::class, 'index'])->name('laporan.pembelian');
    Route::get('/laporan/pembelian/pdf', [App\Http\Controllers\LaporanPembelianController::class, 'cetakPDF'])->name('laporan.pembelian.pdf');
    // Laporan Penjualan
    Route::get('/laporan/penjualan', [App\Http\Controllers\LaporanPenjualanController::class, 'index'])->name('laporan.penjualan');
    Route::get('/laporan/penjualan/cetak', [App\Http\Controllers\LaporanPenjualanController::class, 'cetak'])->name('laporan.penjualan.cetak');
    // Laporan Persediaan (Stok)
    Route::get('/laporan/persediaan', [App\Http\Controllers\LaporanPersediaanController::class, 'index'])->name('laporan.persediaan');
    Route::get('/laporan/persediaan/pdf', [App\Http\Controllers\LaporanPersediaanController::class, 'exportPdf'])->name('laporan.persediaan.pdf');

    // Pencarian
    Route::get('/cari', [App\Http\Controllers\IkanController::class, 'cari']);
    Route::get('/carisp', [App\Http\Controllers\SupplierController::class, 'carisp']);
    Route::get('/carik', [App\Http\Controllers\KonsumenController::class, 'carik']);
    Route::get('/carib', [App\Http\Controllers\BeliController::class, 'carib']);
    Route::get('/caribe', [App\Http\Controllers\BeliController::class, 'caribe']);
    Route::get('/carij', [App\Http\Controllers\JualController::class, 'carij']);
    Route::get('/cariju', [App\Http\Controllers\JualController::class, 'cariju']);

    // 4. Pemilik
    Route::get('/beranda-pemilik', function () {
        return view('pemilik.beranda');
    });
    Route::get('/pemilik/data_ikan', [App\Http\Controllers\DataIkanController::class, 'index'])->name('pemilik.data_ikan');
    Route::get('/pemilik/data_supplier', [App\Http\Controllers\PemilikSupplierController::class, 'index'])->name('pemilik.data_supplier');
    Route::get('/pemilik/data_pasar', [App\Http\Controllers\PemilikPasarController::class, 'index'])->name('pemilik.data_pasar');
    Route::get('/pemilik/data_konsumen', [App\Http\Controllers\PemilikKonsumenController::class, 'index'])->name('pemilik.data_konsumen');
    Route::get('/pemilik/pembelian', [App\Http\Controllers\PemilikPembelianController::class, 'index'])->name('pemilik.pembelian');
    Route::get('/pemilik/penjualan', [App\Http\Controllers\PemilikPenjualanController::class, 'index'])->name('pemilik.penjualan');
    Route::get('/pemilik/penjualan', [App\Http\Controllers\PemilikPenjualanController::class, 'index'])->name('pemilik.penjualan');
Route::get('/pemilik/penjualan/invoice/{jual}', [App\Http\Controllers\JualController::class, 'invoice'])->name('penjualan.invoice');

});



// 5. Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/coba', function () {
    return 'Halaman coba berhasil dibuka!';
});
