<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>MAPA DE REINTEGRAÇÕES E AMORTIZAÇÕES ELEMENTOS DO ACTIVO NÃO REAVLIADOS</title>

    <link rel="stylesheet" href="css/style.css" media="all" />
    <style>
        *{
            margin: 0;
            padding: 0;
        }
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
        <h2 style="text-align: center;border-bottom: 1px solid #c2c2c2;padding-bottom: 2px;text-transform: uppercase;font-size: 11pt">MAPA DE REINTEGRAÇÕES e AMORTIZAÇÕES ELEMENTOS DO ACTIVO NÃO REAVLIADOS</h2>
        <div style="margin:auto 0px"></div>

        
        <table class="table table-bordered table-hover" id="tabela_de_diarias">
            <thead>
              <tr>
                <th style="text-align: left;color:white; background-color: gray;font-size: 7pt;" rowspan="3">Nº</th>
                <th style="text-align: left;color:white; background-color: gray;font-size: 7pt;" rowspan="3">Conta</th>
                <th style="text-align: left;color:white; background-color: gray;font-size: 7pt;" rowspan="3">Descrição</th>
                
                <th style="text-align: center;color:white; background-color: gray;font-size: 7pt;" colspan="2">Data</th>
                
                <th style="text-align: center;color:white; background-color: gray;font-size: 7pt;" rowspan="3">Isenção <br> no Código <br>Aduaneiro <br> (Activo Importado) <br> (Sim ou Não)</th>
                <th style="text-align: left;color:white; background-color: gray;font-size: 7pt;" rowspan="3">Origem</th>
                <th style="text-align: center;color:white; background-color: gray;font-size: 7pt;" rowspan="3">Número de <br> anos de <br> utilidade <br> esperada</th>
                <th style="text-align: center;color:white; background-color: gray;font-size: 7pt;" rowspan="3">Ano da <br> reavaliação</th>
                <th style="text-align: left;color:white; background-color: gray;font-size: 7pt;" rowspan="3">Valor de <br> Aquisição</th>
                <th style="text-align: left;color:white; background-color: gray;font-size: 7pt;" rowspan="3">Quantidade</th>
                
                <th style="text-align: center;color:white; background-color: gray;font-size: 7pt;" colspan="6">Reintegrações <br> e amortizações</th>
                
              </tr>
              
              <tr>
                                    
                <th rowspan="2" style="color:white; background-color: gray;font-size: 7pt;">Aquisição</th>
                <th rowspan="2" style="color:white; background-color: gray;font-size: 7pt;">Início de <br> utilização</th>
                                 
                <th rowspan="2" style="color:white; background-color: gray;font-size: 7pt;">Valores de <br> exercícios<br>  anteriores</th>
                <th style="text-align: center;color:white; background-color: gray;font-size: 7pt;" colspan="3">Do exercício</th>
                <th rowspan="2" style="color:white; background-color: gray;font-size: 7pt;">Amortizações <br> acumuladas</th>
                <th rowspan="2" style="color:white; background-color: gray;font-size: 7pt;">Valor <br> contabilístico</th>
                
              </tr>
                                
              <tr>
                      
                <th style="color:white; background-color: gray;font-size: 7pt;">Taxa%</th>
                <th style="color:white; background-color: gray;font-size: 7pt;">Taxa corrigida %</th>
                <th style="color:white; background-color: gray;font-size: 7pt;">Valores</th>
                
              </tr>
          
            </thead>

            <tbody>
              @foreach ($imobilizados as $key => $item)
              <tr>
                  <td style="text-align: left;font-size: 7pt;">{{ $key + 1 }}</td>
                  <td style="text-align: left;font-size: 7pt;">{{ $item->conta ?? "" }}</td>
                  <td style="text-align: left;font-size: 7pt;">{{ $item->designacao ?? "" }}</td>
                  <td style="text-align: left;font-size: 7pt;">{{ $item->data_aquisicao ?? "" }}</td>
                  <td style="text-align: left;font-size: 7pt;">{{ $item->data_utilizacao ?? "" }}</td>
                  <td style="text-align: center;font-size: 7pt;">{{ $item->sigla ?? "" }}</td>
                  <td style="text-align: left;font-size: 7pt;">{{ $item->origem_id ?? "" }}</td>
                  <td style="text-align: center;font-size: 7pt;">{{ $item->amortizacao_item->vida_util }}</td>
                  <td style="text-align: center;font-size: 7pt;">-----</td>
                  <td style="text-align: right;font-size: 7pt;">{{ number_format($item->valor_aquisicao, 2, ',', '.') }}</td>
                  <td style="text-align: center;font-size: 7pt;">{{ number_format($item->quantidade, 2, ',', '.') }}</td>
                  <td style="text-align: left;font-size: 7pt;">{{ number_format($item->valor_aquisicao * ($item->amortizacao_item->taxa / 100), 2, ',', '.') }}</td>
                  <td style="text-align: left;font-size: 7pt;">{{ $item->amortizacao_item->taxa }}</td>
                  <td style="text-align: left;font-size: 7pt;">--------</td>
                  <td style="text-align: right;font-size: 7pt;">{{ number_format($item->valor_aquisicao * ($item->amortizacao_item->taxa / 100), 2, ',', '.') }}</td>
                  <td style="text-align: left;font-size: 7pt;">{{ number_format(($item->valor_aquisicao * ($item->amortizacao_item->taxa / 100)) * $item->ano_vencimento, 2, ',', '.') }}</td>
                  <td style="text-align: left;font-size: 7pt;">{{ number_format($item->valor_aquisicao - ($item->valor_aquisicao * (($item->amortizacao_item->taxa / 100) * $item->ano_vencimento)), 2, ',', '.') }}</td>
              </tr>
              @endforeach
            </tbody>
            
            <tfoot>
              <tr>
                  <td style="text-align: center;font-size: 7pt;" colspan="9">Total Geral</td>
                  
                  <td style="text-align: right;font-size: 7pt;">{{ number_format($resultado->valor_aquisicao, 2, ',', '.') }}</td>
                  <td style="text-align: left;font-size: 7pt;">0</td>
                  <td>-----</td>
                  <td>-----</td>
                  <td>-------</td>
                  <td style="text-align: right;font-size: 7pt;">{{ number_format($resultado->valores, 2, ',', '.') }}</td>
                  <td style="text-align: right;font-size: 7pt;">{{ number_format($resultado->valor_acumulado, 2, ',', '.') }}</td>
                  <td style="text-align: right;font-size: 7pt;">{{ number_format($resultado->valor_contabilistico, 2, ',', '.') }}</td>
              </tr>
            </tfoot>
          </table>
        

        {{-- <table class="table table-bordered table-hover" id="tabela_de_diarias">
            <thead>
                <tr>
                    <th style="text-align: left;color:white; background-color: gray;" rowspan="3">Descrição</th>
                    <th style="text-align: center;color:white; background-color: gray;" rowspan="3">Ano</th>
                    <th style="text-align: center;color:white; background-color: gray;" rowspan="3">Qtd</th>
                    <th style="text-align: center;color:white; background-color: gray;">Valores de Aquisição</th>
                    <th style="text-align: center;color:white; background-color: gray;">Anos em Esperas</th>
                    <th style="text-align: center;color:white; background-color: gray;" colspan="4">Reintegrações e amortizações</th>
                    <th style="text-align: center;color:white; background-color: gray;" rowspan="3">Activo Imobilizado <br> (Valores líquidos)</th>
                </tr>

                <tr>
                    <th style="text-align: center;color:white; background-color: gray;" rowspan="2">(c)</th>
                    <th style="text-align: center;color:white; background-color: gray;" rowspan="2">(d)</th>

                    <th style="text-align: center;color:white; background-color: gray;" rowspan="2">De exercícios <br> anteriores</th>
                    <th style="text-align: center;color:white; background-color: gray;" colspan="2">Do exercícios</th>
                    <th style="text-align: center;color:white; background-color: gray;" rowspan="2">Acumuladas</th>
                </tr>

                <tr>
                    <th style="text-align: center;color:white; background-color: gray;">Taxa</th>
                    <th style="text-align: center;color:white; background-color: gray;">Valores</th>
                </tr>

            </thead>

            <tbody>

                <tr>
                    <td style="text-align: left">1153-Mobiliario</td>
                    <td style="text-align: center"></td>
                    <td style="text-align: center"></td>
                    <td style="text-align: right">322 000,00</td>
                    <td style="text-align: center"></td>
                    <td style="text-align: right"></td>
                    <td></td>
                    <td style="text-align: right">33 541,67</td>
                    <td style="text-align: right">33 541,67</td>
                    <td style="text-align: right">288 458,33</td>
                </tr>

                <tr>
                    <td style="text-align: left">Secretaria de outros materirais Branca</td>
                    <td style="text-align: center">06-03-2020</td>
                    <td style="text-align: center">2</td>
                    <td style="text-align: right">156 000,00</td>
                    <td style="text-align: center">8</td>
                    <td style="text-align: right">-</td>
                    <td>12,50%</td>
                    <td style="text-align: right">16 250,00</td>
                    <td style="text-align: right">16 250,00</td>
                    <td style="text-align: right">139 750,00</td>
                </tr>

                <tr>
                    <td style="text-align: left">Cadeiras de outros materiais azul</td>
                    <td style="text-align: center">06-03-2020</td>
                    <td style="text-align: center">4</td>
                    <td style="text-align: right">166 000,00</td>
                    <td style="text-align: center">8</td>
                    <td style="text-align: right">-</td>
                    <td>12,50%</td>
                    <td style="text-align: right">17 291,00</td>
                    <td style="text-align: right">17 291,00</td>
                    <td style="text-align: right">148 708,00</td>
                </tr>

            </tbody>

            <tfoot>
                <tr>
                    <td style="text-align: center;font-size: 10px;color:white; background-color: gray;" colspan="3">Total Geral</td>

                    <td style="text-align: right;font-size: 10px;color:white; background-color: gray;">462 000,00</td>
                    <td style="text-align: center;font-size: 10px;color:white; background-color: gray;"></td>
                    <td style="text-align: center;font-size: 10px;color:white; background-color: gray;"></td>
                    <td style="text-align: center;font-size: 10px;color:white; background-color: gray;"></td>
                    <td style="text-align: right;font-size: 10px;color:white; background-color: gray;">462 000,00</td>
                    <td style="text-align: right;font-size: 10px;color:white; background-color: gray;">462 000,00</td>
                    <td style="text-align: right;font-size: 10px;color:white; background-color: gray;">462 000,00</td>
                </tr>
            </tfoot>
        </table> --}}


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
