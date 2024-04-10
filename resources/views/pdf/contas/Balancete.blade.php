<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Listagem do Balancete</title>

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
    </style>
</head>

<body>
    {{-- <header class="clearfix">
        <div id="logo" style="padding: 0">
            <img src="images/{{ $dados_empresa->logotipo_da_empresa ?? 'log.png' }}"
                style="width: 150px;height: 150px;position: absolute;top: -40px">
        </div>
        <div id="company">
            <h2 class="name">{{ $dados_empresa->nome_empresa }}</h2>
            <div>{{ $dados_empresa->endereco->rua }}, {{ $dados_empresa->endereco->bairro }}, <br>
            </div>
            <div>+244 947716133/+244 942364667</div>
            <div><a href="mailto:geral@uma.co.ao">geral@uma.co.ao</a></div>
        </div>
        </div>
    </header> --}}
    <main>
        <h2
            style="text-align: left;border-bottom: 1px solid #c2c2c2;padding-bottom: 2px;text-transform: uppercase;font-size: 11pt">
            Balancete <span style="float: right">DATA INICIO: {{ $requests['data_inicio'] ?? date('Y-m-d') }}, DATA
                FINAL {{ $requests['data_final'] ?? date('Y-m-d') }}</span></h2>
        <h2
            style="text-align: left;border-bottom: 1px solid #c2c2c2;padding-bottom: 2px;text-transform: uppercase;font-size: 11pt">
            Exercício: {{ $exercicio->designacao ?? '' }}</h2>
        <h2
            style="text-align: left;border-bottom: 1px solid #c2c2c2;padding-bottom: 2px;text-transform: uppercase;font-size: 11pt">
            Período: {{ $periodo->designacao ?? '' }}</h2>
        <div style="margin:auto 0px">

        </div>

        <table>
            <tr>
                <th rowspan="2">Conta</th>
                <th rowspan="2">Descrição</th>
                <th colspan="2">Dados do Período</th>
                <th>Mov. Débito</th>
                <th>Mov. Crédito</th>
                <th>Saldo. Devedor</th>
                <th>Saldo. Credor</th>
            </tr>

            <tr>
                <th>Débito</th>
                <th>Crébito</th>

                <th></th>
                <th>Soma Saldos</th>

                <th class="text-info">-</th>
                <th class="text-danger">-</th>
            </tr>

            <tr>
                <th></th>
                <th class="text-right">Soma Líquida</th>



                @foreach ($resultado as $resultadoConta)
                    <th class="text-info">
                        {{ $resultadoConta }}
                    </th>
                @endforeach

            </tr>
            @foreach ($registros as $item)
                <div>
                    <tr>
                        {{-- <th>{{ $item->conta->numero ?? '' }}</th>
                        <th>{{ $item->conta->designacao ?? '' }}</th> --}}
                        
                    </tr>
                </div>
            @endforeach

        </table>
        <script type='text/php'>
            if (isset($pdf)) 
            {               
                $pdf->page_text(60, $pdf->get_height() - 50, "{PAGE_NUM} de {PAGE_COUNT}", null, 12, array(0,0,0));
            }
        </script>
    </main>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br><br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <div class="footer margin-top">
        <hr>
        <p style="text-align:right">Data: {{ date('Y-m-d H:i:s') }} </p>

        <div align="center"> Documento processado pelo software MUTUE - Contas Certas.</div>

    </div>
</body>

</html>
