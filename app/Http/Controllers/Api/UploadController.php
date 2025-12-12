<?php

// plataforma/app/Http/Controllers/Api/UploadController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StorageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Controller para upload de arquivos
 * 
 * Gerencia uploads de imagens para o Editor.js
 * 
 * Regra de Ouro: NUNCA salva hotlinks externos.
 * Sempre faz download e salva no nosso storage (local ou Bunny.net)
 */
class UploadController extends Controller
{
    public function __construct(
        private StorageService $storageService
    ) {}

    /**
     * Upload de imagem para o Editor.js
     * 
     * Suporta dois métodos:
     * 1. Upload direto de arquivo (byFile)
     * 2. Download de URL externa (byUrl) - REGRA DE OURO aplicada aqui
     * 
     * @return JsonResponse Formato esperado pelo Editor.js ImageTool
     */
    public function image(Request $request): JsonResponse
    {
        try {
            // Upload por URL externa (Ctrl+V, importação, etc)
            if ($request->has('byUrl') && $request->has('url')) {
                return $this->fetchUrl($request->input('url'));
            }

            // Upload por arquivo local (drag & drop, seleção)
            if ($request->hasFile('image')) {
                return $this->uploadFile($request->file('image'));
            }

            return $this->errorResponse('Nenhuma imagem enviada. Envie um arquivo ou uma URL.');
        } catch (\InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage());
        } catch (\RuntimeException $e) {
            Log::error('Erro ao fazer upload de imagem', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return $this->errorResponse('Erro ao processar imagem. Tente novamente.');
        } catch (\Exception $e) {
            Log::error('Erro inesperado no upload', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return $this->errorResponse('Erro inesperado. Contate o suporte.');
        }
    }

    /**
     * Faz upload de um arquivo local
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @return JsonResponse
     */
    private function uploadFile($file): JsonResponse
    {
        $result = $this->storageService->uploadFile($file);

        // Retorna no formato esperado pelo Editor.js ImageTool
        return response()->json([
            'success' => 1,
            'file' => [
                'url' => $result['url'],
            ],
        ]);
    }

    /**
     * Faz download de uma imagem externa e salva no nosso storage
     * 
     * REGRA DE OURO: Nunca salva hotlinks. Sempre faz download e salva localmente.
     * 
     * @param string $url URL da imagem externa
     * @return JsonResponse
     */
    private function fetchUrl(string $url): JsonResponse
    {
        // Faz download e salva no storage
        $result = $this->storageService->fetchAndStoreImage($url);

        // Retorna no formato esperado pelo Editor.js ImageTool
        return response()->json([
            'success' => 1,
            'file' => [
                'url' => $result['url'],
            ],
        ]);
    }

    /**
     * Resposta de erro padrão no formato do Editor.js
     */
    private function errorResponse(string $message): JsonResponse
    {
        return response()->json([
            'success' => 0,
            'message' => $message,
        ], 400);
    }
}

