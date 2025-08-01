<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;  

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aprs', function (Blueprint $table) {
            // --- SEÇÃO 1: CABEÇALHO DA APR ---
            $table->id();
            $table->string('numero_apr')->unique()->comment('Nº DA APR');
            $table->string('unidade_nome')->comment('NOME DA UNIDADE / UT');
            $table->string('unidade_numero')->nullable()->comment('Nº DA UT');
            $table->string('responsavel_unidade')->comment('RESPONSÁVEL PELA UNIDADE / UT');
            $table->dateTime('inicio_at')->comment('DATA E HORA DO INÍCIO DA ATIVIDADE');
            $table->dateTime('termino_at')->comment('DATA E HORA DO TÉRMINO DA ATIVIDADE');
            $table->string('local');
            $table->string('atividade_descricao')->comment('ATIVIDADE');
            $table->text('caracteristica_atividade')->nullable();
            $table->text('outras_informacoes')->nullable();
            $table->string('tipo_atividade')->comment('Rotineira, Não Rotineira, Crítica');
            $table->json('atividades_criticas_envolvidas')->nullable()->comment('Lista de atividades críticas selecionadas');
            
            // --- SEÇÃO 2: CHECKLIST FINAL ---
            $table->boolean('checklist_conhece_atividade')->nullable();
            $table->boolean('checklist_sabe_onde_encontrar')->nullable();
            $table->boolean('checklist_acoes_tomadas')->nullable();

            $table->string('status')->default('Em Elaboração');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aprs');
    }
};
