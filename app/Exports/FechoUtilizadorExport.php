<?php

namespace App\Exports;

use App\Http\Controllers\TraitHelpers;
use App\Models\AnoLectivo;
use App\Models\Pagamento;
use App\Models\TipoServico;
use App\Models\Utilizador;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Cell;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithMapping;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell as CellCell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FechoUtilizadorExport extends DefaultValueBinder implements FromCollection, WithMapping, WithTitle, WithHeadings, WithDrawings, WithStyles, WithCustomStartCell, WithCustomValueBinder, ShouldAutoSize
{
    use TraitHelpers, Exportable;
    
    public $anolectivo,
    $data_inicio,
    $codigo_produto,
    $operador,
    $data_final,
    $forma_pagamento, $titulo;
    

    public function __construct($request)
    {
        $this->anolectivo = $request->anolectivo;
        $this->data_inicio = $request->data_inicio;
        $this->codigo_produto = $request->codigo_produto;
        $this->operador = $request->operador;
        $this->data_final = $request->data_final;
        $this->forma_pagamento = $request->forma_pagamento;
        
        $this->titulo = "LISTAGEM DO FECHO DE CAIXA POR UTILIZADOR";
    }

    public function headings():array
    {
        return[
            'serviço',
            'Data',
            'Valor',
            'Recibo',
            'Forma Pagamento',
            'Estado',
        ];
    }

    public function map($caixa):array
    {
        return[
            "########",
            $caixa->DataRegisto,
            number_format($caixa->valor_depositado, 2, ',', '.'),
            $caixa->Codigo,
            $caixa->forma_pagamento,
            $caixa->estado == 1 ? 'Validado' : ($caixa->estado == 2 ? 'Rejeitado' : 'Pendente'),
            $caixa->AnoLectivoPagamento,
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $ano = AnoLectivo::where('estado', 'Activo')->first();

        $anoSelecionado = $this->anolectivo;

        if(!$anoSelecionado){
            $anoSelecionado = $ano->Codigo;
        }
        
        if($this->data_inicio){
            $this->data_inicio = $this->data_inicio;
        }else{
            $this->data_inicio = date('Y-m-d');
        }
                
        $codigo = $this->codigo_produto;
        
        return Pagamento::with(['detalhes.servico', 'operador_novos'])
        ->when($this->operador, function($query, $value){
            $query->where('tb_pagamentos.fk_utilizador', $value);
        })
        ->when($this->data_inicio, function ($query, $value) {
            $query->whereDate('DataRegisto', '>=', Carbon::createFromDate($value));
        })
        ->when($this->data_final, function ($query, $value) {
            $query->whereDate('DataRegisto', '<=', Carbon::createFromDate($value));
        })
        ->whereHas('detalhes', function($query) use($codigo){
            $query->when($codigo, function($query) use($codigo){
                $query->where('Codigo_Servico', $codigo);
            });
        })
        ->where('tb_pagamentos.fk_utilizador', Auth::user()->codigo_importado)
        ->when($this->forma_pagamento, function ($query, $value) {
            $query->where('tb_pagamentos.forma_pagamento', $value);
        })
        ->where('tb_pagamentos.estado', 1)
        ->get();
    }


    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event){
                $event->sheet->getStyle('A6:G6')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                            'color' => ['rgb' => '000000'],
                        ],
                    ]
                ]);

            }
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('FECHO DO CAIXA');
        $drawing->setPath(public_path('/images/logotipo.png'));
        $drawing->setHeight(90);
        $drawing->setCoordinates('A1');

        return $drawing;
    }


    /**
     * @return string
     */
    public function title(): string
    {
        return $this->titulo;
    }

    public function startCell(): string
    {
        return 'A10';
    }
    
    
    public function styles(Worksheet $sheet)
    {
        $operador =  Utilizador::where('codigo_importado', $this->operador)->first();
        $servico = TipoServico::find($this->codigo_produto);
    
        $sheet->setCellValue('A7', strtoupper($this->titulo));
        $sheet->setCellValue('D4', 'OPERADOR: ');
        $sheet->setCellValue('E4', Auth::user()->nome);
        $sheet->setCellValue('D5', 'SERVIÇO: ');
        $sheet->setCellValue('E5', $servico->Descricao ?? 'TODAS');
        $sheet->setCellValue('D6', 'FORMA PAGAMENTO: ');
        $sheet->setCellValue('E6', $this->forma_pagamento ? : 'TODAS');
        $sheet->setCellValue('D7', 'DATA INICIO: ');
        $sheet->setCellValue('E7',  $this->data_inicio ? $this->data_inicio : date("Y-m-d"));
        $sheet->setCellValue('D8', 'DATA FINAL: ');
        $sheet->setCellValue('E8', $this->data_final ? $this->data_final : date("Y-m-d"));
        $coordenadas = $sheet->getCoordinates();

        return [
            // Style the first row as bold text.
            10    => [
                'font' => ['bold' => false, 'color' => ['rgb' => 'FCFCFD']],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => '2b5876']]

            ],

            'D4:F9'    => [
                'font' => ['bold' => false, 'color' => ['rgb' => 'FCFCFD']],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => '2b5876']]

            ],

            // Styling a specific cell by coordinate.
            'A7' => ['font' => ['bold' => true, 'color' => ['rgb' => '00008B']]],
            'F7' => ['font' => ['bold' => true, 'color' => ['rgb' => '00008B']]],
            // 'G6' => ['font' => ['bold' => true, 'color' => ['rgb' => '00008B']]],

            'A11:' . end($coordenadas) => ['borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ]],


            // Styling an entire column.
            //'C'  => ['font' => ['size' => 16]],
        ];
        //$sheet->getStyle('A7')->getFont()->setBold(true);
    }

    public function bindValue(CellCell $cell, $value)
    {

        if (is_string($value)) {
            $cell->setValueExplicit(strval($value), DataType::TYPE_STRING);
            return true;
        }

        // else return default behavior
        return parent::bindValue($cell, strval($value));
    }

}