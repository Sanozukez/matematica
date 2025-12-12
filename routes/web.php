<?php

// plataforma/routes/web.php

use App\Http\Controllers\App\DashboardController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\EnsureUserIsStudent;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Rotas da aplicação organizadas por contexto:
| - Públicas: Home, listagem de cursos, skill tree preview
| - Auth: Login, registro, logout
| - App: Área do aluno (requer autenticação)
|
*/

// =============================================================================
// ROTAS PÚBLICAS
// =============================================================================

/**
 * Página inicial
 */
Route::get('/', [HomeController::class, 'index'])->name('home');

/**
 * Catálogo de cursos (público)
 */
Route::get('/cursos', function () {
    return inertia('Courses/Index');
})->name('courses.index');

/**
 * Skill Tree Preview (público - marketing)
 */
Route::get('/skill-tree', function () {
    return inertia('SkillTree/Index');
})->name('skill-tree.index');

// =============================================================================
// ROTAS DE AUTENTICAÇÃO
// =============================================================================

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// =============================================================================
// ÁREA DO ALUNO (/app)
// =============================================================================

Route::prefix('app')
    ->middleware(['auth', EnsureUserIsStudent::class])
    ->name('app.')
    ->group(function () {
        /**
         * Dashboard do aluno
         */
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        /**
         * Meus cursos (em andamento)
         */
        Route::get('/cursos', function () {
            return inertia('App/Courses/Index');
        })->name('courses.index');

        /**
         * Skill Tree pessoal
         */
        Route::get('/skill-tree', function () {
            return inertia('App/SkillTree/Index');
        })->name('skill-tree.index');

        /**
         * Minhas badges
         */
        Route::get('/badges', function () {
            return inertia('App/Badges/Index');
        })->name('badges.index');
    });
