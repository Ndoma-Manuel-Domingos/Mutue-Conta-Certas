<?php
ini_set('memory_limit', '1200M');
ini_set('max_execution_time', 1200);
?>


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
        <title>LISTA DE PAGAMENTO DE ESTUDANTES</title>
    
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

@include('pdf.estudantes.header')

<main>

    <table class="table table-stripeds" style="">
        
        <thead>
            <tr style="background-color: #3F51B5;color: #ffffff">
                <th colspan="7" style="padding: 7px">LISTA DE PAGAMENTO DE ESTUDANTES</th>
            </tr>
            <tr style="background-color: #3F51B5;color: #ffffff;padding: 7px">
                <th colspan="7">Total: <strong>{{ count($pagamentos) }}</strong></th>
            </tr>
            <tr style="background-color: #3F51B5;color: #ffffff">
                <th style="text-align:left" width="2px">Nº</th>
                <th style="text-align: center;padding: 4px 2px" >Recibo</th>
                <th style="text-align: center;padding: 4px 2px" >Serviço</th>
                <th style="text-align: center;padding: 4px 2px" >Estado</th>
                <th style="text-align: center;padding: 4px 2px" >Data Banco</th>
                <th style="text-align: center;padding: 4px 2px" >Data Registro</th>
                <th style="text-align: center;padding: 4px 2px" >Factura</th>
                <th style="text-align: center;padding: 4px 2px" >Valor</th>
          
            </tr>
        </thead>
        <tbody>
            @foreach ($pagamentos as $item)
                <tr>
                <td style="text-align:left">{{$loop->iteration}}</td>
                    <td>{{ $item->Codigo }}</td>
                    <td>{{ $item->Descricao }}</td>
                    @if ($item->estado == 0)
                        <td style="text-align: center;color: #f8d7da">Pendentes</td>
                    @endif
                    @if ($item->estado == 1)
                        <td style="text-align: center;color: #28a745">Válidos</td>
                    @endif
                    @if ($item->estado == 2)
                        <td style="text-align: center;color: #dc3545">Rejeitados</td>
                    @endif
                    <td>{{ $item->DataBanco }}</td>
                    <td>{{ $item->Data }}</td>
                    <td>{{ $item->codigo_factura }}</td>
                    <td>{{ number_format($item->valor_depositado, 2, ',', '.') }} kz</td>
                </tr>     
            @endforeach
        </tbody>
    </table> 
</main>

</body>
</html>



