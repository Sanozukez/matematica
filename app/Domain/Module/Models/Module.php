<?php

// plataforma/app/Domain/Module/Models/Module.php

namespace App\Domain\Module\Models;

use App\Domain\Badge\Models\Badge;
use App\Domain\Course\Models\Course;
use App\Domain\Lesson\Models\Lesson;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo de Módulo
 * 
 * Representa um módulo dentro de um curso (ex: "Números Naturais")
 * Cada módulo contém lições e pode ter uma badge associada
 * 
 * @property string $id UUID do módulo
 * @property string $course_id UUID do curso pai
 * @property string $title Título do módulo
 * @property string $slug Slug para URL
 * @property string|null $description Descrição do módulo
 * @property string|null $icon Emoji ou ícone
 * @property int $order Ordem dentro do curso
 * @property bool $is_active Se está ativo/publicado
 */
class Module extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $fillable = [
        'course_id',
        'title',
        'slug',
        'description',
        'icon',
        'order',
        'is_active',
    ];

    protected $casts = [
        'order' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Curso ao qual este módulo pertence
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Lições deste módulo
     */
    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    /**
     * Badge associada à conclusão deste módulo
     */
    public function badge(): HasOne
    {
        return $this->hasOne(Badge::class);
    }
}

