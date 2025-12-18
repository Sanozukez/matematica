<?php
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::Class);
$kernel->bootstrap();
echo view('filament.resources.lesson-resource.pages.edit-lesson-fullscreen')->render();
