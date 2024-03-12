<?php

namespace App\Exports;

use App\Http\Controllers\TraitHelpers;
use App\Models\AnoLectivo;
use App\Models\Classe;
use App\Models\Curso;
use App\Models\GradeCurricularAluno;
use App\Models\GrauAcademico;
use App\Models\Matricula;
use App\Models\Nacionalidade;
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

class EstudanteListagemGeralExport extends DefaultValueBinder implements FromCollection, WithMapping, WithTitle, WithHeadings, WithDrawings, WithStyles, WithCustomStartCell, WithCustomValueBinder, ShouldAutoSize
{
    use TraitHelpers, Exportable;

    public $turno , $curso, $titulo , $estado , $candidatura , $ano_lectivo, $total, $numero_matricula_estudantes_por_estado, $nome_estudantes_por_estado, $nacionalidade, $sexo, $faculdade, $necessidades_especial, $grau_academico, $classe;

    public function __construct($request)
    {
 
        $this->turno = $request->turno;
        $this->curso = $request->curso;
        $this->estado = $request->estado;
        $this->candidatura = $request->grau_academico;
        $this->ano_lectivo = $request->ano_lectivo;
        $this->numero_matricula_estudantes_por_estado = $request->numero_matricula_estudantes_por_estado;
        $this->nome_estudantes_por_estado = $request->nome_estudantes_por_estado;
        
        $this->nacionalidade = $request->nacionalidade;
        $this->sexo = $request->sexo;
        $this->faculdade = $request->faculdade;
        $this->necessidades_especial = $request->necessidades_especial;
        $this->grau_academico = $request->grau_academico;
        $this->classe = $request->classe;
             
        $this->titulo = "LISTAGEM GERAL DE ESTUDANTES";
    }

    public function headings():array
    {
        return[
            'Nº Matrícula',
            'Nome',
            'Sexo',
            'Faculdade',
            'Curso',
            'Necessidade',
            'Nacionalidade',
            'Período',
            'Ano Lectivo',
            'Tipo Aluno',
            'Ano Curricular',
        ];
    }

    public function map($item):array
    {
        return[
            $item->codigo ?? "",
            $item->nome ?? "",
            $item->sexo ?? "",
            $item->faculdade ?? "",
            $item->curso ?? "",
            $item->nessecidade ?? "",
            $item->nacionalidade ?? "",
            $item->periodo ?? "",
            $item->anolectivo ?? "",
            'Tipo 1',
            $item->classe ?? "",
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
        
                        
        if($this->classe == "7"){
            $this->classe = "";
        }
        

        return Matricula::leftJoin('tb_confirmacoes', 'tb_matriculas.Codigo' ,'=','tb_confirmacoes.Codigo_Matricula')
        ->leftJoin('tb_cursos', 'tb_matriculas.Codigo_Curso' ,'=','tb_cursos.Codigo')
        ->leftJoin('tb_faculdade', 'tb_cursos.faculdade_id' ,'=','tb_faculdade.codigo')
        ->leftJoin('tb_admissao', 'tb_matriculas.Codigo_Aluno' ,'=','tb_admissao.codigo')
        ->leftJoin('tb_preinscricao', 'tb_admissao.pre_incricao' ,'=','tb_preinscricao.Codigo')
        ->leftJoin('tb_nacionalidades', 'tb_preinscricao.Codigo_Nacionalidade' ,'=','tb_nacionalidades.Codigo')
        ->leftJoin('tb_ano_lectivo', 'tb_confirmacoes.Codigo_Ano_lectivo' ,'=','tb_ano_lectivo.Codigo')
        ->leftJoin('necessidade_especiais', 'tb_preinscricao.necessidade_especial_id' ,'=','necessidade_especiais.id')
        ->leftJoin('tb_periodos', 'tb_preinscricao.Codigo_Turno' ,'=','tb_periodos.Codigo')
        ->leftJoin('tb_grade_curricular_aluno', 'tb_matriculas.Codigo' ,'=','tb_grade_curricular_aluno.codigo_matricula')
        ->leftJoin('tb_grade_curricular', 'tb_grade_curricular_aluno.codigo_grade_curricular' ,'=','tb_grade_curricular.Codigo')
        ->leftJoin('tb_classes', 'tb_grade_curricular.Codigo_Classe' ,'=','tb_classes.Codigo')
        ->when($this->nacionalidade,  function($query, $value){
            $query->where('tb_nacionalidades.Codigo', $value);
        })
        ->when($this->ano_lectivo,  function($query, $value){
            $query->where('tb_ano_lectivo.Codigo', $value);
        })
        ->when($this->sexo,  function($query, $value){
            $query->where('tb_preinscricao.Sexo', $value);
        })
        ->when($this->curso,  function($query, $value){
            $query->where('tb_cursos.Codigo', $value);
        })
        ->when($this->faculdade,  function($query, $value){
            $query->where('tb_cursos.faculdade_id', $value);
        })
        ->when($this->turno,  function($query, $value){
            $query->where('tb_periodos.Codigo', $value);
        })
        ->when($this->necessidades_especial,  function($query, $value){
            $query->where('necessidade_especiais.id', $value);
        })
        ->when($this->grau_academico,  function($query, $value){
            $query->where('tb_cursos.tipo_candidatura', $value);
        })
        ->when($this->classe,  function($query, $value){
            $query->where('tb_grade_curricular.Codigo_Classe', $value);
        })
        ->select(
            'tb_matriculas.Codigo AS codigo',
            'tb_preinscricao.Nome_Completo AS nome',
            'tb_preinscricao.Sexo AS sexo',
            'tb_faculdade.designacao AS faculdade',
            'tb_cursos.Designacao AS curso',
            'tb_classes.Designacao AS classe',
            'tb_cursos.grau AS cursoGrau',
            'tb_ano_lectivo.Designacao AS anolectivo',
            'tb_nacionalidades.Designacao AS nacionalidade',
            'necessidade_especiais.designacao AS nessecidade',
            'tb_periodos.Designacao AS periodo',
            'tb_grade_curricular.Codigo_Classe AS anoCurricular'
        )
        ->distinct('tb_matriculas.Codigo')
        ->orderBy('tb_preinscricao.Nome_Completo', 'asc')
        ->limit(200)
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
        $drawing->setDescription('LISTAGEM ESTUDANTES POR ESTADO');
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
        $nacionalidade = Nacionalidade::find($this->nacionalidade);
    
        $sheet->setCellValue('A7', strtoupper($this->titulo));
        $sheet->setCellValue('G6', 'ANO LECTIVO: ');
        $sheet->setCellValue('H6', $ano ? $ano->Designacao : 'Todos');
        $sheet->setCellValue('G7', 'GRADUAÇÃO: ');
        $sheet->setCellValue('H7', $candidatura ? $candidatura->designacao : 'Todos');
        $sheet->setCellValue('G8', 'CURSO: ');
        $sheet->setCellValue('H8', $curso ? $curso->Designacao : 'Todos');
        $sheet->setCellValue('G9', 'TURNO: ');
        $sheet->setCellValue('H9', $turno ?  $turno->Designacao : 'Todos');
        $sheet->setCellValue('G5', 'CLASSE: ');
        $sheet->setCellValue('H5', $classe ? $classe->Designacao : 'Todos');
        $sheet->setCellValue('G4', 'NACIONALIDADE: ');
        $sheet->setCellValue('H4', $nacionalidade ? $nacionalidade->Designacao : 'Todos');
        $coordenadas = $sheet->getCoordinates();

        return [
            // Style the first row as bold text.
            10    => [
                'font' => ['bold' => false, 'color' => ['rgb' => 'FCFCFD']],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => '2b5876']]

            ],

            'G4:H9'    => [
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
