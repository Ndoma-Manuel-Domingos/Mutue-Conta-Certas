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
        <title>LISTA DE SERVIÇOS PAGOS</title>
    
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
                <th colspan="4" style="padding: 7px;text-transform: uppercase">LISTA DE SERVIÇOS PAGOS PELO(A) ESTUDANTE - {{ $aluno->Nome_Completo }}</th>
            </tr>
            <tr style="background-color: #3F51B5;color: #ffffff;padding: 7px">
                <th colspan="4">Total: <strong>{{ count($facturas) }}</strong></th>
            </tr>
            <tr style="background-color: #3F51B5;color: #ffffff">
                <th style="text-align:left" width="2px">Nº</th>
                <th style="text-align: center;padding: 4px 2px" >Serviço</th>
                <th style="text-align: center;padding: 4px 2px" >Valor</th>
                <th style="text-align: center;padding: 4px 2px" >Data Pag.Banco</th>
                <th style="text-align: center;padding: 4px 2px" >Data de Validação</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($facturas as $item)
                <tr>
                    <td style="text-align:left">{{$loop->iteration}}</td>
                    <td>{{ $item->items->servico->Descricao ? $item->items->servico->Descricao : $item->observacao }}</td>
                    <td>{{ number_format($item->Totalgeral, 2, ',', '.') }} kz</td>
                    <td>{{ $item->DataBanco }}</td>
                    <td>{{ $item->updated_at }}</td>
                </tr>     
            @endforeach
        </tbody>
    </table> 
</main>

</body>
</html>



