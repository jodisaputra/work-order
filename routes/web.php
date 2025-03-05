<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\WorkOrderController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Auth::routes(['register' => false]); // Disable registration as users are created by admin

// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Work Orders
Route::middleware(['auth'])->group(function () {
    Route::resource('work-orders', WorkOrderController::class);

    // Work Order Status Update (for operators)
    Route::put('work-orders/{workOrder}/update-status', [WorkOrderController::class, 'updateStatus'])
        ->name('work-orders.update-status');
});

// Reports (for production managers only)
Route::middleware(['auth', 'role:production-manager'])->prefix('reports')->name('reports.')->group(function () {
    Route::get('/summary', [ReportController::class, 'summary'])->name('summary');
    Route::get('/operator-performance', [ReportController::class, 'operatorPerformance'])->name('operator-performance');
});
