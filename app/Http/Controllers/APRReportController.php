<?php

// Instrução: Substitua o conteúdo do seu ficheiro APRReportController por este.
// Local: app/Http/Controllers/APRReportController.php

namespace App\Http\Controllers;

use App\Models\APR;
use Illuminate\Http\Request;
// Importe a biblioteca de PDF que instalámos
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class APRReportController extends Controller
{
    /**
     * Gera e envia um relatório em PDF para uma APR específica.
     */
    public function exportToPdf(APR $apr)
    {
        // Carregamos os dados relacionados (items e participantes) para garantir que
        // temos toda a informação necessária para o relatório.
        $apr->load(['items', 'participants']);

        // Criamos um array de dados para passar para a nossa 'view' do PDF.
        // A view é o ficheiro .blade.php que define a aparência do PDF.
        $data = [
            'apr' => $apr,
        ];

        // Usamos a biblioteca para carregar a view e passar os dados para ela.
        $pdf = Pdf::loadView('reports.apr_pdf', $data);

        // Opcional: podemos configurar o tamanho do papel e a orientação.
        $pdf->setPaper('a4', 'portrait'); // 'portrait' (retrato) ou 'landscape' (paisagem)

        // Preparamos um nome de ficheiro amigável, substituindo caracteres inválidos.
        $fileName = 'APR_' . str_replace(['/', '\\'], '-', $apr->numero_apr) . '.pdf';

        // Finalmente, enviamos o PDF para o navegador do utilizador para que ele possa fazer o download.
        return $pdf->download($fileName);
    }
    /**
     * Gera e envia um relatório em Excel para uma APR específica.
     */
    public function exportToExcel(APR $apr): StreamedResponse
    {
        // Carregamos os dados relacionados
        $apr->load(['items', 'participants']);

        // Carregamos o nosso ficheiro de template .xlsx
        $templatePath = storage_path('app/templates/ANÁLISE PRELIMINAR DE RISCOS - APR - Modelo.xlsx');
        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();

        // --- PREENCHIMENTO DO CABEÇALHO ---
        // (Os números das células devem ser ajustados de acordo com o seu template exato)
        $sheet->setCellValue('D6', $apr->numero_apr);
        $sheet->setCellValue('M6', $apr->unidade_nome);
        $sheet->setCellValue('F7', $apr->responsavel_unidade);
        $sheet->setCellValue('H8', $apr->inicio_at->format('d/m/Y H:i'));
        $sheet->setCellValue('Y8', $apr->termino_at->format('d/m/Y H:i'));
        $sheet->setCellValue('D9', $apr->local);
        $sheet->setCellValue('O9', $apr->atividade_descricao);
        $sheet->setCellValue('H10', $apr->caracteristica_atividade);
        //$sheet->setCellValue('B12', $apr->outras_informacoes);

        // --- PREENCHIMENTO DOS ITENS DE RISCO ---
        $row = 23; // Linha inicial para os itens de risco (ajuste conforme necessário)
        foreach ($apr->items as $item) {
            //$sheet->setCellValue('A' . $row, $item->numero_item);
            $sheet->setCellValue('D' . $row, $item->tarefa);
           // $sheet->setCellValue('F' . $row, $item->risco);
            //$sheet->setCellValue('K' . $row, $item->recomendacao);
            $row++;
        }

        // --- PREENCHIMENTO DOS PARTICIPANTES ---
        $row = 189; // Linha inicial para os participantes (ajuste conforme necessário)
        foreach ($apr->participants as $participant) {
            $sheet->setCellValue('D' . $row, $participant->nome);
            $sheet->setCellValue('Q' . $row, $participant->re);
            // A coluna "VISTO" pode ser preenchida ou deixada em branco para assinatura manual
            $row++;
        }

        // --- PREPARAÇÃO DO FICHEIRO PARA DOWNLOAD ---
        $writer = new Xlsx($spreadsheet);
        $fileName = 'APR_' . str_replace(['/', '\\'], '-', $apr->numero_apr) . '.xlsx';

        // Usamos uma StreamedResponse para enviar o ficheiro para o navegador
        $response = new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $fileName . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }
}