<?php

// plataforma/app/Domain/Progress/Models/UserProgress.php

namespace App\Domain\Progress\Models;

use App\Domain\Lesson\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo de Progresso do Usuário
 * 
 * Rastreia o progresso do usuário em cada lição
 * Armazena status, pontuação e tempo gasto
 * 
 * @property string $id UUID do registro
 * @property string $user_id UUID do usuário
 * @property string $lesson_id UUID da lição
 * @property string $status Status (not_started, in_progress, completed)
 * @property int|null $score Pontuação obtida (0-100)
 * @property int $time_spent_seconds Tempo gasto em segundos
 * @property \Carbon\Carbon|null $started_at Quando iniciou
 * @property \Carbon\Carbon|null $completed_at Quando completou
 */
class UserProgress extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'user_progress';

    /**
     * Status possíveis
     */
    public const STATUS_NOT_STARTED = 'not_started';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'user_id',
        'lesson_id',
        'status',
        'score',
        'time_spent_seconds',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'score' => 'integer',
        'time_spent_seconds' => 'integer',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Usuário dono deste progresso
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Lição relacionada
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Verifica se está completo
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }
}

