<?php

// ARQUIVO 1: O CONTROLLER (LÓGICA DO BACKEND)
// Comando para criar: php artisan make:controller APRController --resource
// Instrução: Substitua o conteúdo do seu APRController por este.
// Local: app/Http/Controllers/APRController.php

namespace App\Http\Controllers;

use App\Models\APR;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use App\Models\APRItem;
use App\Models\APRParticipant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;


class APRController extends Controller
{
    /**
     * Exibe uma lista de todas as APRs com paginação.
     */
    public function index(): Response
    {
        $aprs = APR::latest()->paginate(10);

        return Inertia::render('APRs/Index', [
            'aprs' => $aprs,
        ]);
    }

    /**
     * Mostra o formulário para criar uma nova APR.
     */
    public function create(): Response
    {
        return Inertia::render('APRs/Create');
    }

     /**
     * Exibe os detalhes de uma APR específica em modo somente leitura.
     */
    public function show(APR $apr): Response
    {
        // Carregamos os dados relacionados (items e participantes) para a APR
        $apr->load(['items', 'participants']);

        // Renderizamos o novo componente de visualização, passando os dados da APR
        return Inertia::render('APRs/Show', [
            'apr' => $apr
        ]);
    }

    /**
     * Armazena uma nova APR no banco de dados.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'numero_apr' => 'required|string|max:255|unique:aprs',
            'unidade_nome' => 'required|string|max:255',
            'unidade_numero' => 'nullable|string|max:255',
            'responsavel_unidade' => 'required|string|max:255',
            'inicio_at' => 'required|date',
            'termino_at' => 'required|date|after_or_equal:inicio_at',
            'local' => 'required|string|max:255',
            'atividade_descricao' => 'required|string',
            'caracteristica_atividade' => 'nullable|string',
            'outras_informacoes' => 'nullable|string',
            'tipo_atividade' => ['required', Rule::in(['Rotineira', 'Não Rotineira', 'Crítica'])],
            'atividades_criticas_envolvidas' => 'nullable|array',
            'checklist_conhece_atividade' => 'required|boolean',
            'checklist_sabe_onde_encontrar' => 'required|boolean',
            'checklist_acoes_tomadas' => 'required|boolean',
            'items' => 'required|array|min:1',
            'items.*.tarefa' => 'required|string',
            'items.*.risco' => 'required|string',
            'items.*.recomendacao' => 'required|string',
            'participants' => 'required|array|min:1',
            'participants.*.re' => 'required|string|max:255',
            'participants.*.nome' => 'required|string|max:255',
            'participants.*.funcao' => 'required|string|max:255',
        ]);

        $apr = APR::create($validatedData);

        foreach ($validatedData['items'] as $index => $itemData) {
            $apr->items()->create([
                'numero_item' => $index + 1,
                'tarefa' => $itemData['tarefa'],
                'risco' => $itemData['risco'],
                'recomendacao' => $itemData['recomendacao'],
            ]);
        }
    }
     /**
     * Mostra o formulário para editar uma APR existente.
     */
    public function edit(APR $apr): Response
    {
        // Carregamos os dados relacionados (items e participantes) para a APR
        $apr->load(['items', 'participants']);

        // Renderizamos o novo componente de edição, passando os dados da APR
        return Inertia::render('APRs/Edit', [
            'apr' => $apr
        ]);
    }

    /**
     * Atualiza uma APR existente no banco de dados.
     */
    public function update(Request $request, APR $apr)
    {
        // A validação é quase a mesma do 'store', mas a regra 'unique' precisa de ser ajustada
        // para ignorar a própria APR que estamos a editar.
        $validatedData = $request->validate([
            'numero_apr' => ['required', 'string', 'max:255', Rule::unique('aprs')->ignore($apr->id)],
            'unidade_nome' => 'required|string|max:255',
            'unidade_numero' => 'nullable|string|max:255',
            'responsavel_unidade' => 'required|string|max:255',
            'inicio_at' => 'required|date',
            'termino_at' => 'required|date|after_or_equal:inicio_at',
            'local' => 'required|string|max:255',
            'atividade_descricao' => 'required|string',
            'caracteristica_atividade' => 'nullable|string',
            'outras_informacoes' => 'nullable|string',
            'tipo_atividade' => ['required', Rule::in(['Rotineira', 'Não Rotineira', 'Crítica'])],
            'atividades_criticas_envolvidas' => 'nullable|array',
            'checklist_conhece_atividade' => 'required|boolean',
            'checklist_sabe_onde_encontrar' => 'required|boolean',
            'checklist_acoes_tomadas' => 'required|boolean',
            'items' => 'required|array|min:1',
            'items.*.tarefa' => 'required|string',
            'items.*.risco' => 'required|string',
            'items.*.recomendacao' => 'required|string',
            'participants' => 'required|array|min:1',
            'participants.*.re' => 'required|string|max:255',
            'participants.*.nome' => 'required|string|max:255',
            'participants.*.funcao' => 'required|string|max:255',
        ]);

        // Usamos uma transação para garantir que todas as operações são bem-sucedidas ou nenhuma é.
        DB::transaction(function () use ($apr, $validatedData) {
            // 1. Atualiza os dados da tabela principal 'aprs'
            $apr->update(Arr::except($validatedData, ['items', 'participants']));

            // 2. Apaga os itens e participantes antigos para simplificar a lógica.
            $apr->items()->delete();
            $apr->participants()->delete();

            // 3. Cria os novos itens e participantes a partir dos dados do formulário.
            foreach ($validatedData['items'] as $index => $itemData) {
                $apr->items()->create([
                    'numero_item' => $index + 1,
                    'tarefa' => $itemData['tarefa'],
                    'risco' => $itemData['risco'],
                    'recomendacao' => $itemData['recomendacao'],
                ]);
            }
            $apr->participants()->createMany($validatedData['participants']);
        });

        return Redirect::route('aprs.index')->with('success', 'APR atualizada com sucesso.');
    }
    /**
     * Remove uma APR específica do banco de dados.
     */
    public function destroy(APR $apr)
    {
        // A transação não é estritamente necessária para uma única exclusão,
        // mas é uma boa prática se a lógica se tornar mais complexa no futuro.
        try {
            $apr->delete();
        } catch (\Exception $e) {
            // Se houver um erro, redireciona de volta com uma mensagem de erro.
            return Redirect::back()->with('error', 'Ocorreu um erro ao excluir a APR.');
        }

        // Redireciona de volta para a lista de APRs com uma mensagem de sucesso.
        return Redirect::route('aprs.index')->with('success', 'APR excluída com sucesso.');
    }
}