<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\FabricController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\PemartaianController;
use App\Http\Controllers\WipController;
use App\Http\Controllers\QualityFinishController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\StokBarangJadiController;

// 1. Jalur Tamu
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// 2. Jalur Member (Login)
Route::middleware('auth')->group(function () {
    
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Route Kelola User
    Route::resource('users', UserController::class);

    // Buyer
    Route::get('buyers/export', [BuyerController::class, 'export'])->name('buyers.export');
    Route::resource('buyers', BuyerController::class);

    // Fabric
    Route::get('fabrics/export', [FabricController::class, 'export'])->name('fabrics.export');
    Route::resource('fabrics', FabricController::class);

    // Color
    Route::get('colors/export', [ColorController::class, 'export'])->name('colors.export');
    Route::resource('colors', ColorController::class);

    //order
    Route::get('orders/{id}/print', [OrderController::class, 'print'])->name('orders.print');
    Route::resource('orders', OrderController::class);

    // Route Laporan
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');

    // Route Gudang / Penerimaan Kain
    Route::resource('receipts', ReceiptController::class);
    Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');

    // Route Produksi / Pemartaian
    Route::resource('pemartaians', PemartaianController::class);
    // Route Laporan Kain WIP
    Route::get('/wip', [WipController::class, 'index'])->name('wip.index');
    // Route Quality Finish (Barang Jadi)
    Route::resource('quality_finishes', QualityFinishController::class);
    // Route khusus untuk Cetak PDF/Print Surat Jalan
    Route::get('deliveries/{id}/print', [DeliveryController::class, 'print'])->name('deliveries.print');
    // Route Pengiriman (Surat Jalan)
    Route::resource('deliveries', DeliveryController::class);
    // Route Stok Barang Jadi (Gudang Siap Kirim)
Route::get('/stok-barang-jadi', [StokBarangJadiController::class, 'index'])->name('stok_barang_jadi.index');
});