<?php

// ARQUIVO 1: O SEEDER
// Comando para criar este arquivo: php artisan make:seeder APRSeeder
// Local: database/seeders/APRSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\APR; // Importe o model APR

class APRSeeder extends Seeder
{
    /**
     * Executa o seeder no banco de dados.
     */
    public function run(): void
    {
        // Limpa a tabela antes de popular para evitar duplicatas em execuções futuras
        // CUIDADO: Isso apaga todos os dados das tabelas relacionadas.
        \App\Models\APRParticipant::query()->delete();
        \App\Models\APRItem::query()->delete();
        APR::query()->delete();

        // 1. Cria a APR principal com os dados gerais
        $apr = APR::create([
            'numero_apr' => 'APR-MAN-2025-001',
            'unidade_nome' => 'Unidade de Produção Belford Roxo',
            'unidade_numero' => null,
            'responsavel_unidade' => 'Carlos Alberto Souza',
            'inicio_at' => '2025-07-31 09:00:00',
            'termino_at' => '2025-07-31 17:00:00',
            'local' => 'Setor de Bombeamento - Próximo ao Tanque T-102',
            'atividade_descricao' => 'Manutenção preventiva da bomba centrífuga B-05, incluindo troca de selo mecânico e alinhamento do motor.',
            'caracteristica_atividade' => 'Atividade programada de manutenção mecânica.',
            'outras_informacoes' => 'Será necessário o uso de talha para içamento.',
            'tipo_atividade' => 'Rotineira',
            'atividades_criticas_envolvidas' => ['Movimentação de cargas', 'Manutenção e serviços em sistemas elétricos'],
            'checklist_conhece_atividade' => true,
            'checklist_sabe_onde_encontrar' => true,
            'checklist_acoes_tomadas' => true,
            'status' => 'Em Elaboração',
        ]);

        // 2. Cria os itens de risco e os associa à APR
        $items = [
            [
                'numero_item' => 1,
                'tarefa' => 'Isolamento e bloqueio elétrico da bomba no painel CCM-02.',
                'risco' => 'Energização acidental do equipamento, choque elétrico, prensamento de membros.',
                'recomendacao' => 'Realizar o bloqueio com cadeado e etiqueta individual (LOTO). Testar a ausência de tensão antes de iniciar a intervenção mecânica. Apenas eletricista autorizado pode realizar o bloqueio.',
            ],
            [
                'numero_item' => 2,
                'tarefa' => 'Desacoplamento do motor e remoção da bomba para a bancada.',
                'risco' => 'Esforço excessivo (risco ergonômico), queda de componentes, esmagamento de mãos ou pés.',
                'recomendacao' => 'Utilizar carrinho de transporte adequado ou talha para movimentar a bomba. Uso obrigatório de luvas de proteção e botas com biqueira de aço. Realizar a atividade em dupla.',
            ],
            [
                'numero_item' => 3,
                'tarefa' => 'Limpeza das peças com solvente desengraxante.',
                'risco' => 'Contato do produto químico com a pele e olhos, inalação de vapores.',
                'recomendacao' => 'Realizar a limpeza em área ventilada. Uso obrigatório de óculos de segurança, luvas de proteção nitrílicas e máscara de proteção para vapores orgânicos.',
            ],
        ];

        $apr->items()->createMany($items);

        // 3. Cria os participantes e os associa à APR
        $participants = [
            ['re' => '85421', 'nome' => 'João da Silva', 'funcao' => 'Mecânico de Manutenção'],
            ['re' => '78554', 'nome' => 'Pedro Oliveira', 'funcao' => 'Eletricista de Manutenção'],
            ['re' => '96332', 'nome' => 'Ana Costa', 'funcao' => 'Técnica de Segurança'],
        ];

        $apr->participants()->createMany($participants);

        // Mensagem de sucesso no console
        $this->command->info('APR de exemplo criada com sucesso!');
    }
}

