<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class APRParticipant extends Model
{
    use HasFactory;

    protected $table = 'apr_participants';

    protected $fillable = [
        'apr_id',
        're',
        'nome',
        'funcao',
        'acknowledged_at',
    ];

    protected $casts = [
        'acknowledged_at' => 'datetime',
    ];

    /**
     * RELACIONAMENTO INVERSO: Um participante pertence a uma APR.
     */
    public function apr(): BelongsTo
    {
        return $this->belongsTo(APR::class, 'apr_id');
    }
}