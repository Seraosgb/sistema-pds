<?php

// ARQUIVO 4: AS ROTAS (O CAMINHO)
// Instrução: Substitua o conteúdo do seu arquivo de rotas por este.
// Local: routes/web.php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\APRController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/aprs/{apr}/pdf', [APRReportController::class, 'exportToPdf'])->name('aprs.pdf');// Rota para exportar APR para PDF

    // Esta única linha cria todas as rotas necessárias para o CRUD de APRs.
    Route::resource('aprs', APRController::class);
});

require __DIR__.'/auth.php';    