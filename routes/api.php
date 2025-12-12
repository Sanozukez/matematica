
<?php

// plataforma/routes/api.php

use App\Http\Controllers\Api\UploadController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Rotas de API para funcionalidades como upload de arquivos.
| Protegidas por autenticação quando necessário.
|
*/

/**
 * Upload de imagens para o Editor.js
 * Requer autenticação
 */
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/upload/image', [UploadController::class, 'image'])
        ->name('api.upload.image');
});

/**
 * Rota alternativa para uploads do painel admin (sessão web)
 */
Route::middleware('web')->group(function () {
    Route::post('/admin/upload/image', [UploadController::class, 'image'])
        ->name('api.admin.upload.image')
        ->middleware('auth');
});

