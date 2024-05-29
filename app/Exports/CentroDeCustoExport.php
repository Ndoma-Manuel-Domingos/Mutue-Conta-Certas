<?php

namespace App\Exports;


use App\Models\Classe;
use App\Models\SubConta;
use App\Models\Conta;
use App\Models\ContaEmpresa;
use App\Models\Exercicio;
use App\Models\TipoBalancete;
use App\Models\MovimentoItem;
use App\Models\Periodo;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\UserEmpresa;

use App\Models\CentroDeCusto;
use GuzzleHttp\Psr7\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
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
use App\Models\Empresa;


class CentroDeCustoExport extends DefaultValueBinder implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithMapping, WithEvents, WithDrawings, WithCustomStartCell
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $dadosEmpresa;
    public $data_inicio, $data_final, $exercicioId, $tipo_balancete_id, $periodo_id;

    public function headings(): array
    {
        // Defina os cabeçalhos das colunas
        return [

            'Designação',
            'Notas',
            'Exercício',
        ];
    }

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

    public function dadosEmpresaLogada($id)
    {
        $empresa = Empresa::where('id', $id)->first();
        if ($empresa)
            return $empresa;
        else
            return "";
    }


    public function __construct($data_inicio, $data_final, $exercicio_id, $tipo_balancete_id, $periodo_id)
    {
        $this->data_inicio = $data_inicio;
        $this->data_final = $data_final;
        $this->exercicioId = $exercicio_id;
        $this->tipo_balancete_id = $tipo_balancete_id;
        $this->periodo_id = $periodo_id;
    }

    public function map($dados): array
    {
        return [
            $dados->conta_id,
            $dados->debito,
            $dados->credito,
        ];
    }
    public function collection()
    {

        $tipo_balancete_id = $this->tipo_balancete_id;
        $periodo_id = $this->periodo_id;
        $data_inicio = $this->data_inicio;
        $data_final = $this->data_final;
        $exercicio = $this->exercicioId;

        $users = User::with('empresa')->findOrFail(auth()->user()->id);

        $data['registros'] = ContaEmpresa::with(['classe', 'conta.items_movimentos' => function ($query) {
            $query->select(
                'conta_id',
                DB::raw('sum(debito) as TotalDebito'),
                DB::raw('sum(credito) as TotalCredito'),
            )->groupBy('conta_id');
        }, 'sub_contas_empresa.items_movimentos' => function ($query) {
            $query->select(
                'subconta_id',
                DB::raw('sum(debito) as total_debito'),
                DB::raw('sum(credito) as total_credito'),
            )->groupBy('subconta_id');
        }])
            ->whereHas('sub_contas_empresa.items_movimentos.movimento', function ($query) use ($data_inicio) {
                $query->when($data_inicio, function ($query, $value) {
                    $query->whereDate('data_lancamento',  ">=", $value);
                });
            })
            ->whereHas('sub_contas_empresa.items_movimentos.movimento', function ($query) use ($data_final) {
                $query->when($data_final, function ($query, $value) {
                    $query->whereDate('data_lancamento', "<=", $value);
                });
            })
            ->paginate(15);

        $valores = [];
        $valores_por_conta = [];

        $movimento_debito = 0;
        $movimento_credito = 0;
        $total_credito = 0;
        $total_debito = 0;
        $total_por_conta_credito = 0;
        $total_por_conta_debito = 0;

        foreach ($data['registros'] as $movimento) {
            foreach ($movimento['conta']['items_movimentos'] as $contas) {

                $total_por_conta_credito += $contas->TotalCredito;
                $total_por_conta_debito += $contas->TotalDebito;

                $valores_por_conta = [
                    "total_credito" => $total_por_conta_credito,
                    "total_debito" => $total_por_conta_debito,
                ];
            }

            foreach ($movimento['sub_contas_empresa'] as $mov) {

                foreach ($mov['items_movimentos'] as $resultado) {

                    if ($resultado->total_debito > $resultado->total_credito) {
                        $movimento_debito += ($resultado->total_debito - $resultado->total_credito);
                    }

                    if ($resultado->total_credito > $resultado->total_debito) {
                        $movimento_credito += ($resultado->total_credito - $resultado->total_debito);
                    }

                    $total_credito += $resultado->total_credito;
                    $total_debito += $resultado->total_debito;

                    $valores = [
                        "total_movimento_credito" => $movimento_credito,
                        "total_movimento_debito" => $movimento_debito,
                        "total_credito" => $total_credito,
                        "total_debito" => $total_debito,
                    ];
                }
            }
        }

        $data['resultado'] = $valores;
        $data['resultado_por_conta'] = $valores_por_conta;
        $data['exercicio'] = Exercicio::find($exercicio); //select('id', 'designacao As text')->get();
        $data['periodo'] = Periodo::find($periodo_id); //select('id', 'designacao As text')->get();
        $data['tipo_balancete'] = TipoBalancete::find($tipo_balancete_id); //select('id', 'designacao As text')->get();

        $dados = [
            'registros' => $data['registros'],
            'resultado' => $data['resultado'],
            'resultado_por_conta' => $data['resultado_por_conta'],
            'exercicio' => $data['exercicio'],
        ];

        return $dados;
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
                    ]
                ]);

                $event->sheet->getColumnDimension('A')->setWidth(100);
                $event->sheet->getColumnDimension('B')->setWidth(200);
                $event->sheet->getColumnDimension('C')->setWidth(300);
            }
        ];
    }

    public function startCell(): String
    {
        return "A10";
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
        $sheet->setCellValue('D6', strtoupper('BALANCO'));
        $sheet->setCellValue('D7', 'Empresa: ');
        $sheet->setCellValue('D8', 'NIF: ');
        $sheet->setCellValue('E7', $this->dadosEmpresa->nome_empresa);
        $sheet->setCellValue('E8',  $this->dadosEmpresa->codigo_empresa);
        $coordenadas = $sheet->getCoordinates();

        return [
            // Style the first row as bold text.
            10    => [
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
            // 'G6' => ['font' => ['bold' => true, 'color' => ['rgb' => '00008B']]],

            'A11:' . end($coordenadas) => ['borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ]],

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
