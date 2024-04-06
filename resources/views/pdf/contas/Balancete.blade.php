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

        <table class="table table-bordered table-hover">
            <thead>
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
             
                <tr>
                    <th></th>
                    <th class="text-right" style="text-align: right">Total</th>

                    <th class="text-info" style="background-color: rgb(212, 212, 212); text-align: right;color: blue;">{{ $resultado_por_conta['total_debito'] == 0 ? '-' : number_format($resultado_por_conta['total_debito']) }}</th>
                    <th class="text-danger" style="background-color: rgb(212, 212, 212); text-align: right;color: red;">{{ $resultado_por_conta['total_credito'] == 0 ? '-' : number_format($resultado_por_conta['total_credito']) }}</th>

                    <th class="text-info" style="background-color: rgb(212, 212, 212); text-align: right;color: blue;">{{ $resultado['total_movimento_debito'] == 0 ? '-' : number_format($resultado['total_movimento_debito']) }}</th>
                    <th class="text-danger" style="background-color: rgb(212, 212, 212); text-align: right;color: red;">{{ $resultado['total_movimento_credito'] == 0 ? '-' : number_format($resultado['total_movimento_credito']) }}</th>
                    <th class="text-info" style="background-color: rgb(212, 212, 212); text-align: right;color: blue;">{{ $resultado['total_debito'] == 0 ? '-' : number_format($resultado['total_debito']) }}</th>
                    <th class="text-danger" style="background-color: rgb(212, 212, 212); text-align: right;color: red;">{{ $resultado['total_credito'] == 0 ? '-' : number_format($resultado['total_credito']) }}</th>
                </tr>

                @foreach ($registros as $item)
                
                <tr>
                    <th class="text-right" colspan="2" style="text-align: right; background-color: rgb(212, 212, 212);color:gray;">Total por Classe</th>
                    
                    @php
                        $movimento = optional($item->conta->items_movimentos[0]);
                        $total_debito = $movimento ? ($movimento->TotalDebito == 0 ? '-' : number_format($movimento->TotalDebito)) : '-';
                        $total_credito = $movimento ? ($movimento->TotalCredito == 0 ? '-' : number_format($movimento->TotalCredito)) : '-';
                    @endphp
                    
                    <td class="text-info" style="background-color: rgb(212, 212, 212); text-align: right;color: blue;">{{ $total_debito }}</td>
                    <td class="text-danger" style="background-color: rgb(212, 212, 212); text-align: right;color: red;">{{ $total_credito }}</td>
                    
                    <td class="text-info" style="background-color: rgb(212, 212, 212); text-align: right;color: blue;">-</td>
                    <td class="text-danger" style="background-color: rgb(212, 212, 212); text-align: right;color: red;">-</td>
                    <td class="text-info" style="background-color: rgb(212, 212, 212); text-align: right;color: blue;">-</td>
                    <td class="text-danger" style="background-color: rgb(212, 212, 212); text-align: right;color: red;">-</td>
                </tr>

                <tr>
                   
                    <th class="text-right" colspan="2" style="text-align: right; background-color: rgb(212, 212, 212);color:gray;">Total por Conta</th>
                    
                    @php
                        $movimento = $item->conta->items_movimentos[0] ?? null;
                        $total_debito = $movimento ? ($movimento->TotalDebito == 0 ? '-' : number_format($movimento->TotalDebito)) : '-';
                        $total_credito = $movimento ? ($movimento->TotalCredito == 0 ? '-' : number_format($movimento->TotalCredito)) : '-';
                    @endphp
                    
                    <td class="text-info" style="background-color: rgb(212, 212, 212); text-align: right;color: blue;">{{ $total_debito }}</td>
                    <td class="text-danger" style="background-color: rgb(212, 212, 212); text-align: right;color: red;">{{ $total_credito }}</td>
                    <td class="text-info" style="background-color: rgb(212, 212, 212); text-align: right;color: blue;">-</td>
                    <td class="text-danger" style="background-color: rgb(212, 212, 212); text-align: right;color: red;">-</td>
                    <td class="text-info" style="background-color: rgb(212, 212, 212); text-align: right;color: blue;">-</td>
                    <td class="text-danger" style="background-color: rgb(212, 212, 212); text-align: right;color: red;">-</td>
                </tr>
                
                <tr>
                    <th style="text-align: left;color:white; background-color: gray;" colspan="9">CLASSE - {{ $item->classe->numero ?? '' }} - {{ $item->classe->designacao ?? '' }}</th>
                </tr>
                
                <tr>
                    <th style="text-align: center;color:white; background-color: gray;">{{ $item->conta->numero ?? '' }}</th>
                    <th style="text-align: left;color:white; background-color: gray;" colspan="8">{{ $item->conta->designacao ?? '' }}</th>
                </tr>

                @foreach ($item->sub_contas_empresa as $item2)
                <tr>
                    <td>{{ $item2->numero ?? '' }}</td>
                    <td style="text-align: left">{{ $item2->designacao ?? '' }}</td>
                    
                    @php
                        $movimento = $item2->items_movimentos[0] ?? null;
                        $total_debito = $movimento ? ($movimento->total_debito == 0 ? '-' : number_format($movimento->total_debito)) : '-';
                        $total_credito = $movimento ? ($movimento->total_credito == 0 ? '-' : number_format($movimento->total_credito)) : '-';
                    @endphp
                    
                    <td class="text-info" style="background-color: rgb(212, 212, 212); text-align: right;color: blue;">{{ $total_debito }}</td>
                    <td class="text-danger" style="background-color: rgb(212, 212, 212); text-align: right;color: red;">{{ $total_credito }}</td>
                    
                    <td class="text-info" style="background-color: rgb(212, 212, 212); text-align: right;color: blue;">{{ $total_debito }}</td>
                    <td class="text-danger" style="background-color: rgb(212, 212, 212); text-align: right;color: red;">{{ $total_credito }}</td>
                    
                    <td class="text-info" style="background-color: rgb(212, 212, 212); text-align: right;color: blue;">{{ $total_debito }}</td>
                    <td class="text-danger" style="background-color: rgb(212, 212, 212); text-align: right;color: red;">{{ $total_credito }}</td>
                    
                </tr>
                @endforeach

                @endforeach
            </thead>
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
