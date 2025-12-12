<?php

// plataforma/app/Filament/Resources/LessonResource/Pages/CreateLesson.php

namespace App\Filament\Resources\LessonResource\Pages;

use App\Filament\Resources\LessonResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLesson extends CreateRecord
{
    protected static string $resource = LessonResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Texto: Builder já salva em 'content' corretamente
        
        // Vídeo
        if (($data['type'] ?? '') === 'video' && isset($data['video_data'])) {
            $data['content'] = $data['video_data'];
        }
        
        // Quiz
        if (($data['type'] ?? '') === 'quiz' && isset($data['quiz_data'])) {
            $data['content'] = $data['quiz_data'];
        }
        
        // Game
        if (($data['type'] ?? '') === 'game' && isset($data['game_data'])) {
            $data['content'] = $data['game_data'];
        }
        
        // Limpar campos temporários
        unset($data['video_data'], $data['quiz_data'], $data['game_data']);
        
        // Garantir array vazio se null
        if (!isset($data['content']) || !is_array($data['content'])) {
            $data['content'] = [];
        }
        
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        // Se for texto, redirecionar para o editor fullscreen (TipTap/Builder)
        if ($this->record->type === 'text') {
            return LessonResource::getUrl('editor', ['record' => $this->record]);
        }

        // Para outros tipos, voltar para a lista
        return LessonResource::getUrl('index');
    }
}