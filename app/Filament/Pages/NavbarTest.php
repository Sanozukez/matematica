<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class NavbarTest extends Page
{
    protected static ?string $navigationLabel = 'Navbar Test';
    protected static ?string $title = 'Navbar Test';
    protected static ?int $navigationSort = 999;
    protected static string $view = 'filament.pages.navbar-test';

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
}
