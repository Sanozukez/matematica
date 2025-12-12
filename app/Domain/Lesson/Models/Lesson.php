<?php

// plataforma/app/Domain/Lesson/Models/Lesson.php

namespace App\Domain\Lesson\Models;

use App\Domain\Module\Models\Module;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo de Lição
 * 
 * Representa uma lição/aula dentro de um módulo
 * Pode conter texto, vídeo, quiz ou mini jogo
 * 
 * @property string $id UUID da lição
 * @property string $module_id UUID do módulo pai
 * @property string $title Título da lição
 * @property string $slug Slug para URL
 * @property string $type Tipo (text, video, quiz, game)
 * @property array|null $content Conteúdo JSON da lição
 * @property int $order Ordem dentro do módulo
 * @property int $duration_minutes Duração estimada em minutos
 * @property bool $is_active Se está ativo/publicado
 */
class Lesson extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    /**
     * Tipos de lição disponíveis
     */
    public const TYPE_TEXT = 'text';
    public const TYPE_VIDEO = 'video';
    public const TYPE_QUIZ = 'quiz';
    public const TYPE_GAME = 'game';

    protected $fillable = [
        'module_id',
        'title',
        'slug',
        'type',
        'content',
        'order',
        'duration_minutes',
        'is_active',
    ];

    protected $casts = [
        'content' => 'array',
        'order' => 'integer',
        'duration_minutes' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Garantir que content sempre seja array (nunca string)
     */
    protected function casts(): array
    {
        return [
            'content' => 'array',
        ];
    }

    /**
     * Accessor: Garantir que content sempre retorne array
     */
    public function getContentAttribute($value)
    {
        if (is_null($value)) {
            return [];
        }
        
        if (is_array($value)) {
            return $value;
        }
        
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }
        
        return [];
    }

    /**
     * Mutator: Garantir que content sempre seja salvo como array
     */
    public function setContentAttribute($value)
    {
        if (is_null($value) || $value === '') {
            $this->attributes['content'] = json_encode([]);
            return;
        }
        
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            $this->attributes['content'] = json_encode(is_array($decoded) ? $decoded : []);
            return;
        }
        
        $this->attributes['content'] = json_encode(is_array($value) ? $value : []);
    }

    /**
     * Módulo ao qual esta lição pertence
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Retorna os tipos disponíveis com labels
     */
    public static function getTypes(): array
    {
        return [
            self::TYPE_TEXT => 'Texto/Conteúdo',
            self::TYPE_VIDEO => 'Vídeo',
            self::TYPE_QUIZ => 'Quiz',
            self::TYPE_GAME => 'Mini Jogo',
        ];
    }
}

