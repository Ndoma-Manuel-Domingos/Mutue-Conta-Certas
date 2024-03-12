<?php

namespace App\Exports;

use App\Http\Controllers\TraitHelpers;
use App\Models\AnoLectivo;
use App\Models\Classe;
use App\Models\Curso;
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

class EstudanteMatriculadoExport extends DefaultValueBinder implements FromCollection, WithMapping, WithTitle, WithHeadings, WithDrawings, WithStyles, WithCustomStartCell, WithCustomValueBinder, ShouldAutoSize
{
    use TraitHelpers, Exportable;

    public $turno , $curso, $titulo , $classe , $candidatura , $ano_lectivo, $total;

    public function __construct($request)
    {
        $this->turno = $request->turno;
        $this->curso = $request->curso;
        $this->classe = $request->classe;
        $this->candidatura = $request->candidatura;
        $this->ano_lectivo = $request->ano_lectivo;
             
        $this->titulo = "LISTA ESTUDANTES MATRÍCULADOS";
    }

    public function headings():array
    {
        return[
            'Nº Matrícula',
            'Nome',
            'Tipo Aluno',
            'Ano Curricular',
            'Telefone',
            'Gênero',
            'Curso',
            'Ano De Ingresso',
        ];
    }

    public function map($item):array
    {
        return[
            $item->codigo_matricula,
            $item->nome,
            'Tipo 1',
            $item->classe,
            $item->telefone,
            $item->genero,
            $item->curso,
            $item->anoLectivo,
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

        return GradeCurricularAluno::select('tb_grade_curricular_aluno.codigo_matricula','tb_preinscricao.Nome_Completo as nome', 'tb_classes.Designacao AS classe', 'tb_preinscricao.Contactos_Telefonicos as telefone', 'tb_preinscricao.Sexo as genero', 'tb_ano_lectivo.Designacao as anoLectivo', 'tb_cursos.Designacao as curso')
        ->leftJoin('tb_matriculas', 'tb_grade_curricular_aluno.codigo_matricula' ,'=','tb_matriculas.Codigo')
        ->leftJoin('tb_cursos', 'tb_matriculas.Codigo_Curso' ,'=','tb_cursos.Codigo')
        ->leftJoin('tb_admissao', 'tb_matriculas.Codigo_Aluno' ,'=','tb_admissao.codigo')
        ->leftJoin('tb_preinscricao', 'tb_admissao.pre_incricao' ,'=','tb_preinscricao.Codigo')
        ->leftJoin('tb_confirmacoes', 'tb_matriculas.Codigo' ,'=','tb_confirmacoes.Codigo_Matricula')
        ->leftJoin('tb_classes', 'tb_confirmacoes.Classe' ,'=','tb_classes.Codigo')
        ->leftJoin('tb_ano_lectivo', 'tb_confirmacoes.Codigo_Ano_lectivo' ,'=','tb_ano_lectivo.Codigo')
        ->when($this->ano_lectivo, function($query, $value){
            $query->where('tb_grade_curricular_aluno.codigo_ano_lectivo', $value);
        })
        ->when($this->curso,  function($query, $value){
            $query->where('tb_cursos.Codigo', $value);
        })
        ->when($this->classe,  function($query, $value){
            $query->where('tb_confirmacoes.Classe', $value);
        })
        ->when($this->candidatura,  function($query, $value){
            $query->where('tb_cursos.tipo_candidatura', $value);
        })
        ->whereIn('Codigo_Status_Grade_Curricular', [2])
        ->distinct('tb_grade_curricular_aluno.codigo_matricula')
        ->orderBy('tb_preinscricao.Nome_Completo', 'ASC')
        ->limit(300)
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
        $drawing->setDescription('LISTA ESTUDANTES MATRÍCULADOS');
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
        $turno = Turno::find($this->turno);
        $curso = Curso::find($this->curso);
        $classe = Classe::find($this->classe);
        $candidatura = GrauAcademico::find($this->candidatura);
        $ano = AnoLectivo::find($this->ano_lectivo);
    
        $sheet->setCellValue('A7', strtoupper($this->titulo));
        $sheet->setCellValue('G6', 'ANO LECTIVO: ');
        $sheet->setCellValue('H6', $ano ? $ano->Designacao : 'Todos');
        $sheet->setCellValue('G7', 'GRADUAÇÃO: ');
        $sheet->setCellValue('H7', $candidatura ? $candidatura->designacao : 'Todos');
        $sheet->setCellValue('G8', 'CURSO: ');
        $sheet->setCellValue('H8', $curso ? $curso->Designacao : 'Todos');
        $sheet->setCellValue('G9', 'TURNO: ');
        $sheet->setCellValue('H9', $turno ?  $turno->Designacao : 'Todos');
        $sheet->setCellValue('G9', 'CLASSE: ');
        $sheet->setCellValue('H9', $classe ?  $classe->Designacao : 'Todos');
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
