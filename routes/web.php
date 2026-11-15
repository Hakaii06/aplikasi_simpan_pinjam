<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::resource('anggota', MemberController::class)->parameters(['anggota' => 'member'])->except(['show']);
    Route::resource('transaksi', TransactionController::class)->parameters(['transaksi' => 'transaction'])->except(['show']);
    Route::get('/laporan/anggota.pdf', [ReportController::class, 'members'])->name('reports.members');
    Route::get('/laporan/transaksi.pdf', [ReportController::class, 'transactions'])->name('reports.transactions');
});
