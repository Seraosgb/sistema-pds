<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class APRItem extends Model
{
    use HasFactory;

    protected $table = 'apr_items';

    protected $fillable = [
        'apr_id',
        'numero_item',
        'tarefa',
        'risco',
        'recomendacao',
    ];

    /**
     * RELACIONAMENTO INVERSO: Um item pertence a uma APR.
     */
    public function apr(): BelongsTo
    {
        return $this->belongsTo(APR::class, 'apr_id');
    }
}