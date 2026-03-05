<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\FabricController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\PemartaianController;
use App\Http\Controllers\StokWipController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\ReturController;
use App\Http\Controllers\LaporanStokGreigeController;
use App\Http\Controllers\TutupBukuController;
use App\Http\Controllers\DyestuffController;
use App\Http\Controllers\ChemicalController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\ProcessController;
use App\Http\Controllers\JobTicketController;
use App\Http\Controllers\OrderRecipeController;

// ==========================================
// 1. JALUR TAMU (Belum Login)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// ==========================================
// 2. JALUR MEMBER (Sudah Login)
// ==========================================
Route::middleware('auth')->group(function () {
    
    // 👇 SATPAM 1: Saat baru buka web (/)
    Route::get('/', function () {
        if (auth()->user()->role == 'laborat') {
            return redirect()->route('job-tickets.index'); // Belok ke Laborat
        }
        return redirect()->route('dashboard'); // Ke Smart Order
    });

    // 👇 SATPAM 2: Saat mencoba buka (/dashboard)
    Route::get('/dashboard', function () {
        if (auth()->user()->role == 'laborat') {
            return redirect()->route('job-tickets.index'); // Usir ke Laborat
        }
        // Jika bukan laborat, panggil fungsi DashboardController seperti biasa
        return app(\App\Http\Controllers\DashboardController::class)->index();
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ------------------------------------------
    // A. KELOMPOK ROUTE SMART ORDER UTAMA
    // ------------------------------------------
    Route::resource('users', UserController::class);

    Route::get('buyers/export', [BuyerController::class, 'export'])->name('buyers.export');
    Route::resource('buyers', BuyerController::class);

    Route::get('fabrics/export', [FabricController::class, 'export'])->name('fabrics.export');
    Route::resource('fabrics', FabricController::class);

    Route::get('colors/export', [ColorController::class, 'export'])->name('colors.export');
    Route::resource('colors', ColorController::class);

    Route::get('orders/{id}/print', [OrderController::class, 'print'])->name('orders.print');
    Route::resource('orders', OrderController::class);

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');

    Route::get('/receipts/details', [ReceiptController::class, 'details'])->name('receipts.details');
    Route::resource('receipts', ReceiptController::class);
    Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');
    Route::resource('returs', ReturController::class);
    Route::get('laporan-stok-greige', [LaporanStokGreigeController::class, 'index'])->name('laporan.stok_greige');
    
    Route::get('pemartaians/details', [PemartaianController::class, 'details'])->name('pemartaians.details');
    Route::resource('pemartaians', PemartaianController::class);
    Route::get('stok-wip', [StokWipController::class, 'index'])->name('wip.index');
    Route::get('laporan-stok-wip', [StokWipController::class, 'laporan'])->name('wip.laporan');
    
    Route::get('deliveries/{id}/print', [DeliveryController::class, 'print'])->name('deliveries.print');
    Route::resource('deliveries', DeliveryController::class);
    
    Route::get('tutup-buku', [TutupBukuController::class, 'index'])->name('tutup_buku.index');
    Route::post('tutup-buku', [TutupBukuController::class, 'store'])->name('tutup_buku.store');
    Route::post('tutup-buku/open/{id}', [TutupBukuController::class, 'open'])->name('tutup_buku.open');


    // ------------------------------------------
    // B. KELOMPOK ROUTE KHUSUS LABORAT
    // ------------------------------------------
    Route::resource('dyestuffs', DyestuffController::class);
    Route::resource('chemicals', ChemicalController::class);
    Route::resource('machines', MachineController::class);
    Route::resource('processes', ProcessController::class);

    Route::get('/job-tickets/get-order/{id}', [JobTicketController::class, 'getOrderDetails']);
    Route::get('/job-tickets/{id}/print', [JobTicketController::class, 'print'])->name('job-tickets.print');
    Route::get('/job-tickets/get-recipe/{order_id}/{color_id}', [JobTicketController::class, 'getRecipe']);
    Route::resource('job-tickets', JobTicketController::class);
    Route::resource('order-recipes', OrderRecipeController::class);

});