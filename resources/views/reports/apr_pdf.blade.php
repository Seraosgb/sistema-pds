<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>APR - {{ $apr->numero_apr }}</title>
    <style>
        /* Estilos para deixar o PDF o mais próximo possível do seu modelo Excel */
        @page { margin: 20px; }
        body { font-family: 'Helvetica', sans-serif; font-size: 8px; color: #333; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { border: 1px solid #000; padding: 3px; vertical-align: top; }
        .table th { font-weight: bold; background-color: #e0e0e0; text-align: center; }
        .header-main-title { font-size: 14px; font-weight: bold; text-align: center; padding: 10px 0; }
        .section-title { font-weight: bold; padding-top: 5px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .no-border td { border: none; }
        .signature-line { border-bottom: 1px solid #000; height: 20px; }
        .checkbox { display: inline-block; width: 10px; height: 10px; border: 1px solid #000; margin-right: 5px; }
        .checked { background-color: #000; }
    </style>
</head>
<body>

    <table class="table">
        <tr>
            <td colspan="4" class="text-center">
                <!-- Se tiver um logo, pode colocar aqui -->
                <div class="header-main-title">ANÁLISE PRELIMINAR DE RISCOS - APR</div>
            </td>
        </tr>
        <tr>
            <td colspan="2"><span class="section-title">Nº DA APR:</span> {{ $apr->numero_apr }}</td>
            <td colspan="2"><span class="section-title">NOME DA UNIDADE:</span> {{ $apr->unidade_nome }}</td>
        </tr>
        <tr>
            <td colspan="3"><span class="section-title">RESPONSÁVEL PELA UNIDADE:</span> {{ $apr->responsavel_unidade }}</td>
            <td><span class="section-title">ASSINATURA:</span></td>
        </tr>
        <tr>
            <td colspan="2"><span class="section-title">DATA E HORA DO INÍCIO:</span> {{ $apr->inicio_at->format('d/m/Y H:i') }}</td>
            <td colspan="2"><span class="section-title">DATA E HORA DO TÉRMINO:</span> {{ $apr->termino_at->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td colspan="2"><span class="section-title">LOCAL:</span> {{ $apr->local }}</td>
            <td colspan="2"><span class="section-title">ATIVIDADE:</span> {{ $apr->atividade_descricao }}</td>
        </tr>
        <tr>
            <td colspan="4"><span class="section-title">CARACTERÍSTICA DA ATIVIDADE:</span><br>{{ $apr->caracteristica_atividade }}</td>
        </tr>
         <tr>
            <td colspan="4"><span class="section-title">OUTRAS INFORMAÇÕES SOBRE A ATIVIDADE:</span><br>{{ $apr->outras_informacoes }}</td>
        </tr>
    </table>

    <br>

    <table class="table">
        <thead>
            <tr>
                <th style="width:5%;">ITEM</th>
                <th style="width:30%;">TAREFAS</th>
                <th style="width:30%;">RISCOS DAS TAREFAS</th>
                <th style="width:35%;">RECOMENDAÇÕES E/OU PROCEDIMENTOS PARA EVITAR ACIDENTES</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($apr->items as $item)
                <tr>
                    <td class="text-center">{{ $item->numero_item }}</td>
                    <td>{!! nl2br(e($item->tarefa)) !!}</td>
                    <td>{!! nl2br(e($item->risco)) !!}</td>
                    <td>{!! nl2br(e($item->recomendacao)) !!}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center" style="padding: 20px;">Nenhum item de risco cadastrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <br>

    <table class="table">
         <tr>
            <td colspan="3" class="text-center section-title">DECLARO QUE PARTICIPEI DA ANÁLISE PRELIMINAR DE RISCOS E RECEBI INSTRUÇÕES...</td>
        </tr>
        <tr>
            <th style="width:15%;">RE</th>
            <th style="width:45%;">NOME</th>
            <th style="width:40%;">FUNÇÃO</th>
            <!-- A coluna de assinatura é visual, o registro é o aceite no sistema -->
        </tr>
        @foreach ($apr->participants as $participant)
            <tr>
                <td>{{ $participant->re }}</td>
                <td>{{ $participant->nome }}</td>
                <td>{{ $participant->funcao }}</td>
            </tr>
        @endforeach
    </table>

</body>
</html>