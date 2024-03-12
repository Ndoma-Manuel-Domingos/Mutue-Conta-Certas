<?php

namespace App\Exports;

use App\Http\Controllers\TraitHelpers;
use App\Models\AnoLectivo;
use App\Models\Classe;
use App\Models\ConclusaoCursoAluno;
use App\Models\Curso;
use App\Models\Faculdade;
use App\Models\GradeCurricularAluno;
use App\Models\GrauAcademico;
use App\Models\Pagamento;
use App\Models\TipoServico;
use App\Models\Turno;
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

class EstudanteDiplomadosExport extends DefaultValueBinder implements FromCollection, WithMapping, WithTitle, WithHeadings, WithDrawings, WithStyles, WithCustomStartCell, WithCustomValueBinder, ShouldAutoSize
{
    use TraitHelpers, Exportable;

    public $genero , $curso, $titulo , $faculdade , $candidatura , $ano_lectivo, $total;

    public function __construct($request)
    {
        $this->genero = $request->genero;
        $this->curso = $request->curso;
        $this->faculdade = $request->faculdade;
        $this->candidatura = $request->candidatura;
        $this->ano_lectivo = $request->ano_lectivo;
             
        $this->titulo = "LISTA ESTUDANTES DIPLOMADOS";
    }

    public function headings():array
    {
        return[
            'Nº Matrícula',
            'Nome',
            'Nº Bilhete',
            'Gênero',
            'Tipo Aluno',
            'Data Nascimento',
            'Curso',
            'Tipo Candidatura',
            'Data Matrícula',
            'Data Conclusão',
            'Nota',
        ];

    }

    public function map($item):array
    {
        return[
            $item->codigo_matricula,
            $item ? ($item->matricula ? ($item->matricula->admissao ? ($item->matricula->admissao->preinscricao ? $item->matricula->admissao->preinscricao->Nome_Completo : "") : "") : "")  : "",
            $item ? ($item->matricula ? ($item->matricula->admissao ? ($item->matricula->admissao->preinscricao ? $item->matricula->admissao->preinscricao->Bilhete_Identidade : "") : "") : "")  : "",
            $item ? ($item->matricula ? ($item->matricula->admissao ? ($item->matricula->admissao->preinscricao ? $item->matricula->admissao->preinscricao->Sexo : "") : "") : "")  : "",
            "Tipo 1",
            $item ? ($item->matricula ? ($item->matricula->admissao ? ($item->matricula->admissao->preinscricao ? $item->matricula->admissao->preinscricao->Data_Nascimento : "") : "") : "")  : "",
            $item ? ($item->matricula ? ($item->matricula->curso ? $item->matricula->curso->Designacao : "") : "") : "" ,
            $item ? ($item->matricula ? ($item->matricula->admissao ? ($item->matricula->admissao->preinscricao ? ($item->matricula->admissao->preinscricao->grau_academico ? $item->matricula->admissao->preinscricao->grau_academico->designacao : "") : "") : "") : "")  : "",
            
            $item ? ($item->matricula ? $item->matricula->Data_Matricula : "") : "" ,
            $item->data_conclusao,
            $item->nota,
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    
        if($this->ano_lectivo){
            $ano_lectivo = $this->ano_lectivo;
        }else {
            $ano_lectivo = 21;
        }

        $curso = $this->curso;
        $candidatura = $this->candidatura;
        $genero = $this->genero;

        return ConclusaoCursoAluno::when($ano_lectivo, function($query, $value){
            $query->where("ano_lectivo", $value);
        })
        ->whereHas('matricula', function($query) use($curso){
            $query->when($this->curso, function($query)  use($curso){
                $query->where("Codigo_Curso", $curso);
            });
        })
        ->whereHas('matricula.admissao.preinscricao', function($query) use($candidatura){
            $query->when($candidatura, function($query)  use($candidatura){
                $query->where("codigo_tipo_candidatura", $candidatura);
            });
        })
        ->whereHas('matricula.admissao.preinscricao', function($query) use($genero){
            $query->when($genero, function($query)  use($genero){
                $query->where("Sexo", $genero);
            });
        })
        ->with(['matricula.admissao.preinscricao.grau_academico','matricula.curso', 'ano'])
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
        $drawing->setDescription('LISTA ESTUDANTES DIPLOMADOS');
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
        $faculdade = Faculdade::find($this->faculdade);
        $curso = Curso::find($this->curso);
        $candidatura = GrauAcademico::find($this->candidatura);
        $ano = AnoLectivo::find($this->ano_lectivo);
    
        $sheet->setCellValue('A7', strtoupper($this->titulo));
        $sheet->setCellValue('G6', 'ANO LECTIVO: ');
        $sheet->setCellValue('H6', $ano ? $ano->Designacao : 'Todos');
        $sheet->setCellValue('G7', 'GRADUAÇÃO: ');
        $sheet->setCellValue('H7', $candidatura ? $candidatura->designacao : 'Todos');
        $sheet->setCellValue('G8', 'CURSO: ');
        $sheet->setCellValue('H8', $curso ? $curso->Designacao : 'Todos');
        $sheet->setCellValue('G9', 'FACULDADE: ');
        $sheet->setCellValue('H9', $faculdade ?  $faculdade->designacao : 'Todos');

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
