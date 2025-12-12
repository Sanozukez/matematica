<?php

// plataforma/app/Filament/Resources/LessonResource/Pages/EditLesson.php

namespace App\Filament\Resources\LessonResource\Pages;

use App\Filament\Resources\LessonResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLesson extends EditRecord
{
    protected static string $resource = LessonResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $content = $data['content'] ?? [];
        $type = $data['type'] ?? 'text';

        // Preencher campos temporários baseado no tipo
        if ($type === 'video') {
            $data['video_data'] = $content;
        } elseif ($type === 'quiz') {
            $data['quiz_data'] = $content;
        } elseif ($type === 'game') {
            $data['game_data'] = $content;
        }
        
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $type = $data['type'] ?? 'text';
        
        // Mover dados temporários para content
        if ($type === 'video' && isset($data['video_data'])) {
            $data['content'] = $data['video_data'];
        } elseif ($type === 'quiz' && isset($data['quiz_data'])) {
            $data['content'] = $data['quiz_data'];
        } elseif ($type === 'game' && isset($data['game_data'])) {
            $data['content'] = $data['game_data'];
        }
        
        // Limpar campos temporários para não enviar ao Model (embora não estejam no $fillable)
        unset($data['video_data'], $data['quiz_data'], $data['game_data']);
        
        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

