<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketCommentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Events\TicketPushed;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. HALAMAN DEPAN (GUEST)
Route::get('/', function () {
    return view('welcome');
});

// Route::get('/test-notif', function () {
//     broadcast(new TicketPushed('Ada tiket darurat baru saja masuk!'));
//     return "Notifikasi telah ditembakkan ke WebSocket!";
// });

// 2. GROUP RUTE AUTH (Bisa diakses Staff & Admin)
Route::middleware(['auth', 'verified'])->group(function () {

    // --- MAIN LANDING PAGE (TIKET SAYA) ---
    // (Dihapus karena conflict dan method tickets tidak ada, menggunakan Route::resource)

    // --- FITUR KOMENTAR (DISKUSI) ---
    // PENTING: Ditaruh di dalam auth group agar hanya user login yang bisa komen
    Route::post('/tickets/{ticket}/comments', [TicketCommentController::class, 'store'])->name('comments.store');

    // --- MANAJEMEN TIKET (CRUD UMUM) ---
    // Staff boleh Create, Store, Show, Edit, Update.
    // TAPI: Staff TIDAK BOLEH 'destroy' (Hapus). Kita kecualikan method itu.
    Route::resource('tickets', TicketController::class)->except(['destroy']);
    // TAMBAHKAN INI: Rute Khusus Reopen Tiket
    Route::patch('/tickets/{ticket}/reopen', [TicketController::class, 'reopen'])->name('tickets.reopen');



    // --- PANDUAN / BANTUAN ---
    Route::get('/panduan', function () {
        return view('panduan');
    })->name('panduan');

    // --- PROFILE ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// 3. GROUP RUTE KHUSUS ADMIN (Satpam 'admin' berjaga di sini)
Route::middleware(['auth', 'admin'])->group(function () {

    // --- DASHBOARD ADMIN ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- EXPORT DATA (SENSITIF) ---
    Route::get('/tickets/export/data', [TicketController::class, 'export'])->name('tickets.export');

    // --- HAPUS TIKET (SENSITIF) ---
    // Rute delete kita taruh manual di sini supaya aman dari user biasa
    Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])->name('tickets.destroy');

    // --- MANAJEMEN USER ---
    // Cukup satu baris ini, Laravel otomatis membuatkan route:
    // index, create, store, show, edit, update, destroy
    Route::resource('users', UserController::class);
    // Route perangkat (devices)
    Route::get('/devices', [\App\Http\Controllers\DeviceController::class, 'index'])->name('devices.index');
    Route::get('/devices/export/csv', [\App\Http\Controllers\DeviceController::class, 'export'])->name('devices.export');
    Route::get('/devices/{device}', [\App\Http\Controllers\DeviceController::class, 'show'])->name('devices.show');
    Route::get('/devices/{device}/report', [\App\Http\Controllers\DeviceController::class, 'showReport'])->name('devices.report');
});


require __DIR__ . '/auth.php';
