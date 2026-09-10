<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ZonaController;
use App\Http\Controllers\AlertaController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ReporteController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ============================================
// RUTAS DE AUTENTICACIÓN
// ============================================
Route::middleware('auth')->group(function () {
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/// ============================================
// RUTAS DE ZONAS
// ============================================
Route::middleware('auth')->group(function () {
    Route::get('/zonas', [ZonaController::class, 'index'])->name('zonas.index');

    Route::middleware('esAdmin')->group(function () {
        Route::get('/zonas/create', [ZonaController::class, 'create'])->name('zonas.create');
        Route::post('/zonas', [ZonaController::class, 'store'])->name('zonas.store');
    });

    Route::get('/zonas/{zona}', [ZonaController::class, 'show'])->name('zonas.show');

    Route::middleware('esAdmin')->group(function () {
        Route::get('/zonas/{zona}/edit', [ZonaController::class, 'edit'])->name('zonas.edit');
        Route::put('/zonas/{zona}', [ZonaController::class, 'update'])->name('zonas.update');
        Route::delete('/zonas/{zona}', [ZonaController::class, 'destroy'])->name('zonas.destroy');
    });
});

// ============================================
// RUTAS DE EMPLEADOS (SOLO ADMIN)
// ============================================
Route::middleware(['auth', 'esAdmin'])->group(function () {
    Route::resource('empleados', EmpleadoController::class);
});

// ============================================
// RUTAS DE ALERTAS
// ============================================
Route::middleware('auth')->group(function () {
    // 👇 RUTA PERSONALIZADA ANTES QUE LAS DEMÁS
    Route::get('/alertas/pdf', [AlertaController::class, 'exportarPDF'])->name('alertas.pdf');
    
    Route::get('/alertas', [AlertaController::class, 'index'])->name('alertas.index');
    Route::post('/alertas', [AlertaController::class, 'store'])->name('alertas.store');
    Route::put('/alertas/{id}/atender', [AlertaController::class, 'atender'])->name('alertas.atender');
    Route::delete('/alertas/{id}', [AlertaController::class, 'destroy'])->name('alertas.destroy');
});

// ============================================
// RUTAS DE REPORTES (SOLO ADMIN)
// ============================================
Route::middleware(['auth', 'esAdmin'])->group(function () {
    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('/reportes/pdf', [ReporteController::class, 'exportarPDF'])->name('reportes.pdf');
});

require __DIR__.'/auth.php';