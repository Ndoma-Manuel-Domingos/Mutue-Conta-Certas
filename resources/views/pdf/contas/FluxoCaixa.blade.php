<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Lista de Fluxo de caixa</title>

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

        tr th {
            background-color: gray;
            color: white;
            text-align: center;

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
        <h2 style="text-align: left;border-bottom: 1px solid #c2c2c2;padding-bottom: 2px;text-transform: uppercase;font-size: 11pt;font-weight: 100"> FLUXO DE CAIXA</h2>
        <h2 style="text-align: left;border-bottom: 1px solid #c2c2c2;padding-bottom: 2px;text-transform: uppercase;font-size: 11pt;font-weight: 100"> DATA: {{ $requests['data_inicio'] ?? date('Y-m-d')  }} a {{ $requests['data_final'] ?? date('Y-m-d') }}</h2>
        <h2 style="text-align: left;border-bottom: 1px solid #c2c2c2;padding-bottom: 2px;text-transform: uppercase;font-size: 11pt;font-weight: 100"> EXERCÍCIO: {{ $exercicio->designacao ?? 'TODOS' }}</h2>
        <h2 style="text-align: left;border-bottom: 1px solid #c2c2c2;padding-bottom: 2px;text-transform: uppercase;font-size: 11pt;font-weight: 100"> Período: {{ $periodo->designacao ?? 'TODOS' }}</h2>

        <div style="margin:auto 0px"> </div>
        <table id="table_id" class="display" style="background-color:white; border-radius:4px;">
            <thead>
                <tr class="">
                    <th>Nº</th>
                    <th>Nº Mov</th>
                    <th style="text-align: left;">Diário</th>
                    <th style="text-align: left;">Documento</th>
                    <th style="text-align: right;">Data</th>
                    <th style="text-align: right;">Execício</th>
                    <th style="text-align: right;">Débito</th>
                    <th style="text-align: right;">Crédito</th>
                    <th style="text-align: right;">Saldo</th>
                </tr>
                <tr class="">
                    <th colspan="6" style="text-align: right;">TOTAL</th>
                    <th style="text-align: right;color: blue">{{ number_format($resultado->debito, 2, ',', '.') }}</th>
                    <th style="text-align: right;color: red">{{ number_format($resultado->credito, 2, ',', '.') }}</th>
                    @if ($resultado->debito > $resultado->credito)
                       <th style="text-align: right;color: blue">{{ number_format($resultado->debito - $resultado->credito, 2, ',', '.') }}</th>    
                    @endif
                    @if ($resultado->credito > $resultado->debito)
                        <th style="text-align: right;color: red">{{ number_format($resultado->credito - $resultado->debito, 2, ',', '.') }}</th>  
                    @endif
                </tr>
            </thead>

            <tbody>
                @foreach($movimentos as $key => $item)
                <tr>
                    <td style="text-align: center;">{{$key + 1 }}</td>
                    <td style="text-align: center;">{{$item->id}}</td>
                    <td style="text-align: left;">{{$item->diario->numero}} - {{$item->diario->designacao}}</td>
                    <td style="text-align: left;">{{$item->tipo_documento->numero}} - {{$item->tipo_documento->designacao}}</td>
                    <td style="text-align: right;">{{$item->data_lancamento}}</td>
                    <td style="text-align: right;">{{$item->exercicio->designacao}}</td>
                    <td style="text-align: right;color: blue">{{ number_format($item->debito, 2, ',', '.') }}</td>
                    <td style="text-align: right;color: red">{{ number_format($item->credito, 2, ',', '.') }}</td>
                    @if ($item->debito > $item->credito)
                    <td style="text-align: right;color: blue">{{ number_format($item->debito - $item->credito, 2, ',', '.') }}</td>
                    @endif
                    @if ($item->credito > $item->debito)
                    <td style="text-align: right;color: red">{{ number_format($item->credito - $item->debito , 2, ',', '.') }}</td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>

    </main>

    <div class="footer margin-top">
        <hr>
        <p style="text-align:right">Data: {{ date('Y-m-d H:i:s') }} </p>

        <div align="center"> Documento processado pelo software MUTUE - Contas Certas.</div>

    </div>

</body>
</html>
