<?php

use App\Http\Controllers\AisleController;
use App\Http\Controllers\BinController;
use App\Http\Controllers\RolePermissionController;
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

    Route::resource('warehouses', WarehouseController::class)->except('show');
    Route::resource('zones', ZoneController::class)->except('show');
    Route::resource('aisles', AisleController::class)->except('show');
    Route::get('bins/{bin}/barcode', [BinController::class, 'barcode'])->name('bins.barcode');
    Route::resource('bins', BinController::class)->except('show');
});
