<?php

use App\Http\Controllers\AisleController;
use App\Http\Controllers\BinController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\ZoneController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('roles', [RolePermissionController::class, 'index'])->name('roles.index');
    Route::get('roles/{role}/permissions', [RolePermissionController::class, 'edit'])->name('roles.permissions.edit');
    Route::put('roles/{role}/permissions', [RolePermissionController::class, 'update'])->name('roles.permissions.update');

    Route::resource('warehouses', WarehouseController::class);
    Route::resource('zones', ZoneController::class);
    Route::resource('aisles', AisleController::class);
    Route::get('bins/{bin}/barcode', [BinController::class, 'barcode'])->name('bins.barcode');
    Route::resource('bins', BinController::class)->except('show');

    // Tenant-specific routes
    Route::middleware(['tenant'])->group(function () {
        // User management
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/',                      [UserController::class, 'index'])->name('index');
            Route::get('/create',                [UserController::class, 'create'])->name('create');
            Route::post('/',                     [UserController::class, 'store'])->name('store');
            Route::get('/{user}/edit',           [UserController::class, 'edit'])->name('edit');
            Route::put('/{user}',                [UserController::class, 'update'])->name('update');
            Route::patch('/{user}/deactivate',   [UserController::class, 'deactivate'])->name('deactivate');
            Route::patch('/{user}/reactivate',   [UserController::class, 'reactivate'])->name('reactivate');
            Route::get('/{user}/activity-log',   [UserController::class, 'activityLog'])->name('activity-log');
        });
    });
});
