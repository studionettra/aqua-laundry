<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\TransLaundryPickupController;
use App\Http\Controllers\TransOrderController;
use App\Http\Controllers\TypeOfServiceController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Login
Route::get('/', [LoginController::class, 'index'])->name('login');

// app
Route::post('action-login', [LoginController::class, 'actionLogin'])->name('action-login');
Route::post('action-out', [LoginController::class, 'actionLogout'])->name('action-logout');

Route::middleware(['auth', 'nochace'])->group(function () {
    // Semua yang login bisa akses dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Group Administrator & Pimpinan
    Route::middleware(['checklevel:Administrator,Pimpinan'])->group(function () {
        Route::get('report', [ReportController::class, 'index'])->name('report.index');
        Route::get('report/print', [ReportController::class, 'print'])->name('report.print');
    });

    // Group Administrator saja
    Route::middleware(['checklevel:Administrator'])->group(function () {
        Route::resource('user', UserController::class);
        Route::resource('level', LevelController::class);

        // Tax routes
        Route::get('tax', [TaxController::class, 'index'])->name('tax.index');
        Route::put('tax/{id}', [TaxController::class, 'update'])->name('tax.update');
    });

    // Group Administrator & Operator
    Route::middleware(['checklevel:Administrator,Operator'])->group(function () {
        Route::get('transaction/{id}/print', [TransOrderController::class, 'print'])->name('transaction.print');
        Route::patch('transaction/{id}/status', [TransOrderController::class, 'updateStatus'])->name('transaction.update-status');
        Route::resource('customer', CustomerController::class);
        Route::resource('service', TypeOfServiceController::class);
        Route::resource('transaction', TransOrderController::class);
        Route::resource('pickup', TransLaundryPickupController::class);
        Route::resource('expense', ExpenseController::class);
    });
});
