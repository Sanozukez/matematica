<?php

// plataforma/app/Domain/Course/Models/Course.php

namespace App\Domain\Course\Models;

use App\Domain\Module\Models\Module;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo de Curso
 * 
 * Representa um curso completo na plataforma (ex: "Matemática Básica")
 * Cada curso contém múltiplos módulos organizados hierarquicamente
 * 
 * @property string $id UUID do curso
 * @property string $title Título do curso
 * @property string $slug Slug para URL
 * @property string|null $description Descrição do curso
 * @property string $level Nível (basic, fundamental, medium, advanced)
 * @property string|null $icon Emoji ou ícone do curso
 * @property string|null $color Cor tema do curso (hex)
 * @property int $order Ordem de exibição
 * @property bool $is_active Se está ativo/publicado
 * @property bool $is_gamified Se usa gamificação (mini jogos)
 */
class Course extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    /**
     * Níveis disponíveis para cursos
     */
    public const LEVEL_BASIC = 'basic';
    public const LEVEL_FUNDAMENTAL = 'fundamental';
    public const LEVEL_MEDIUM = 'medium';
    public const LEVEL_ADVANCED = 'advanced';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'level',
        'icon',
        'color',
        'order',
        'is_active',
        'is_gamified',
    ];

    protected $casts = [
        'order' => 'integer',
        'is_active' => 'boolean',
        'is_gamified' => 'boolean',
    ];

    /**
     * Módulos pertencentes a este curso
     */
    public function modules(): HasMany
    {
        return $this->hasMany(Module::class)->orderBy('order');
    }

    /**
     * Retorna os níveis disponíveis com labels
     */
    public static function getLevels(): array
    {
        return [
            self::LEVEL_BASIC => 'Básico',
            self::LEVEL_FUNDAMENTAL => 'Fundamental',
            self::LEVEL_MEDIUM => 'Médio',
            self::LEVEL_ADVANCED => 'Avançado',
        ];
    }
}

