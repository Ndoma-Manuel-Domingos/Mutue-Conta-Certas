<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Extrato de Contas</title>

    <link rel="stylesheet" href="css/style.css" media="all" />
    <style>
        .page-break {
            page-break-after: always;
        }

        .blue-text {
            color: blue;
        }

        .red-text {
            color: red;
        }

        .green-text {
            color: green;
        }

        .orange-text {
            color: orange;
        }

        .footer {
            font-size: 0.875rem;
            padding: 1rem;
            background-color: rgb(255, 255, 255);
            bottom: 0;
            position: fixed;
            width: 90%;
            text-align: center;
        }

    </style>
</head>

<body>
    <header class="clearfix">
        <div id="logo" style="padding: 0">
            <img src="images/{{$dados_empresa->logotipo_da_empresa ?? 'log.png' }}" style="width: 150px;height: 150px;position: absolute;top: -40px">
        </div>
        <div id="company">
            <h2 class="name">{{$dados_empresa->nome_empresa}}</h2>
            <div>{{$dados_empresa->endereco->rua}}, {{$dados_empresa->endereco->bairro}}, <br>
            </div>
            <div>+244 947716133/+244 942364667</div>
            <div><a href="mailto:geral@uma.co.ao">geral@uma.co.ao</a></div>
        </div>
        </div>
    </header>
    <main>
        @if ($subconta)
        <h2 style="text-align: left;border-bottom: 1px solid #c2c2c2;padding-bottom: 2px;text-transform: uppercase;font-size: 11pt;font-weight: 100">  Extracto da Conta - {{ $subconta->numero ?? '' }}. {{ $subconta->designacao ?? '' }}</h2>
        @else
        <h2 style="text-align: left;border-bottom: 1px solid #c2c2c2;padding-bottom: 2px;text-transform: uppercase;font-size: 11pt;font-weight: 100">  Extractos das Contas</h2>
        @endif
        <h2 style="text-align: left;border-bottom: 1px solid #c2c2c2;padding-bottom: 2px;text-transform: uppercase;font-size: 11pt;font-weight: 100"> Período: {{ $requests['data_inicio'] ?? date('Y-m-d')  }} a {{ $requests['data_final'] ?? date('Y-m-d') }}</h2>
        <div style="margin:auto 0px">

        </div>
        <table  id="tabela_de_balancetes">
            <thead>
            
                <tr>
                    <th style="background-color: gray; color: white;"><strong>Nº</strong></th>
                    <th style="background-color: gray; color: white;"><strong>Nº Movimento</strong></th>
                    <th style="background-color: gray; color: white;text-align: left;"><strong>Data</strong></th>
                    <th style="background-color: gray; color: white;text-align: left;"><strong>Descrição</strong></th>
                    <th style="background-color: gray; color: white;text-align: right;"><strong>Devedor</strong></th>
                    <th style="background-color: gray; color: white;text-align: right;"><strong>Credor</strong></th>
                </tr>
            </thead>

            <tbody>
              
                @foreach ($movimentos as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td style="text-align: center;">{{ $item->id }}</td>
                    <td style="text-align: left;">{{ $item->movimento->data_lancamento }}</td>
                    <td style="text-align: left;">{{ $item->descricao }}</td>
                    <td style="color: blue;">{{ $item->debito == 0 ? '-' : number_format($item->debito, 2, ',', '.') }}</td>
                    <td style="color: red;">{{ $item->credito == 0 ? '-' : number_format($item->credito, 2, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th style="text-align: right;">TOTAL</th>
                    <th style="font-weight: bold;text-align: right;color: blue;">{{ ($resultado['total_debito'] ?? 0) == 0 ? '-' : number_format(($resultado['total_debito'] ?? 0), 2, ',', '.') }}</th>
                    <th style="font-weight: bold;text-align: right;color: red;">{{ ($resultado['total_credito'] ?? 0) == 0 ? '-' : number_format(($resultado['total_credito'] ?? 0), 2, ',', '.') }}</th>
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                    
                    @php
                        $total_debito = $resultado['total_debito'] ?? 0;
                        $total_credito = $resultado['total_credito'] ?? 0;
                    @endphp
                    
                    <th></th>
                    <th style="text-align: right;">SALDO DO PERÍODO</th>
                    <th style="font-weight: bold;text-align: right;color: blue;">{{ ($total_debito - $total_credito) == 0 ? '-' : ($total_debito > $total_credito ? ( number_format($total_debito - $total_credito, 2, ',', '.')) : number_format($total_credito - $total_debito, 2, ',', '.') ) }}</th>
                    <th style="font-weight: bold;text-align: right;color: red;">-</th>
                </tr> number_format($total_debito - $total_credito, 2, ',', '.')
                {{-- <tr>
                    <th style="text-align: right;" colspan="3">SALDO GERAL</th>
                    <th  style="text-align: right;color: blue;"><strong>{{ $resultado['total_debito'] > $resultado['total_credito'] ? number_format($resultado['total_debito'] - $resultado['total_credito'], 2, ',', '.') : number_format(0, 2, ',', '.') }}</strong></th>
                    <th  style="text-align: right;color: red;"><strong>{{ $resultado['total_credito'] > $resultado['total_debito'] ? number_format($resultado['total_credito'] - $resultado['total_debito'], 2, ',', '.') : number_format(0, 2, ',', '.') }}</strong></th>
                    <th></th>
                </tr> --}}
                

            </tbody>
        </table>
        <script type='text/php'>
            if (isset($pdf)) 
            {               
                $pdf->page_text(60, $pdf->get_height() - 50, "{PAGE_NUM} de {PAGE_COUNT}", null, 12, array(0,0,0));
            }
        </script>
    </main>

    <div class="footer margin-top">
        <hr>
        <p style="text-align:right">Data: {{ date('Y-m-d H:i:s') }} </p>

        <div align="center"> Documento processado pelo software MUTUE - Contas Certas.</div>

    </div>

</body>

</html>
