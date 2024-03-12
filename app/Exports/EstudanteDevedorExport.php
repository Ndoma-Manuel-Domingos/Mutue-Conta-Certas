<?php

namespace App\Exports;

use App\Http\Controllers\TraitHelpers;
use App\Models\AnoLectivo;
use App\Models\Curso;
use App\Models\Matricula;
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

class EstudanteDevedorExport extends DefaultValueBinder implements FromCollection, WithMapping, WithTitle, WithHeadings, WithDrawings, WithStyles, WithCustomStartCell, WithCustomValueBinder, ShouldAutoSize
{
    use TraitHelpers, Exportable;

    public $searchAnoLectivo, $searchMes, $searchFaculdade, $searchCurso, $searchTurno, $titulo;

    public function __construct($request)
    {
        $this->searchAnoLectivo = $request->searchAnoLectivo;
        $this->searchMes = $request->searchMes;
        $this->searchFaculdade = $request->searchFaculdade;
        $this->searchCurso = $request->searchCurso;
        $this->searchTurno = $request->searchTurno;
        
        
        $this->titulo = "LISTA DE ESTUDANTES DEVEDORES";
    }

    public function headings():array
    {
        return[
            'Nº Matrícula',
            'Nome',
            'Curso',
            'Turno',
            'Valor Unitário',
        ];
    }

    public function map($caixa):array
    {
        return[
            $caixa->Codigo,
            $caixa->admissao->preinscricao->Nome_Completo ?? '',
            $caixa->admissao->preinscricao->curso->Designacao ?? '',
            $caixa->admissao->preinscricao->turno->Designacao ?? '',
            number_format(0, 2, ',', '.') ,
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $ano = AnoLectivo::where('estado', 'Activo')->first();

        $anoSelecionado = $this->searchAnoLectivo;

        if (!$anoSelecionado) {
            $anoSelecionado = $ano->Codigo;
        }
        
        $searchFaculdade = $this->searchFaculdade ?? "";
        $searchCurso = $this->searchCurso ?? "";
        $searchTurno = $this->searchTurno ?? ""; 
        $searchMes = $this->searchMes ?? "";
            
        $data['items'] = Matricula::with(['admissao.preinscricao.polo', 'admissao.preinscricao.curso', 'admissao.preinscricao.grau_academico', 'admissao.preinscricao.turno'])
        ->whereRaw('tb_matriculas.Codigo IN (SELECT tgca.codigo_matricula FROM tb_grade_curricular_aluno tgca WHERE tgca.codigo_ano_lectivo = ? AND tgca.Codigo_Status_Grade_Curricular IN (2, 3))', [$anoSelecionado])
        ->whereNotIn('tb_matriculas.Codigo', function ($query) use($searchMes) {
            $query->select('tm_pp.Codigo')
                ->from('tb_matriculas as tm_pp')
                ->join('tb_admissao as tb_admissao', 'tb_admissao.codigo', '=', 'tm_pp.Codigo_Aluno')
                ->join('tb_preinscricao as tb_preinscricao', 'tb_preinscricao.Codigo', '=', 'tb_admissao.pre_incricao')
                ->join('tb_cursos as tb_cursos', 'tb_cursos.Codigo', '=', 'tm_pp.Codigo_Curso')
                ->join('tb_periodos as tp2', 'tp2.Codigo', '=', 'tb_preinscricao.Codigo_Turno')
                ->join('factura as f', 'f.CodigoMatricula', '=', 'tm_pp.Codigo')
                ->leftJoin('factura_items as fi', 'fi.CodigoFactura', '=', 'f.Codigo')
                ->where('fi.estado', 1)
                ->where('fi.mes_temp_id', $searchMes);
        })
        ->whereNotIn('Codigo', function ($query) use($anoSelecionado) {
            $query->select('codigo_matricula')
                ->from('tb_bolseiros')
                ->where('codigo_anoLectivo', $anoSelecionado)
                ->where('status', 0)
                ->whereIn('desconto', [100, 0]);
        })
        ->whereHas('admissao.preinscricao', function($query){
            $query->orderBy('Nome_Completo', 'asc');
        })
        ->whereHas('admissao.preinscricao.curso', function($query) use($searchCurso){
            $query->when($searchCurso, function($query) use($searchCurso){
                $query->where('Codigo', $searchCurso);
            });
            $query->where('tipo_candidatura', 1);
        })
        ->whereHas('admissao.preinscricao.turno', function($query) use($searchTurno){
            $query->when($searchTurno, function($query) use($searchTurno){
                $query->where('Codigo', $searchTurno);
            });
        })
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
        $sheet->setCellValue('D6', 'ANOS LECTIVOS: ');
        $sheet->setCellValue('E6', AnoLectivo::find($this->searchAnoLectivo) ? AnoLectivo::find($this->searchAnoLectivo)->Designacao : 'TODAS');
        $sheet->setCellValue('D7', 'CURSO: ');
        $sheet->setCellValue('E7',  Curso::find($this->searchCurso) ? Curso::find($this->searchCurso)->Designacao : 'TODAS');
        $sheet->setCellValue('D8', 'TURNO: ');
        $sheet->setCellValue('E8', Turno::find($this->searchTurno) ? Turno::find($this->searchTurno)->Designacao :'TODAS');
        $coordenadas = $sheet->getCoordinates();

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
