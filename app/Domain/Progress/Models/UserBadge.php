<?php

// plataforma/app/Domain/Progress/Models/UserBadge.php

namespace App\Domain\Progress\Models;

use App\Domain\Badge\Models\Badge;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo de Badge do Usuário (Pivot aprimorado)
 * 
 * Registra as badges conquistadas por cada usuário
 * Inclui data de desbloqueio e metadados
 * 
 * @property string $id UUID do registro
 * @property string $user_id UUID do usuário
 * @property string $badge_id UUID da badge
 * @property \Carbon\Carbon $unlocked_at Quando foi desbloqueada
 */
class UserBadge extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'user_badges';

    protected $fillable = [
        'user_id',
        'badge_id',
        'unlocked_at',
    ];

    protected $casts = [
        'unlocked_at' => 'datetime',
    ];

    /**
     * Usuário que possui a badge
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Badge conquistada
     */
    public function badge(): BelongsTo
    {
        return $this->belongsTo(Badge::class);
    }
}

