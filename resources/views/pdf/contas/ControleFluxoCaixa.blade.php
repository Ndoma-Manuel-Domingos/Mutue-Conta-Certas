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
        <h2 style="text-align: left;border-bottom: 1px solid #c2c2c2;padding-bottom: 2px;text-transform: uppercase;font-size: 11pt;font-weight: 100"> Fluxo de caixa das actividades operacionais </h2>
        <h2 style="text-align: left;border-bottom: 1px solid #c2c2c2;padding-bottom: 2px;text-transform: uppercase;font-size: 11pt;font-weight: 100"> DATA: {{ $requests['data_inicio'] ?? date('Y-m-d')  }} a {{ $requests['data_final'] ?? date('Y-m-d') }}</h2>
        <h2 style="text-align: left;border-bottom: 1px solid #c2c2c2;padding-bottom: 2px;text-transform: uppercase;font-size: 11pt;font-weight: 100"> EXERCÍCIO: {{ $exercicio->designacao ?? 'TODOS' }}</h2>
        <h2 style="text-align: left;border-bottom: 1px solid #c2c2c2;padding-bottom: 2px;text-transform: uppercase;font-size: 11pt;font-weight: 100"> Período: {{ $periodo->designacao ?? 'TODOS' }}</h2>

        <div style="margin:auto 0px"> </div>
        <table id="table_id" class="display" style="background-color:white; border-radius:4px;">
            <thead>
                <tr class="">
                    <th style="text-align: left;">Descrição</th>
                    <th style="text-align: right;">Valores</th>
                </tr>
            </thead>
            
            
            <tbody>
                <tr>
                    <td style="text-align: left;">Dinheiro recebido de Clientes</td>
                    <td style="text-align: right;">{{ number_format($dinheiro_recebido_clientes, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="text-align: left;">Dinheiro pagos em mercadorias</td>
                    <td style="text-align: right;">{{ number_format($dinheiro_recebido_fornecedores, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="text-align: left;">Dinheiro pagos em salários e custos operacionais</td>
                    <td style="text-align: right;">{{ number_format($dinheiro_custo, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="text-align: left;">Dinheiro pagos em juros</td>
                    <td style="text-align: right;">{{ number_format($dinheiro_pagos_juros, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="text-align: left;">Dinheiro pagos em impostos</td>
                    <td style="text-align: right;">{{ number_format($dinheiro_imposto, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="text-align: left;">Outros</td>
                    <td style="text-align: right;">{{ number_format($outros, 2, ',', '.') }}</td>
                </tr>
              </tbody>
              
              <tfoot>
                <tr>
                    <th style="text-align: left;">Caixa liquida gerada nas actividades operacionais</th>
                    <th style="text-align: right;">{{ number_format(($dinheiro_recebido_clientes + $dinheiro_recebido_fornecedores + $dinheiro_custo + $dinheiro_pagos_juros + $dinheiro_imposto + $outros), 2, ',', '.') }}</th>
                </tr>
              </tfoot>
           
        </table>

    </main>

    <div class="footer margin-top">
        <hr>
        <p style="text-align:right">Data: {{ date('Y-m-d H:i:s') }} </p>

        <div align="center"> Documento processado pelo software MUTUE - Contas Certas.</div>

    </div>

</body>
</html>
