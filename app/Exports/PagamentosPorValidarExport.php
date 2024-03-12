<?php
namespace App\Exports;

use App\Http\Controllers\TraitHelpers;
use App\Models\AnoLectivo;
use App\Models\GrauAcademico;
use App\Models\Pagamento;
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

class PagamentosPorValidarExport extends DefaultValueBinder implements FromCollection, WithMapping, WithTitle, WithHeadings, WithDrawings, WithStyles, WithCustomStartCell, WithCustomValueBinder, ShouldAutoSize
{
    use TraitHelpers, Exportable;

    public $prestacao, $titulo, $ano_lectivo, $filtro_estudante,  $forma_pagamento, $tipo_servico, $grau_academico, $data_inicio, $data_final;

    public function __construct($request)
    {
        $this->ano_lectivo = $request->ano_lectivo;
        $this->prestacao = $request->prestacao;
        $this->filtro_estudante = $request->filtro_estudante;
        $this->forma_pagamento = $request->forma_pagamento;
        $this->tipo_servico = $request->tipo_servico;
        $this->grau_academico = $request->grau_academico;
        $this->data_inicio = $request->data_inicio;
        $this->data_final = $request->data_final;
        
        $this->titulo = "LISTA DE PAGAMENTO POR VALIDAR";
    }

    public function headings():array
    {
        return[
            'Matrícula',
            'Estudante',
            'Factura',
            'Recibo',
            'Serviço',
            'Data Pagamento',
            'Data Inserção',
            'Valor Depositado',
            'Forma Pagamento',
        ];
    }

    public function map($caixa):array
    {
        return[
            $caixa->matricula ?? "#####",
            $caixa->Nome_Completo ?? "",
            $caixa->codigo_factura ?? "",
            $caixa->Codigo ?? "",
            $caixa->servico ?? "",
            $caixa->DataBanco ?? "",
            $caixa->Data ?? "",
            number_format($caixa->valor_depositado ?? 0, 2, ',', '.') ,
            $caixa->forma_pagamento ?? "",
        ];
    }


    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {

        $ano = AnoLectivo::where('estado', 'Activo')->first();
        $data['grau'] = GrauAcademico::get();
        
        if($this->ano_lectivo){
            $this->ano_lectivo = $this->ano_lectivo;
        }else{
            $this->ano_lectivo = $ano->Codigo;
        }

        $data['items'] = Pagamento::when($this->prestacao, function ($query, $value) {
            $query->where('mes_temp.id', $value);
        })
        ->when($this->forma_pagamento, function ($query, $value) {
            $query->where('tb_pagamentos.forma_pagamento', $value);
        })
        ->when($this->tipo_servico, function ($query, $value) {
            $query->where('tb_pagamentosi.Codigo_Servico', $value);
        })
        ->when($this->filtro_estudante, function ($query, $value) {
            $query->where('tb_pagamentos.Codigo_PreInscricao', $value)
                ->orWhere('tb_preinscricao.Bilhete_Identidade', $value)
                ->orWhere('tb_preinscricao.Nome_Completo', 'like', "%{$value}%");
        })
        ->when($this->grau_academico, function ($query, $value) {
            $query->where('tb_tipo_candidatura.id', ($value));
        })
        ->when($this->data_inicio, function ($query, $value) {
            $query->whereDate('tb_pagamentos.created_at', '>=', Carbon::createFromDate($value));
        })
        ->when($this->data_final, function ($query, $value) {
            $query->whereDate('tb_pagamentos.created_at', '<=', Carbon::createFromDate($value));
        })
        ->where('tb_pagamentos.estado', 0)
        ->where('tb_pagamentos.AnoLectivo', $this->ano_lectivo)
        ->join('tb_pagamentosi', 'tb_pagamentos.Codigo', '=', 'tb_pagamentosi.Codigo_Pagamento')
        ->join('tb_tipo_servicos', 'tb_pagamentosi.Codigo_Servico', '=', 'tb_tipo_servicos.Codigo')
        ->join('tb_preinscricao', 'tb_pagamentos.Codigo_PreInscricao', '=', 'tb_preinscricao.Codigo')
        ->leftjoin('tb_admissao', 'tb_preinscricao.Codigo', '=', 'tb_admissao.pre_incricao')
        ->leftjoin('tb_matriculas', 'tb_admissao.Codigo', '=', 'tb_matriculas.Codigo_Aluno')
        ->join('tb_tipo_candidatura', 'tb_preinscricao.codigo_tipo_candidatura', '=', 'tb_tipo_candidatura.id')
        ->select(
            'tb_matriculas.Codigo AS matricula',
            'tb_preinscricao.Nome_Completo',
            'tb_pagamentos.codigo_factura',
            'tb_pagamentos.Codigo',
            'tb_pagamentos.DataBanco',
            'tb_pagamentos.Data',
            'tb_pagamentos.estado',
            'tb_pagamentos.forma_pagamento',
            'tb_pagamentos.valor_depositado',
            'tb_tipo_candidatura.designacao AS grau_academico',
            'tb_tipo_servicos.Descricao AS servico',
        )
        ->get();
        
        return $data['items'];
        
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
        $drawing->setDescription('LISTA DE EXTRATOS DE DEPOSITOS');
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
        $sheet->setCellValue('A7', strtoupper($this->titulo));
        $sheet->setCellValue('H6', 'FORMA PAGAMENTO: ');
        $sheet->setCellValue('I6', $this->forma_pagamento ?? 'TODAS');
        $sheet->setCellValue('H7', 'DATA INICIO: ');
        $sheet->setCellValue('I7', $this->data_inicio ?? 'TODAS');
        $sheet->setCellValue('H8', 'DATA FINAL: ');
        $sheet->setCellValue('I8', $this->data_final ?? 'TODAS');
        $sheet->setCellValue('H9', 'SERVIÇO: ');
        $sheet->setCellValue('I9', $this->tipo_servico ?? 'TODAS');
        $coordenadas = $sheet->getCoordinates();

        return [
            // Style the first row as bold text.
            10    => [
                'font' => ['bold' => false, 'color' => ['rgb' => 'FCFCFD']],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => '2b5876']]

            ],

            'H6:I9'    => [
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
