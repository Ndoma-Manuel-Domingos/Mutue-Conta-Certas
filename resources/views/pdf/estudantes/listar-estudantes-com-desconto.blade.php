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
        <title>LISTAR ESTUDANTES COM DESCONTO</title>
    
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
                font-size: 10pt;
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
                font-size: 10pt;
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
                font-size: 10px;
                margin: 0;
                padding: 0;
            }
            strong{
                font-size: 13px;
            }

            .footer p {
            font-size: 10pt;
            text-align: center;
            margin: 0;
        }


        .footer {
            border-top: 1px solid #000;
            position: fixed;
            bottom: 0;
            width: 100%;
            padding: 10px 0;
            text-align: center;
        }
        </style>
    </head>
<body>

@include('pdf.estudantes.header')

<table style="background-color: rgb(234, 234, 234);margin-top: -20px">
    <tr>
        <td colspan="2" style="text-align: center;padding: 0px">LISTAGEM ESTUDANTES COM DESCONTO</td>
    </tr>
    <tr>
        <td style="text-align: left;padding: 0px">Total de Registro:</td>
        <td style="text-align: left;padding-left: 5px;border-left: 2px solid #ffffff">{{ $items->count() }}</td>
    </tr>
</table>
<main>

  

    <table class="table table-stripeds" style="">
        <thead>
            <tr style="background-color: #152ea2;color: #e3e7e8"> <th style="text-align:left" width="2px">Nº</th>
                <th style="text-align:left" width="2px">Nº</th>
                <th style="text-align: center;padding: 4px 0">Matricula</th>
                <th style="text-align: center;padding: 4px 0">Nome</th>
                <th style="text-align: center;padding: 4px 0">Instituições</th>
                <th style="text-align: center;padding: 4px 0">Tipo de Desconto</th>
                <th style="text-align: center;padding: 4px 0">Estado</th>
                <th style="text-align: center;padding: 4px 0">Semestre</th>
            </tr>
        
        </thead>
        @php
            $contador = 0;
        @endphp
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td style="text-align:left">{{$loop->iteration}}</td>
                    <td>{{ $item->codigo_matricula }}</td>
                    <td>{{ $item->nome }}</td>
                    <td>{{ $item->instituicao }}</td>
                    <td>{{ $item->tipoDesconto }}</td>
                    <td>{{ $item->Designacao }}</td>
                    <td>{{ $item->semestreItem }}</td>
                </tr>    
            @endforeach
        </tbody>
    </table> 
    <div class="footer">
        <p>Documento Processado Pelo Mutue-Finanças, Mutue - Soluções Tecnológicas Inteligentes</p>
    </div>
</main>

</body>
</html>



