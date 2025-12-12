<?php

// plataforma/app/Models/User.php

namespace App\Models;

use App\Domain\Badge\Models\Badge;
use App\Domain\Progress\Models\UserBadge;
use App\Domain\Progress\Models\UserProgress;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

/**
 * Model de Usuário
 * 
 * Representa todos os tipos de usuários do sistema:
 * - super_admin: Administrador do SaaS
 * - creator: Criador de cursos
 * - student: Aluno/consumidor de cursos
 * 
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 */
class User extends Authenticatable implements FilamentUser
{
    use HasFactory;
    use HasPanelShield;
    use HasRoles;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Determina se o usuário pode acessar o painel Filament
     * 
     * Apenas super_admin e creator podem acessar
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole(['super_admin', 'creator']);
    }

    /**
     * Badges conquistadas pelo usuário
     */
    public function badges(): BelongsToMany
    {
        return $this->belongsToMany(Badge::class, 'user_badges')
            ->withPivot('unlocked_at')
            ->withTimestamps();
    }

    /**
     * Registros de badges do usuário (para queries mais complexas)
     */
    public function userBadges(): HasMany
    {
        return $this->hasMany(UserBadge::class);
    }

    /**
     * Progresso do usuário nas lições
     */
    public function progress(): HasMany
    {
        return $this->hasMany(UserProgress::class);
    }

    /**
     * Verifica se é Super Admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    /**
     * Verifica se é Criador de Cursos
     */
    public function isCreator(): bool
    {
        return $this->hasRole('creator');
    }

    /**
     * Verifica se é Aluno
     */
    public function isStudent(): bool
    {
        return $this->hasRole('student');
    }

    /**
     * Total de pontos acumulados (soma dos pontos das badges)
     */
    public function getTotalPointsAttribute(): int
    {
        return $this->badges()->sum('points');
    }
}
