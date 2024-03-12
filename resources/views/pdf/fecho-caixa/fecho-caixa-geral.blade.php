
<?php
ini_set('memory_limit', '1200M');
ini_set('max_execution_time', 1200);
?>
<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/><!DOCTYPE html>
        <title>FECHO DE CAIXA GERAL</title>
    
        <style type="text/css">
            *{
                margin: 0;
                padding: 0;
                -webkit-box-sizing: border-box;
                box-sizing: border-box;
                font-family: Arial, Helvetica, sans-serif;
                text-align: left;
            }
            body{
                padding: 20px;
                font-family: Arial, Helvetica, sans-serif;
            }

            h1{
                font-size: 15pt;
                margin-bottom: 10px;
            }
            h2{
                font-size: 12pt;
            }
            p{
                /* margin-bottom: 20px; */
                line-height: 25px;
                font-size: 12pt;
                text-align: justify;
            }
            strong{
                font-size: 12pt;
            }

            table{
                width: 100%;
                text-align: left;
                border-spacing: 2;	
                margin-bottom: 10px;
                /* border: 1px solid rgb(0, 0, 0); */
                font-size: 12pt;
            }
            thead{
                background-color: #fdfdfd;
                font-size: 10px;
            }
            td{
                border-bottom: 1px solid rgb(255, 255, 255);
            }
            th, td{
                padding: 6px;
                font-size: 13px;
                margin: 0;
                padding: 0;
            }
            strong{
                font-size: 13px;
            }
        </style>
    </head>
<body>

<div style="text-align: center;width: 100%;padding: 20px 0;">
    <table style="border-bottom: 1px solid #000;padding-bottom: 10px">
        <tr>
            <td rowspan="5" style="width: 100px">
                <img src="{{ public_path('images/logotipo.png') }}" style="width: 200px;height: 120px;" />
            </td>
            <td style="text-align: right;font-size: 16px">Universidade Metodista</td>
        </tr>
        <tr>
            <td style="text-align: right;font-size: 16px">Rua Nossa Senhora da Muxima Nº 10,</td>
        </tr>
        <tr>
            <td style="text-align: right;font-size: 16px">Bairro Kinaxixi, Luanda</td>
        </tr>
        <tr>
            <td style="text-align: right;font-size: 16px">+244 947716133/+244 942364667</td>
        </tr>
        <tr>
            <td style="text-align: right;font-size: 16px">geral@uma.co.ao</td>
        </tr>
    </table>
</div>

<main>

    <table style="background-color: rgb(234, 234, 234);margin-top: -20px">
        <tr>
            <td colspan="2" style="text-align: center;padding: 0px">{{ $ttulo ?? "" }}</td>
        </tr>
        <tr>
            <td style="text-align: left;padding: 0px">Total de Registro:</td>
            <td style="text-align: left;padding-left: 5px;border-left: 2px solid #ffffff">{{ $items->count() }}</td>
        </tr>
    </table>

    <table class="table table-stripeds" style="">
        <thead>
            <tr style="background-color: #3F51B5;color: #ffffff">
                <th colspan="3" style="text-align: left;padding: 4px 2px">Operador:</th>
                <th colspan="3"  style="text-align: left;padding: 4px 2px">{{ $operador ? $operador->nome : "TODAS" }}</th>
            </tr>
            
            <tr style="background-color: #3F51B5;color: #ffffff">
                <th colspan="3" style="text-align: left;padding: 4px 2px">Data Inicio:</th>
                <th colspan="3"  style="text-align: left;padding: 4px 2px">{{ $requests['data_inicio'] ?? date("Y-m-d") }}</th>
            </tr>
            
            <tr style="background-color: #3F51B5;color: #ffffff">
                <th colspan="3"  style="text-align: left;padding: 4px 2px">Data Final:</th>
                <th colspan="3"  style="text-align: left;padding: 4px 2px">{{ $requests['data_final'] ?? date("Y-m-d") }}</th>
            </tr>
            
            <tr style="background-color: #3F51B5;color: #ffffff">
                <th colspan="3"  style="text-align: left;padding: 4px 2px">Forma Pagamento:</th>
                <th colspan="3"  style="text-align: left;padding: 4px 2px">{{ $requests['forma_pagamento'] ?? "TODAS" }}</th>
            </tr>
            
            <tr style="background-color: #3F51B5;color: #ffffff">
                <th colspan="3"  style="text-align: left;padding: 4px 2px">Tipo Serviço:</th>
                <th colspan="3"  style="text-align: left;padding: 4px 2px">{{ $servico ? $servico->Descricao : "TODAS" }}</th>
            </tr>
        
            <tr style="background-color: #3F51B5;color: #ffffff">
                <th style="text-align:left" width="2px">Nº</th>
                <th style="text-align: left;padding: 4px 2px" width="150px">Operador</th>
                <th style="text-align: left;padding: 4px 2px">Data</th>
                <th style="text-align: left;padding: 4px 2px">Valor</th>
                <th style="text-align: left;padding: 4px 2px">Recibo</th>
                <th style="text-align: left;padding: 4px 2px">Forma Pagamento</th>
                <th style="text-align: left;padding: 4px 2px">Estado</th>
            </tr>
        </thead>
        @php
            $contador = 0;
        @endphp
        <tbody>
            @foreach ($items as $item)
                @php
                    $contador++;
                @endphp
                <tr>
                    <td style="text-align:left">{{$loop->iteration}}</td>
                    <td style="padding: 5px;text-align: left">{{  $item->operador_novos ? $item->operador_novos->nome : "Estudante" }}</td>
                    <td style="text-align: left">{{ date("Y-m-d", strtotime($item->DataRegisto)) }}</td>
                    <td style="text-align: left">{{ number_format($item->valor_depositado, 2, ',', '.') }} KZ</td>
                    <td style="text-align: left">{{ $item->Codigo }}</td>
                    <td style="text-align: left">{{ $item->forma_pagamento ?? 'CUSH' }}</td>
                    @if ( $item->estado == 1 ) 
                        <td style="text-align: left">Valido</td>
                    @else 
                        @if($item->estado == 2)
                        <td style="text-align: left">Rejeitado</td>
                        @endif
                    @endif
                </tr>     
            @endforeach
        </tbody>
    </table> 
</main>

</body>
</html>



