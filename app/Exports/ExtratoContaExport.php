<?php

namespace App\Exports;

use App\Http\Controllers\Config;
use App\Http\Controllers\TraitHelpers;
use App\Models\AnoLectivo;
use App\Models\Conta;
use App\Models\Empresa;
use App\Models\MovimentoItem;
use App\Models\Pagamento;
use App\Models\TipoServico;
use App\Models\Utilizador;
use Carbon\Carbon;
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

class ExtratoContaExport extends DefaultValueBinder implements FromCollection, WithMapping, WithTitle, WithHeadings, WithDrawings, WithStyles, WithCustomStartCell, WithCustomValueBinder, ShouldAutoSize
{
    use Config, Exportable;
    
    public $conta_id, $exercicio_id, $periodo_id, $data_inicio, $data_final, $subconta_id,  $titulo;
    public $total_credito = 0, $total_debito = 0;
    

    public function __construct($request)
    {
        $this->conta_id = $request->conta_id;
        $this->exercicio_id = $request->exercicio_id;
        $this->periodo_id = $request->periodo_id;
        $this->data_inicio = $request->data_inicio;
        $this->data_final = $request->data_final;
        $this->subconta_id = $request->subconta_id;
        
        $this->titulo = "EXTRATO DE CONTAS";
    }

    public function headings():array
    {
        return[
            'Nº Movimento',
            'Descrição',
            'Débito',
            'Crédito',
            'Data',
        ];
    }

    public function map($item):array
    {
        return[
            $item->movimento->lancamento_atual,
            $item->subconta->designacao,
            number_format($item->debito, 2, ',', '.'),
            number_format($item->credito, 2, ',', '.'),
            $item->movimento->data_lancamento,
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
            
        $data['movimentos'] = MovimentoItem::whereHas('movimento', function($query){
            $query->when($this->exercicio_id, function($query, $value){
                $query->where('exercicio_id', $value);
            }); 
        })
        ->whereHas('movimento', function($query) {
            $query->when($this->periodo_id, function($query, $value){
                $query->where('periodo_id', $value);
            }); 
        })
        ->whereHas('subconta', function($query) {
            $query->when($this->conta_id, function($query, $value){
                $query->where('conta_id', $value);
            }); 
        })
        ->whereHas('movimento', function($query) {
            $query->when($this->data_inicio, function($query, $value){
                $query->whereDate('data_lancamento',  ">=" ,$value);
            }); 
        })
        ->whereHas('movimento', function($query) {
            $query->when($this->data_final, function($query, $value){
                $query->whereDate('data_lancamento', "<=" ,$value);
            }); 
        })
        ->when($this->subconta_id, function($query, $value){
            $query->where('subconta_id', $value);
        })
        ->with(['subconta',  'movimento.diario', 'movimento.tipo_documento', 'criador'])
        ->where('empresa_id', $this->empresaLogada())
        ->orderBy('id', 'desc')->get();
        
        return $data['movimentos'];
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
        
        $dados_empresa = Empresa::findOrFail($this->empresaLogada());
    
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('FECHO DO CAIXA');
        $drawing->setPath(public_path("/images/{$dados_empresa->logotipo_da_empresa}"));
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
        $conta = Conta::find($this->conta_id);
    
        $sheet->setCellValue('A7', strtoupper($this->titulo));
        $sheet->setCellValue('C4', 'Conta: ');
        $sheet->setCellValue('D4', $conta->designacao ?? 'TODOS');
        $sheet->setCellValue('C7', 'DATA INICIO: ');
        $sheet->setCellValue('D7',  $this->data_inicio ? $this->data_inicio : date("Y-m-d"));
        $sheet->setCellValue('C8', 'DATA FINAL: ');
        $sheet->setCellValue('D8', $this->data_final ? $this->data_final : date("Y-m-d"));
        $coordenadas = $sheet->getCoordinates();

        return [
            // Style the first row as bold text.
            10    => [
                'font' => ['bold' => false, 'color' => ['rgb' => 'FCFCFD']],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => '2b5876']]

            ],

            'C4:E9'    => [
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
