<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>FICHA DE IMOBILIZADO</title>

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
    <header class="clearfix">
        <div id="logo" style="padding: 0">
            <img src="images/{{ $dados_empresa->logotipo_da_empresa ?? 'log.png' }}" style="width: 150px;height: 150px;position: absolute;top: -40px">
        </div>
        <div id="company">
            <h2 class="name">{{ $dados_empresa->nome_empresa }}</h2>
            <div>{{ $dados_empresa->endereco->rua }}, {{ $dados_empresa->endereco->bairro }}, <br>
            </div>
            <div>+244 947716133/+244 942364667</div>
            <div><a href="mailto:geral@uma.co.ao">geral@uma.co.ao</a></div>
        </div>
        </div>
    </header>

    <main>
        <h2 style="text-align: center;border-bottom: 1px solid #c2c2c2;padding-bottom: 2px;text-transform: uppercase;font-size: 11pt">FICHA DO IMOBILIZADO</h2>
        <div style="margin:auto 0px"> </div>

        <table>
            <tbody>
                <tr>
                    <th style="text-align: left;width: 250px">Exercício:</th>
                    <th style="text-align: left;width: 250px">Período:</th>
                    <th style="text-align: left;width: 250px">Estado:</th>
                </tr>
                <tr>
                    <td style="text-align: left;">{{ $imobilizado->exercicio->designacao }}</td>
                    <td style="text-align: left;">{{ $imobilizado->periodo->designacao }}</td>
                    <td style="text-align: left;">{{ $imobilizado->status }}</td>
                </tr>
                
                <tr>
                    <th style="text-align: left;width: 250px">Código:</th>
                    <th style="text-align: left;width: 250px">Descrição:</th>
                    <th style="text-align: left;width: 250px">Classificação:</th>
                </tr>
                <tr>
                    <td style="text-align: left;">{{ $imobilizado->sigla }}</td>
                    <td style="text-align: left;">{{ $imobilizado->designacao }}</td>
                    <td style="text-align: left;">{{ $imobilizado->classificacao->designacao }}</td>
                </tr>
            </tbody>
        </table>

        <table>
            <tbody>
                <tr>
                    <th style="text-align: left;width: 250px">Data de Aquisição:</th>
                    <th style="text-align: left;width: 250px">Data de Utilização:</th>
                    <th style="text-align: left;width: 250px">Valor de Aquisição:</th>
                </tr>
                <tr>
                    <td style="text-align: left;">{{ $imobilizado->data_aquisicao }}</td>
                    <td style="text-align: left;">{{ $imobilizado->data_utilizacao }}</td>
                    <td style="text-align: left;">{{ number_format($imobilizado->valor_aquisicao, 2, '.', ',')  }}</td>
                </tr>
                <tr>
                    <th style="text-align: left;">Quantidade:</th>
                    <th style="text-align: left;">Categoria:</th>
                    <th style="text-align: left;">Amortizações:</th>
                </tr>
                <tr>
                    <td style="text-align: left;">{{ $imobilizado->quantidade }}</td>
                    <td style="text-align: left;">{{ $imobilizado->amortizacao->designacao }}</td>
                    <td style="text-align: left;">{{ $imobilizado->amortizacao_item->designacao }}</td>
                </tr>
                <tr>
                    <th style="text-align: left;">Vida Útil Anos:</th>
                    <th style="text-align: left;">Taxa de Amortização anual:</th>
                    <th style="text-align: left;"></th>
                </tr>
                <tr>
                    <td style="text-align: left;">{{ $imobilizado->amortizacao_item->vida_util }}</td>
                    <td style="text-align: left;">{{ $imobilizado->amortizacao_item->taxa }}</td>
                    <td style="text-align: left;"></td>
                </tr>
            </tbody>
        </table>
        
        <table>
            <tbody>
                <tr>
                    <th style="text-align: left;width: 250px">Valor Actual:</th>
                    <th style="text-align: left;width: 250px">Valor Anterior:</th>
                    <th style="text-align: left;width: 250px"></th>
                </tr>
                <tr>
                    <td style="text-align: left;">{{ number_format($imobilizado->valor_aquisicao, 2, '.', ',')  }}</td>
                    <td style="text-align: left;">{{ number_format($imobilizado->valor_aquisicao, 2, '.', ',')  }}</td>
                    <td style="text-align: left;"></td>
                </tr>

            </tbody>
        </table>

        <script type='text/php'>
            if (isset($pdf)) 
            {               
                $pdf->page_text(60, $pdf->get_height() - 50, "{PAGE_NUM} de {PAGE_COUNT}", null, 12, array(0,0,0));
            }
        </script>
    </main>


    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

    <div class="footer margin-top">
        <hr>
        <p style="text-align:right">Data: {{ date('Y-m-d H:i:s') }} </p>

        <div align="center"> Documento processado pelo software MUTUE - Contas Certas.</div>

    </div>
</body>

</html>
