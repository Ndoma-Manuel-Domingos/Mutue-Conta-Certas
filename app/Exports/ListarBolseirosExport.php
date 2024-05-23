<?php

namespace App\Exports;

use App\Http\Controllers\TraitHelpers;
use App\Models\AnoLectivo;
use App\Models\Bolseiro;
use App\Models\Curso;
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

class ListarBolseirosExport extends DefaultValueBinder implements FromCollection, WithMapping, WithTitle, WithHeadings, WithDrawings, WithStyles, WithCustomStartCell, WithCustomValueBinder, ShouldAutoSize
{
    use TraitHelpers, Exportable;

    public $ano_lectivo_id, $curso_id, $instituicao_id, $tipo_de_bolsa, $desconto, $estado, $semestre, $percentagem, $titulo;

    public function __construct($request)
    {
        $this->ano_lectivo_id = $request->ano_lectivo_id;
        $this->curso_id = $request->curso_id ?? 0;
        $this->tipo_de_bolsa = $request->tipo_de_bolsa ?? 0;
        $this->percentagem = $request->percentagem ?? 0;
        $this->instituicao_id = $request->instituicao_id ?? 0;
        $this->desconto = $request->desconto ?? 0;
        $this->estado = $request->estado ?? 0;
        $this->semestre = $request->semestre ?? 0;
        $this->titulo = "LISTAGEM ESTUDANTES BOLSEIROS";

    }

    public function headings():array
    {
        return [
            "Matrícula",
            "Nome",
            "Curso",
            "Tipo de Bolsa",
            "Percentagem",
            "Estado",
            "Semestre",
            "Instituição",
        ];
    }

    public function map($caixa):array
    {
        return [
            $caixa->codigo_matricula ?? '',
            $caixa->nome ?? '',
            $caixa->curso ?? '',
            $caixa->tipobolsa ?? '',
            $caixa->desconto ?? '',
            $caixa->status == 0 ? "ACTIVO" : "INACTIVO",
            $caixa->semestreItem ?? '',
            $caixa->Instituicao ?? '',
        ];

    }

    /**
    * @return \Illuminate\Support\Collection
    */
        public function collection()
        {
            $ano = AnoLectivo::where('estado', 'Activo')->first();


            if($this->instituicao_id == 0){ $this->instituicao_id = ""; }

            if($this->semestre == 0){ $this->semestre = ""; }

            if($this->percentagem == 120){ $this->percentagem = ""; }

            if($this->ano_lectivo_id){
                $this->ano_lectivo_id = $this->ano_lectivo_id;
            }else{
                $this->ano_lectivo_id = $ano->Codigo;
            }

            if($this->curso_id == 0){ $this->curso_id = ""; }

            if($this->tipo_de_bolsa == 0){ $this->tipo_de_bolsa = ""; }

            if($this->estado == "0"){
                $this->estado = [false] ;
            }else if($this->estado == "1"){
                $this->estado =  [true];
            }else{
                $this->estado =  [true, false];
            }

            return Bolseiro::when($this->ano_lectivo_id, function ($query, $value) {
                $query->where('tb_bolseiros.codigo_anoLectivo', $value);
            })
            ->whereIn('tb_bolseiros.status', $this->estado)
            ->when($this->curso_id, function ($query, $value) {
                $query->where('tb_cursos.Codigo', $value);
            })
            ->when($this->instituicao_id, function ($query, $value) {
                $query->where('tb_bolseiros.codigo_Instituicao', $value);
            })
            ->when($this->tipo_de_bolsa, function ($query, $value) {
                $query->where('tb_bolseiros.codigo_tipo_bolsa', $value);
            })
            ->when($this->percentagem, function ($query, $value) {
                $query->where('tb_bolseiros.desconto', $value);
            })
            ->when($this->semestre, function ($query, $value) {
                $query->where('tb_bolseiros.semestre', $value);
            })
            ->join('tb_matriculas', 'tb_bolseiros.codigo_matricula', '=', 'tb_matriculas.Codigo')
            ->join('tb_cursos', 'tb_matriculas.Codigo_Curso', '=', 'tb_cursos.Codigo')
            ->join('tb_admissao', 'tb_matriculas.Codigo_Aluno', '=', 'tb_admissao.codigo')
            ->join('tb_preinscricao', 'tb_admissao.pre_incricao', '=', 'tb_preinscricao.Codigo')
            ->join('tb_tipo_bolsas', 'tb_bolseiros.codigo_tipo_bolsa', '=', 'tb_tipo_bolsas.codigo')
            ->join('tb_Instituicao', 'tb_bolseiros.codigo_Instituicao', '=', 'tb_Instituicao.codigo')
            ->join('tb_semestres', 'tb_bolseiros.semestre', '=', 'tb_semestres.Codigo')
            ->join('tb_ano_lectivo', 'tb_bolseiros.codigo_anoLectivo', '=', 'tb_ano_lectivo.Codigo')
            ->select('tb_matriculas.Codigo AS matricula', 'tb_cursos.Designacao AS curso', 'tb_bolseiros.codigo_matricula', 'tb_bolseiros.codigo', 'tb_tipo_bolsas.designacao AS tipobolsa',
                'tb_bolseiros.desconto', 'tb_bolseiros.status', 'tb_semestres.Designacao AS semestreItem', 'tb_preinscricao.Nome_Completo As nome', 'tb_bolseiros.codigo_anoLectivo',
                'tb_bolseiros.codigo_Instituicao',
                'tb_Instituicao.Instituicao',
                'tb_bolseiros.semestre',
                'tb_bolseiros.afectacao',
                'tb_bolseiros.codigo_tipo_bolsa',
                'tb_bolseiros.desconto',
                'tb_bolseiros.status',
                'tb_ano_lectivo.Designacao AS anoLectivo',
                'tb_preinscricao.Codigo AS preninscricaoCodigo'
            )
            ->orderBy('tb_bolseiros.codigo', 'desc')
            ->get();

            // dd();

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
        $drawing->setDescription('LISTAGEM ESTUDANTES BOLSEIROS');
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

        $ano = AnoLectivo::find($this->ano_lectivo_id);
        $curso = Curso::find($this->curso_id);

        $sheet->setCellValue('A7', strtoupper($this->titulo));
        $sheet->setCellValue('E6', 'ANO LECTIVO: ');
        $sheet->setCellValue('F6',  $ano->Designacao);
        $sheet->setCellValue('E7', 'CURSO ');
        $sheet->setCellValue('F7', $curso ? $curso->Designacao : 'TODAS');
        // $sheet->setCellValue('E8', 'TURNO: ');
        // $sheet->setCellValue('F8', $this->searchTurno ?? 'TODAS');
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

        ];
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
