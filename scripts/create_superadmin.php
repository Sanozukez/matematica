<?php
// scripts/create_superadmin.php
// Creates or updates a super admin user using Spatie roles.

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$email = 'sanozukez@gmail.com';
$name = 'Aureo';
$password = bcrypt('admin');
$verifiedAt = now();

$user = App\Models\User::updateOrCreate(
    ['email' => $email],
    [
        'name' => $name,
        'password' => $password,
        'email_verified_at' => $verifiedAt,
    ]
);

// Ensure role exists
$roleName = 'super_admin';
Spatie\Permission\Models\Role::findOrCreate($roleName, 'web');

$user->assignRole($roleName);

echo "Created/updated: {$user->email}\n";
