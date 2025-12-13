<?php

namespace App\Filament\Resources\LessonResource\Pages;

use App\Filament\Resources\LessonResource;
use Filament\Resources\Pages\Page;

class EditLessonFullscreen extends Page
{
    protected static string $resource = LessonResource::class;

    protected static string $view = 'filament.resources.lesson-resource.pages.edit-lesson-fullscreen';

    protected static ?string $slug = 'editor';

    protected static ?string $title = 'Editor de Lição';

    protected static string $layout = 'layouts.editor';

    public static function shouldRegisterNavigation(array $parameters = []): bool
    {
        return false;
    }
}
