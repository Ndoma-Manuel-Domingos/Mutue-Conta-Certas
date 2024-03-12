<?php

namespace App\Exports;

use App\Http\Controllers\TraitHelpers;
use App\Models\GradeCurricularAluno;
use App\Models\Pagamento;
use App\Models\TipoServico;
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

class EstudantePropinaPagaExport extends DefaultValueBinder implements FromCollection, WithMapping, WithTitle, WithHeadings, WithDrawings, WithStyles, WithCustomStartCell, WithCustomValueBinder, ShouldAutoSize
{
    use TraitHelpers, Exportable;

    public $a , $searchMes, $titulo , $searchFaculdade , $searchCurso , $searchTurno;

    public function __construct($a , $request)
    {
        $this->a = $a;
        $this->searchMes = $request->searchMes;
        $this->searchFaculdade = $request->searchFaculdade;
        $this->searchCurso = $request->searchCurso;
        $this->searchTurno = $request->searchTurno;
             
        $this->titulo = "LISTA DE ESTUDANTES COM MENSALIDADES PAGAS";
    }

    public function headings():array
    {
        return[
            'Nº Matrícula',
            'Nome',
            'Faculdade',
            'Curso',
            'Turno',
            'Mês/Parcela',
        ];
    }

    public function map($caixa):array
    {
        return[
            $caixa->matricula,
            $caixa->aluno,
            $caixa->faculdade,
            $caixa->curso,
            $caixa->turno,
            $caixa->servico,
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // recuperar os servicos deste ano lectivo primeiramente mais somente servicos de propinas
        $servicos = TipoServico::where('Descricao', 'like', 'Propina %')->pluck('Codigo');

        $grade_curriculares = GradeCurricularAluno::when($this->a, function ($query, $value) {
            $query->where('codigo_ano_lectivo', '=', $value);
            $query->whereIn('Codigo_Status_Grade_Curricular', [2,3]);
        })->distinct('codigo_matricula')->pluck('codigo_matricula');



        $query = Pagamento::when($this->a, function ($query, $value) {
            $query->where('tb_pagamentos.AnoLectivo', '=', $value);
        });

        if ($this->a >= 2 AND $this->a <= 15){
            $query->when($this->searchMes, function ($query, $value) {
                $query->where('tb_pagamentosi.mes_id', '=', $value);
            });
        }else{
            $query->when($this->searchMes, function ($query, $value) {
                $query->where('tb_pagamentosi.mes_temp_id', '=', $value);
            });
        }

        $query->when($this->searchFaculdade, function ($query, $value) {
            $query->where('tb_cursos.faculdade_id', '=', $value);
        })
        ->when($this->searchCurso, function ($query, $value) {
            $query->where('tb_cursos.Codigo', '=', $value);
        })
        ->when($this->searchTurno, function ($query, $value) {
            $query->where('tb_periodos.Codigo', '=', $value);
        })
        ->join('tb_pagamentosi', 'tb_pagamentos.Codigo', '=', 'tb_pagamentosi.Codigo_Pagamento')
        ->join('tb_preinscricao', 'tb_pagamentos.Codigo_PreInscricao', '=', 'tb_preinscricao.Codigo')
        ->join('tb_admissao', 'tb_preinscricao.Codigo', '=', 'tb_admissao.pre_incricao')
        ->join('tb_matriculas', 'tb_admissao.codigo', '=', 'tb_matriculas.Codigo_Aluno')
        ->join('tb_cursos', 'tb_matriculas.Codigo_Curso', '=', 'tb_cursos.Codigo')
        ->join('tb_faculdade', 'tb_cursos.faculdade_id', '=', 'tb_faculdade.codigo')
        ->join('tb_ano_lectivo', 'tb_pagamentos.AnoLectivo', '=', 'tb_ano_lectivo.Codigo')
        ->join('tb_periodos', 'tb_preinscricao.Codigo_Turno', '=', 'tb_periodos.Codigo')
        ->where('tb_pagamentos.estado', 1)
        // ->whereIn('tb_matriculas.Codigo', $grade_curriculares)
        ->whereIn('tb_pagamentosi.Codigo_Servico', $servicos);

        if ($this->a >= 2 AND $this->a <= 15){
            $query->join('meses', 'tb_pagamentosi.mes_id', '=', 'meses.codigo');
            $query->select(
                'meses.mes AS servico',
                'meses.codigo AS IdServico',
                'tb_matriculas.Codigo AS matricula',
                'tb_preinscricao.Nome_Completo AS aluno',
                'tb_cursos.Designacao AS curso',
                'tb_periodos.Designacao AS turno',
                'tb_faculdade.designacao AS faculdade',
                'tb_pagamentos.Totalgeral AS valores',
                'tb_ano_lectivo.Designacao AS anolectivo',
                'tb_pagamentos.Codigo AS CodigoPagamento',
                'tb_pagamentosi.Valor_Pago AS valorPago',
            );
        }else{
            $query->join('mes_temp', 'tb_pagamentosi.mes_temp_id', '=', 'mes_temp.id');
            $query->select(
                'mes_temp.designacao AS servico',
                'mes_temp.id AS IdServico',
                'tb_matriculas.Codigo AS matricula',
                'tb_preinscricao.Nome_Completo AS aluno',
                'tb_cursos.Designacao AS curso',
                'tb_periodos.Designacao AS turno',
                'tb_faculdade.designacao AS faculdade',
                'tb_pagamentos.Totalgeral AS valores',
                'tb_pagamentos.Codigo AS CodigoPagamento',
                'tb_pagamentosi.Valor_Pago AS valorPago',
            );

        }

        return  $query->get();

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
        $sheet->setCellValue('E6', 'FACULDADE: ');
        $sheet->setCellValue('F6', $this->searchFaculdade ?? 'TODAS');
        $sheet->setCellValue('E7', 'CURSO ');
        $sheet->setCellValue('F7', $this->searchCurso ?? 'TODAS');
        $sheet->setCellValue('E8', 'TURNO: ');
        $sheet->setCellValue('F8', $this->searchTurno ?? 'TODAS');
        $coordenadas = $sheet->getCoordinates();

        return [
            // Style the first row as bold text.
            10    => [
                'font' => ['bold' => false, 'color' => ['rgb' => 'FCFCFD']],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => '2b5876']]

            ],

            'E6:F9'    => [
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
