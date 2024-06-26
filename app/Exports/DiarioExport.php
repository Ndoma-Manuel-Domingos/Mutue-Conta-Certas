<?php

namespace App\Exports;

use App\Models\Diario;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\CentroDeCusto;
use GuzzleHttp\Psr7\Request;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\DefaultValueBinder;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Color;
use App\Models\UserEmpresa;
use App\Models\Empresa;
use App\Models\User;

class DiarioExport extends DefaultValueBinder implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithMapping, WithEvents, WithDrawings, WithCustomStartCell
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $dadosEmpresa;

    public function empresaLogada()
    {
        $user = auth()->user();

        if ($user) {

            $user = User::find($user->id);

            $empresa = UserEmpresa::where('estado', '1')->where('user_id', $user->id)->first();
            if ($empresa) {
                return $empresa->empresa_id;
            } else {
                return "";
            }
        }

        return "";
    }

    public function dadosEmpresaLogada($id){
        $empresa = Empresa::where('id', $id)->first();
        if($empresa)
            return $empresa;
        else
            return "";
    }
    public function headings(): array
    {
        // Defina os cabeçalhos das colunas
        return [

            '#',
            'Código',
            'Designação',
            'Estado',
        ];
    }

    public function __construct()
    {
        $this->dadosEmpresa = $this->dadosEmpresaLogada($this->empresaLogada());
    }

    public function map($data): array
    {

        return [
            '#',
            $data->numero,
            $data->designacao,
            $data->estado,

        ];
    }


    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A6:C6')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,

                        ],
                    ],

                    $event->sheet->getStyle('E11:E' . $event->sheet->getHighestRow())
                        ->getFont()->setColor(new Color('FF0000')),

                    $event->sheet->getStyle('D11:D' . $event->sheet->getHighestRow())
                        ->getFont()->setColor(new Color('0000CD')),

                ]);
                $event->sheet->getColumnDimension('E')->setWidth(28);
                // $event->sheet->getColumnDimension('B')->setWidth(200);
                // $event->sheet->getColumnDimension('C')->setWidth(300);
            },

        ];
    }

    public function collection()
    {
        return Diario::with(['empresa'])->where('empresa_id', $this->empresaLogada())->get();
    }


    public function startCell(): String
    {
        return "A11";
    }

    public function drawings()
    {

        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Balanço');
        $drawing->setPath(public_path('/images/log1.png'));
        $drawing->setHeight(90);
        $drawing->setCoordinates('A1');

        return $drawing;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setCellValue('C6', strtoupper('BALANCO'));
        $sheet->setCellValue('C7', 'Empresa: ');
        $sheet->setCellValue('C8', 'NIF: ');
        $sheet->setCellValue('D7', $this->dadosEmpresa->nome_empresa);
        $sheet->setCellValue('D8',  $this->dadosEmpresa->codigo_empresa);
        $coordenadas = $sheet->getCoordinates();

        return [
            // Style the first row as bold text.
            11    => [
                'font' => ['bold' => false, 'color' => ['rgb' => 'FCFCFD']],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => '2b5876']]

            ],

            // 'E6:F9'    => [
            //     'font' => ['bold' => false, 'color' => ['rgb' => 'FCFCFD']],
            //     'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => '2b5876']]

            // ],

            // Styling a specific cell by coordinate.
            'A7' => ['font' => ['bold' => true, 'color' => ['rgb' => '00008B']]],
            'F7' => ['font' => ['bold' => true, 'color' => ['rgb' => '00008B']]],
            // 'D11:D' => ['font' => ['bold' => true, 'color' => ['rgb' => 'FF0000']]],
            // 'G6' => ['font' => ['bold' => true, 'color' => ['rgb' => '00008B']]],

            'A11:' . end($coordenadas) => ['borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ]],

            'C6:D9'    => [
                'font' => ['bold' => false, 'color' => ['rgb' => 'FCFCFD']],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => '2b5876']]

            ],

        ];
    }

    public function bindValue(Cell $cell, $value)
    {

        if (is_string($value)) {
            $cell->setValueExplicit(strval($value), DataType::TYPE_STRING);

            return true;
        }


        return parent::bindValue($cell, strval($value));
    }
}
