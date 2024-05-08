<?php

namespace App\Exports;

use App\Http\Controllers\Config;
use App\Models\Empresa;
use App\Models\User;
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

class ListagemEmpresaExport extends DefaultValueBinder implements FromCollection, WithMapping, WithTitle, WithHeadings, WithDrawings, WithStyles, WithCustomStartCell, WithCustomValueBinder, ShouldAutoSize
{
    use Config, Exportable;

    public $titulo;

    public function __construct($request)
    {
        $this->titulo = "LISTAGEM DE EMPRESAS";
    }

    public function headings():array
    {
        return[
           "NIF",
           "Nome",
           "Regime do IVA",
           "Moeda Base",
           "Moeda Alternativo",
           "Tipo",
           "Grupo",
           "Estado"
        ];
    }

    public function map($item):array
    {
        return[
            
            $item->codigo_empresa ?? '',
            $item->nome_empresa ?? '-',
            $item->regime ? $item->regime->designacao : '-',
            // $item->moeda ? ($item->moeda->base ? ($item->moeda->base ? $item->moeda->base->designacao : "") : "") : "" -  $item->moeda ? ($item->moeda->base ? ($item->moeda->base ? $item->moeda->base->sigla : "") : "") : "" ,
            $item->moeda ? ($item->moeda->base ? ($item->moeda->base ? $item->moeda->base->sigla : "") : "") : "" ,
            $item->moeda ? ($item->moeda->alternativa ? ($item->moeda->alternativa ? $item->moeda->alternativa->sigla : "") : "") : "",
            // $item->moeda ? ($item->moeda->base ? ($item->moeda->alternativa ? $item->moeda->alternativa->designacao : "") : "") : "" - $item->moeda ? ($item->moeda->alternativa ? ($item->moeda->alternativa ? $item->moeda->alternativa->sigla : "") : "") : "",
            $item->tipo ? $item->tipo->designacao : "" ,
            $item->grupo ? $item->grupo->designacao : "",
            $item->estado_empresa_id == 1 ? 'Activo': 'Desactivo',
            
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $user = User::with('empresa')->findOrFail(auth()->user()->id);
        
        return Empresa::with(['endereco', 'regime', 'moeda.base', 'moeda.alternativa', 'moeda.cambio', 'tipo', 'grupo'])
        ->where('user_id', $user->id)
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
        $dados_empresa = $this->dadosEmpresaLogada();
        
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('LISTAGEM DE EMPRESAS');
        // $drawing->setPath(public_path("images/{$dados_empresa->logotipo_da_empresa }"));
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
        $sheet->setCellValue('A7', strtoupper($this->titulo));
        // $sheet->setCellValue('D6', 'ANOS LECTIVOS: ');
        // $sheet->setCellValue('E6', AnoLectivo::find($this->searchAnoLectivo) ? AnoLectivo::find($this->searchAnoLectivo)->Designacao : 'TODAS');
        // $sheet->setCellValue('D7', 'CURSO: ');
        // $sheet->setCellValue('E7',  Curso::find($this->searchCurso) ? Curso::find($this->searchCurso)->Designacao : 'TODAS');
        // $sheet->setCellValue('D8', 'TURNO: ');
        // $sheet->setCellValue('E8', Turno::find($this->searchTurno) ? Turno::find($this->searchTurno)->Designacao :'TODAS');
        // $coordenadas = $sheet->getCoordinates();

        return [
            // Style the first row as bold text.
            10    => [
                'font' => ['bold' => false, 'color' => ['rgb' => 'FCFCFD']],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => '2b5876']]

            ],

            'D6:E9'    => [
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
