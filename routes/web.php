<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InventoryMovementController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\RepairController;
use App\Http\Controllers\RepairAssistantController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureUserHasRole;

Route::get('/', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    // Inventario y clientes accesibles para usuarios autenticados
    Route::resource('inventory', ProductController::class)
        ->only(['index', 'show'])
        ->parameters(['inventory' => 'product']);
    Route::resource('customers', CustomerController::class)->except(['destroy']);
    Route::get('/repairs/assistant', [RepairAssistantController::class, 'index'])->name('repairs.assistant');
    Route::post('/repairs/assistant', [RepairAssistantController::class, 'message'])->name('repairs.assistant.message');

    // Servicios y proveedores: sólo administradores
    Route::middleware([EnsureUserHasRole::class . ':admin'])->group(function () {
        Route::resource('inventory', ProductController::class)
            ->except(['index', 'show'])
            ->parameters(['inventory' => 'product']);
        Route::post('inventory/{product}/movements', [InventoryMovementController::class, 'store'])->name('inventory.movements.store');
        Route::resource('services', ServiceController::class);
        Route::resource('providers', ProviderController::class);
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::patch('users/{user}/role', [UserController::class, 'updateRole'])->name('users.role');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // Reparaciones: accesible para usuarios y administradores
    Route::resource('repairs', RepairController::class)->except(['destroy']);

    Route::middleware([EnsureUserHasRole::class . ':admin'])->group(function () {
        Route::delete('customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
        Route::delete('repairs/{repair}', [RepairController::class, 'destroy'])->name('repairs.destroy');
    });
});

Route::middleware(['guest'])->group(function () {
    Route::get('/login', function () { return view('auth.login'); })->name('login');
    Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
    Route::get('/register', function () { return view('auth.register'); })->name('register');
    Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);
});

Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout')->middleware('auth');

require __DIR__.'/auth.php';