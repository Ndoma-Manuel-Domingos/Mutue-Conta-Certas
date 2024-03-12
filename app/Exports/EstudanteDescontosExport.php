<?php

namespace App\Exports;

use App\Http\Controllers\TraitHelpers;
use App\Models\AnoLectivo;
use App\Models\ConclusaoCursoAluno;
use App\Models\Curso;
use App\Models\DescontoEspecia;
use App\Models\Faculdade;
use App\Models\GrauAcademico;
use App\Models\Simestre;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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

class EstudanteDescontosExport extends DefaultValueBinder implements FromCollection, WithMapping, WithTitle, WithHeadings, WithDrawings, WithStyles, WithCustomStartCell, WithCustomValueBinder, ShouldAutoSize
{
    use TraitHelpers, Exportable;

    public $semestre, $titulo , $tipoDesconto , $ano_lectivo, $total;
    

    public function __construct($request)
    {
        $this->semestre = $request->semestre;
        $this->tipoDesconto = $request->tipoDesconto;
        $this->ano_lectivo = $request->ano_lectivo;
             
        $this->titulo = "LISTA ESTUDANTES COM DESCONTO";
    }

    public function headings():array
    {
        return[
            'Matrícula',
            'Nome',
            'Instituição',
            'Tipo de Desconto',
            'Afectação',
            'Período',
            '%.Desconto',
            'Ano Lectivo',
            'Status',
        ];

    }

    public function map($item):array
    {
        return[
            
            $item->matricula,
            $item->Nome_Completo,
            $item->NomeInstituicao,
            $item->tipoDesconto,
            $item->afectacao,
            $item->semestre,
            $item->valor,
            $item->Ano,
            $item->status,
      
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    
        if($this->ano_lectivo){
            $this->ano_lectivo = $this->ano_lectivo;
        }else {
            $this->ano_lectivo = 21;
        }

        return DB::table('tb_descontos_alunoo')
        ->when($this->ano_lectivo, function($query, $value){
            $query->where('codigo_anoLectivo', $value);
        })
        ->when($this->semestre, function($query, $value){
            $query->where('semestre', $value);
        })
        ->when($this->tipoDesconto, function($query, $value){
            $query->where('tipo_taxa_desconto_especial', $value);
        })
        ->join('tb_matriculas', 'tb_descontos_alunoo.codigo_matricula', '=', 'tb_matriculas.Codigo')
        ->join('tb_ano_lectivo','tb_descontos_alunoo.codigo_anoLectivo','=','tb_ano_lectivo.Codigo')
        ->join('tb_Instituicao','tb_descontos_alunoo.instituicao_id','=','tb_Instituicao.codigo')
        ->join('descontos_especiais','tb_descontos_alunoo.tipo_taxa_desconto_especial','=','descontos_especiais.id')
        ->join('tb_semestres','tb_descontos_alunoo.semestre','=','tb_semestres.Codigo')
        ->join('tb_status','tb_descontos_alunoo.estatus_desconto_id','=','tb_status.Codigo')
        ->join('tb_admissao','tb_matriculas.Codigo_Aluno','=','tb_admissao.Codigo')
        ->join('tb_preinscricao','tb_preinscricao.Codigo','=','tb_admissao.pre_incricao')
        ->select('tb_matriculas.Codigo AS matricula','tb_ano_lectivo.Designacao As Ano','tb_Instituicao.Instituicao AS NomeInstituicao','descontos_especiais.descricao AS tipoDesconto','tb_descontos_alunoo.estatus_desconto_id',
        'tb_descontos_alunoo.afectacao','tb_semestres.Designacao AS semestre','descontos_especiais.taxa AS valor','tb_status.Designacao AS status', 'tb_preinscricao.Nome_Completo', 'tb_descontos_alunoo.codigo_anoLectivo',
        'tb_descontos_alunoo.instituicao_id', 'tb_descontos_alunoo.semestre AS semestre_id', 'tb_descontos_alunoo.tipo_taxa_desconto_especial', 'tb_descontos_alunoo.codigo', 'tb_descontos_alunoo.codigo_matricula')
        ->orderBy('tb_descontos_alunoo.codigo', 'desc')
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
        $drawing->setDescription('LISTA ESTUDANTES COM DESCONTO');
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
        
        $desconto = DescontoEspecia::find($this->tipoDesconto);
        $semestre = Simestre::find($this->semestre);
        $ano = AnoLectivo::find($this->ano_lectivo);

        $sheet->setCellValue('A7', strtoupper($this->titulo));
        $sheet->setCellValue('G6', 'ANO LECTIVO: ');
        $sheet->setCellValue('H6', $ano ? $ano->Designacao : 'Todos');
        $sheet->setCellValue('G7', 'TIPO DE DESCONTO: ');
        $sheet->setCellValue('H7', $desconto ? $desconto->designacao : 'Todos');
        $sheet->setCellValue('G8', 'SIMESTRE: ');
        $sheet->setCellValue('H8', $semestre ? $semestre->Designacao : 'Todos');
        // $sheet->setCellValue('G9', 'FACULDADE: ');
        // $sheet->setCellValue('H9', $faculdade ?  $faculdade->designacao : 'Todos');

        $coordenadas = $sheet->getCoordinates();

        return [
            // Style the first row as bold text.
            10    => [
                'font' => ['bold' => false, 'color' => ['rgb' => 'FCFCFD']],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => '2b5876']]

            ],

            'G6:H9'    => [
                'font' => ['bold' => false, 'color' => ['rgb' => 'FCFCFD']],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => '2b5876']]

            ],

            // Styling a specific cell by coordinate.
            'A7' => ['font' => ['bold' => true, 'color' => ['rgb' => '00008B']]],
            'H7' => ['font' => ['bold' => true, 'color' => ['rgb' => '00008B']]],
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
