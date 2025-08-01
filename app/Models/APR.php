<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class APR extends Model
{
    use HasFactory;

    /**
     * O nome da tabela associada com o model.
     *
     * @var string
     */
    protected $table = 'aprs';

    /**
     * Os atributos que podem ser atribuídos em massa.
     * Isso é essencial para que os formulários de criação/edição funcionem.
     *
     * @var array
     */
    protected $fillable = [
        'numero_apr',
        'unidade_nome',
        'unidade_numero',
        'responsavel_unidade',
        'inicio_at',
        'termino_at',
        'local',
        'atividade_descricao',
        'caracteristica_atividade',
        'outras_informacoes',
        'tipo_atividade',
        'atividades_criticas_envolvidas',
        'checklist_conhece_atividade',
        'checklist_sabe_onde_encontrar',
        'checklist_acoes_tomadas',
        'status',
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     * Isso facilita muito a manipulação dos dados.
     *
     * @var array
     */
    protected $casts = [
        'inicio_at' => 'datetime',
        'termino_at' => 'datetime',
        'atividades_criticas_envolvidas' => 'array', // Converte o JSON para array automaticamente
        'checklist_conhece_atividade' => 'boolean',
        'checklist_sabe_onde_encontrar' => 'boolean',
        'checklist_acoes_tomadas' => 'boolean',
    ];

    /**
     * RELACIONAMENTO: Uma APR tem muitos itens de risco.
     */
    public function items(): HasMany
    {
        return $this->hasMany(APRItem::class, 'apr_id');
    }

    /**
     * RELACIONAMENTO: Uma APR tem muitos participantes.
     */
    public function participants(): HasMany
    {
        return $this->hasMany(APRParticipant::class, 'apr_id');
    }
}
