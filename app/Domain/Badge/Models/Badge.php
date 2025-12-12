<?php

// plataforma/app/Domain/Badge/Models/Badge.php

namespace App\Domain\Badge\Models;

use App\Domain\Module\Models\Module;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo de Badge (Conquista)
 * 
 * Representa uma conquista que o usuário pode desbloquear
 * Associada a um módulo e pode ter pré-requisitos (outras badges)
 * 
 * @property string $id UUID da badge
 * @property string|null $module_id UUID do módulo associado
 * @property string $name Nome da badge
 * @property string $slug Slug único
 * @property string|null $description Descrição da conquista
 * @property string|null $icon Emoji ou ícone
 * @property string|null $color Cor da badge (hex)
 * @property int $points Pontos concedidos ao desbloquear
 * @property bool $is_active Se está ativa
 */
class Badge extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $fillable = [
        'module_id',
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'points',
        'is_active',
    ];

    protected $casts = [
        'points' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Módulo associado a esta badge
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Badges que são pré-requisito para esta
     * (Dependências - DAG)
     */
    public function prerequisites(): BelongsToMany
    {
        return $this->belongsToMany(
            Badge::class,
            'badge_dependencies',
            'badge_id',
            'prerequisite_badge_id'
        )->withTimestamps();
    }

    /**
     * Badges que dependem desta
     * (Badges que esta desbloqueia)
     */
    public function dependents(): BelongsToMany
    {
        return $this->belongsToMany(
            Badge::class,
            'badge_dependencies',
            'prerequisite_badge_id',
            'badge_id'
        )->withTimestamps();
    }

    /**
     * Usuários que possuem esta badge
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_badges')
            ->withPivot('unlocked_at')
            ->withTimestamps();
    }
}

