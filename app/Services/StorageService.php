<?php

// plataforma/app/Services/StorageService.php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Serviço de Storage para upload de arquivos
 * 
 * Gerencia uploads de imagens com suporte para:
 * - Storage local (temporário)
 * - Bunny.net (preparado para ativação futura)
 * 
 * Abstrai a lógica de storage para facilitar migração entre providers
 */
class StorageService
{
    /**
     * Disco de storage atual
     * Pode ser 'local', 'public' ou 'bunny' (quando configurado)
     */
    private string $disk;

    /**
     * Base path para imagens de lições
     */
    private const LESSONS_IMAGE_PATH = 'lessons/images';

    /**
     * Tamanho máximo de imagem (10MB)
     */
    private const MAX_IMAGE_SIZE = 10 * 1024 * 1024;

    /**
     * Extensões permitidas
     */
    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];

    public function __construct()
    {
        // Determina o disco baseado na configuração
        // Por enquanto usa 'public' (storage local)
        // Quando Bunny.net estiver configurado, usar 'bunny'
        $this->disk = $this->getActiveDisk();
    }

    /**
     * Retorna o disco ativo baseado na configuração
     */
    private function getActiveDisk(): string
    {
        // Verifica se Bunny.net está configurado
        if ($this->isBunnyConfigured()) {
            return 'bunny';
        }

        // Usa storage público local
        return 'public';
    }

    /**
     * Verifica se Bunny.net está configurado
     */
    private function isBunnyConfigured(): bool
    {
        return !empty(env('BUNNY_STORAGE_ZONE'))
            && !empty(env('BUNNY_STORAGE_API_KEY'))
            && !empty(env('BUNNY_CDN_URL'));
    }

    /**
     * Faz upload de um arquivo local
     * 
     * @param \Illuminate\Http\UploadedFile $file Arquivo enviado
     * @param string|null $subfolder Subpasta opcional (ex: '2024/12')
     * @return array ['url' => string, 'path' => string]
     */
    public function uploadFile($file, ?string $subfolder = null): array
    {
        // Validações
        $this->validateFile($file);

        // Gera nome único
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = Str::uuid() . '.' . $extension;

        // Define path
        $path = $this->buildPath($subfolder, $filename);

        // Salva arquivo
        $storedPath = $file->storeAs(
            dirname($path),
            basename($path),
            $this->disk
        );

        if (!$storedPath) {
            throw new \RuntimeException('Erro ao salvar arquivo no storage');
        }

        // Retorna URL pública e path
        return [
            'url' => $this->getPublicUrl($storedPath),
            'path' => $storedPath,
        ];
    }

    /**
     * Faz download de uma imagem externa e salva no storage
     * 
     * @param string $url URL da imagem externa
     * @param string|null $subfolder Subpasta opcional
     * @return array ['url' => string, 'path' => string]
     */
    public function fetchAndStoreImage(string $url, ?string $subfolder = null): array
    {
        // Valida URL
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException('URL inválida');
        }

        // Faz download da imagem
        $response = Http::timeout(30)->get($url);

        if (!$response->successful()) {
            throw new \RuntimeException('Erro ao baixar imagem: ' . $response->status());
        }

        // Valida conteúdo
        $content = $response->body();
        $contentType = $response->header('Content-Type', '');

        if (!str_starts_with($contentType, 'image/')) {
            throw new \InvalidArgumentException('URL não retorna uma imagem válida');
        }

        // Valida tamanho
        if (strlen($content) > self::MAX_IMAGE_SIZE) {
            throw new \RuntimeException('Imagem muito grande. Máximo: ' . (self::MAX_IMAGE_SIZE / 1024 / 1024) . 'MB');
        }

        // Detecta extensão do Content-Type ou da URL
        $extension = $this->detectExtension($url, $contentType);

        // Gera nome único
        $filename = Str::uuid() . '.' . $extension;
        $path = $this->buildPath($subfolder, $filename);

        // Salva no storage
        $stored = Storage::disk($this->disk)->put($path, $content);

        if (!$stored) {
            throw new \RuntimeException('Erro ao salvar imagem no storage');
        }

        return [
            'url' => $this->getPublicUrl($path),
            'path' => $path,
        ];
    }

    /**
     * Constrói o path completo do arquivo
     */
    private function buildPath(?string $subfolder, string $filename): string
    {
        $basePath = self::LESSONS_IMAGE_PATH;

        if ($subfolder) {
            $basePath .= '/' . trim($subfolder, '/');
        } else {
            // Organiza por ano/mês automaticamente
            $basePath .= '/' . date('Y/m');
        }

        return $basePath . '/' . $filename;
    }

    /**
     * Retorna a URL pública do arquivo
     */
    private function getPublicUrl(string $path): string
    {
        if ($this->disk === 'bunny') {
            // URL do CDN Bunny.net
            $cdnUrl = rtrim(env('BUNNY_CDN_URL'), '/');
            return $cdnUrl . '/' . $path;
        }

        // URL do storage local público
        return Storage::disk($this->disk)->url($path);
    }

    /**
     * Detecta a extensão do arquivo baseado na URL ou Content-Type
     */
    private function detectExtension(string $url, string $contentType): string
    {
        // Tenta extrair da URL
        $urlPath = parse_url($url, PHP_URL_PATH);
        if ($urlPath) {
            $ext = strtolower(pathinfo($urlPath, PATHINFO_EXTENSION));
            if (in_array($ext, self::ALLOWED_EXTENSIONS)) {
                return $ext;
            }
        }

        // Tenta extrair do Content-Type
        if (preg_match('/image\/(jpeg|jpg|png|gif|webp|svg\+xml)/i', $contentType, $matches)) {
            $ext = strtolower($matches[1]);
            if ($ext === 'jpg' || $ext === 'jpeg') {
                return 'jpg';
            }
            if ($ext === 'svg+xml') {
                return 'svg';
            }
            return $ext;
        }

        // Default: jpg
        return 'jpg';
    }

    /**
     * Valida arquivo antes do upload
     */
    private function validateFile($file): void
    {
        if (!$file || !$file->isValid()) {
            throw new \InvalidArgumentException('Arquivo inválido');
        }

        if ($file->getSize() > self::MAX_IMAGE_SIZE) {
            throw new \InvalidArgumentException(
                'Arquivo muito grande. Máximo: ' . (self::MAX_IMAGE_SIZE / 1024 / 1024) . 'MB'
            );
        }

        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, self::ALLOWED_EXTENSIONS)) {
            throw new \InvalidArgumentException(
                'Formato não permitido. Use: ' . implode(', ', self::ALLOWED_EXTENSIONS)
            );
        }
    }

    /**
     * Deleta um arquivo do storage
     */
    public function deleteFile(string $path): bool
    {
        return Storage::disk($this->disk)->delete($path);
    }

    /**
     * Verifica se um arquivo existe
     */
    public function fileExists(string $path): bool
    {
        return Storage::disk($this->disk)->exists($path);
    }
}

