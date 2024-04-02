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
        <h2 style="text-align: left;border-bottom: 1px solid #c2c2c2;padding-bottom: 2px;text-transform: uppercase;font-size: 11pt">Balancete <span style="float: right">DATA INICIO: {{ $requests['data_inicio'] ?? date('Y-m-d')  }}, DATA FINAL {{ $requests['data_final'] ?? date('Y-m-d') }}</span></h2>
        <h2 style="text-align: left;border-bottom: 1px solid #c2c2c2;padding-bottom: 2px;text-transform: uppercase;font-size: 11pt">Exercício: {{ $exercicio->designacao ?? '' }}</h2>
        <h2 style="text-align: left;border-bottom: 1px solid #c2c2c2;padding-bottom: 2px;text-transform: uppercase;font-size: 11pt">Período: {{ $periodo->designacao ?? '' }}</h2>
        <div style="margin:auto 0px">
            
        </div>

        <table class="table table-bordered table-hover" id="tabela_de_balancetes">
            <thead>
                <tr>
                    <th style="text-align: center;color:white; background-color: gray;" rowspan="2">Conta</th>
                    <th style="text-align: center;color:white; background-color: gray;text-align: left" rowspan="2">Descrição</th>
                    <th style="text-align: center;color:white; background-color: gray;" colspan="2">Dados do Período</th>
                    <th style="text-align: center;color:white; background-color: gray;" colspan="2">Movimento</th>
                    <th style="text-align: center;color:white; background-color: gray;" colspan="2">Saldo</th>
                </tr>

                <tr>
                    <th style="text-align: center;color:white; background-color: gray;">Débito</th>
                    <th style="text-align: center;color:white; background-color: gray;">Crédito</th>
                    
                    <th style="text-align: center;color:white; background-color: gray;">Débito</th>
                    <th style="text-align: center;color:white; background-color: gray;">Crédito</th>
                    
                    <th style="text-align: center;color:white; background-color: gray;">Débito</th>
                    <th style="text-align: center;color:white; background-color: gray;">Crédito</th>
                </tr>

            </thead>

            <tbody>
                @foreach ($movimentos as $item)
                <tr>
                    <td>{{ $item->subconta->numero }}</td>
                    <td style="text-align: left">{{ $item->subconta->designacao }}</td>
                    <td style="color: blue">{{ $item->debito == 0 ? '-' : number_format($item->debito, 2, ',', '.') }}</td>
                    <td style="color: red">{{ $item->credito == 0 ? '-' : number_format($item->credito, 2, ',', '.') }}</td>
                    <td style="color: blue">{{ $item->debito == 0 ? '-' : number_format($item->debito, 2, ',', '.') }}</td>
                    <td style="color: red">{{ $item->credito == 0 ? '-' : number_format($item->credito, 2, ',', '.') }}</td>
                    <td style="color: blue">{{ $item->debito == 0 ? '-' : ($item->debito > $item->credito ? number_format($item->debito - $item->credito, 2, ',', '.') : '-') }}</td>
                    <td style="color: red">{{ $item->credito == 0 ? '-' : ($item->credito > $item->debito ? number_format($item->credito - $item->debito, 2, ',', '.') : '-') }}</td>
                    {{-- <td>{{ $item->criador->name }}</td> --}}
                </tr>
                @endforeach
                
                <tr>
                    <th colspan="2" style="text-align: right; background-color: rgb(212, 212, 212);color:gray;">Soma Líquida</th>
                    <th class="text-right" style="background-color: rgb(212, 212, 212); text-align: right;color: blue;">{{ number_format($resultado['total_debito'], 2, ',', '.') }}</th>
                    <th class="text-right" style="background-color: rgb(212, 212, 212); text-align: right;color: red;">{{ number_format($resultado['total_credito'], 2, ',', '.') }}</th>
                    <th class="text-right" style="background-color: rgb(212, 212, 212); text-align: right;color: blue;">{{ number_format($resultado['total_debito'], 2, ',', '.') }}</th>
                    <th class="text-right" style="background-color: rgb(212, 212, 212); text-align: right;color: red;">{{ number_format($resultado['total_credito'], 2, ',', '.') }}</th>
                    <th class="text-right" style="background-color: rgb(212, 212, 212); text-align: right;color: blue;">{{ number_format($resultado['total_movimento_debito'], 2, ',', '.') }}</th>
                    <th class="text-right" style="background-color: rgb(212, 212, 212); text-align: right;color: red;">{{ number_format($resultado['total_movimento_credito'], 2, ',', '.') }}</th>
                </tr>
                
                <tr>
                    <th colspan="2" style="text-align: right; background-color: rgb(212, 212, 212);color:gray;">Total por Classe</th>
                    <th class="text-right" style="background-color: rgb(212, 212, 212); text-align: right;color: blue;">{{ number_format($resultado['total_debito'] - 62562, 2, ',', '.') }}</th>
                    <th class="text-right" style="background-color: rgb(212, 212, 212); text-align: right;color: red;">{{ number_format($resultado['total_credito'] - 34534, 2, ',', '.') }}</th>
                    <th class="text-right" style="background-color: rgb(212, 212, 212); text-align: right;color: blue;">{{ number_format($resultado['total_debito'], 2, ',', '.') }}</th>
                    <th class="text-right" style="background-color: rgb(212, 212, 212); text-align: right;color: red;">{{ number_format($resultado['total_credito'], 2, ',', '.') }}</th>
                    <th class="text-right" style="background-color: rgb(212, 212, 212); text-align: right;color: blue;">{{ number_format($resultado['total_movimento_debito'], 2, ',', '.') }}</th>
                    <th class="text-right" style="background-color: rgb(212, 212, 212); text-align: right;color: red;">{{ number_format($resultado['total_movimento_credito'], 2, ',', '.') }}</th>
                </tr>
                
                <tr>
                    <th colspan="2" style="text-align: right; background-color: rgb(212, 212, 212);color:gray;">Total por Conta</th>
                    <th class="text-right" style="background-color: rgb(212, 212, 212); text-align: right;color: blue;">{{ number_format($resultado['total_debito'] - 82536, 2, ',', '.') }}</th>
                    <th class="text-right" style="background-color: rgb(212, 212, 212); text-align: right;color: red;">{{ number_format($resultado['total_credito'] - 54243, 2, ',', '.') }}</th>
                    <th class="text-right" style="background-color: rgb(212, 212, 212); text-align: right;color: blue;">{{ number_format($resultado['total_debito'], 2, ',', '.') }}</th>
                    <th class="text-right" style="background-color: rgb(212, 212, 212); text-align: right;color: red;">{{ number_format($resultado['total_credito'], 2, ',', '.') }}</th>
                    <th class="text-right" style="background-color: rgb(212, 212, 212); text-align: right;color: blue;">{{ number_format($resultado['total_movimento_debito'], 2, ',', '.') }}</th>
                    <th class="text-right" style="background-color: rgb(212, 212, 212); text-align: right;color: red;">{{ number_format($resultado['total_movimento_credito'], 2, ',', '.') }}</th>
                </tr>
                
                <tr>
                    <th colspan="2" style="text-align: right; background-color: gray;color:white;"></th>
                    <th style="text-align: center;color:white; background-color: gray;"></th>
                    <th style="text-align: center;color:white; background-color: gray;"></th>
                    <th colspan="2" style="text-align: right; background-color: gray;color:white;">TOTAL</th>
                    <th style="text-align: right">{{ number_format(0, 2, ',', '.') }}</th>
                    <th style="text-align: right">{{ number_format(0, 2, ',', '.') }}</th>
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
