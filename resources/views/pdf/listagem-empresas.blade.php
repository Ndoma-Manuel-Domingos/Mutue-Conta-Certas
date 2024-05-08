<?php
ini_set('memory_limit', '1200M');
ini_set('max_execution_time', 1200);
?>

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
        <h2 style="text-align: left;border-bottom: 1px solid #c2c2c2;padding-bottom: 2px;text-transform: uppercase;font-size: 11pt;font-weight: 100"> LISTAGEM DE EMPRESAS</h2>

        <div style="margin:auto 0px"> </div>
        <table class="table table-stripeds" style="">

            <head>
                <tr style="background-color: #152ea2;color: #e3e7e8">
                    <th style="text-align: left;">NIF</th>
                    <th style="text-align: left;">Nome</th>
                    <th style="text-align: left;">Regime do IVA</th>
                    <th style="text-align: left;">Moeda Base</th>
                    <th style="text-align: left;">Moeda Alternativo</th>
                    <th style="text-align: left;">Tipo</th>
                    <th style="text-align: left;">Grupo</th>
                    <th style="text-align: left;">Estado</th>
                </tr>
                </thead>

                <tbody>
                    @foreach ($empresas as $key => $item)
                    <tr>
                        <td style="text-align: left;" class="text-uppercase">{{ $item->codigo_empresa ?? '' }}</td>
                        <td style="text-align: left;" class="text-uppercase">{{ $item->nome_empresa ?? '-' }}</td>
                        <td style="text-align: left;">{{ $item->regime ? $item->regime->designacao : '-' }}</td>
                        <td style="text-align: left;">{{ $item->moeda ? ($item->moeda->base ? ($item->moeda->base ? $item->moeda->base->designacao : "") : "") : "" }} - {{ $item->moeda ? ($item->moeda->base ? ($item->moeda->base ? $item->moeda->base->sigla : "") : "") : "" }}</td>
                        <td style="text-align: left;">{{ $item->moeda ? ($item->moeda->base ? ($item->moeda->alternativa ? $item->moeda->alternativa->designacao : "") : "") : "" }} - {{ $item->moeda ? ($item->moeda->alternativa ? ($item->moeda->alternativa ? $item->moeda->alternativa->sigla : "") : "") : "" }}</td>
                        <td style="text-align: left;">{{ $item->tipo ? $item->tipo->designacao : "" }}</td>
                        <td style="text-align: left;">{{ $item->grupo ? $item->grupo->designacao : "" }}</td>
                        <td style="text-align: left;" class="text-capitalize">{{ $item->estado_empresa_id == 1 ? 'Activo': 'Desactivo' }}</td>
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
