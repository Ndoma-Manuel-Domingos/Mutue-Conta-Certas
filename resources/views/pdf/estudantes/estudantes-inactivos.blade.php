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
        <title>ESTUDANTES INACTIVOS</title>
    
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

<main>

    <table class="table table-stripeds" style="">
        
        <thead>
            <tr style="background-color: #e3e7e8;color: #000000;">
                <th colspan="7" style="padding: 7px text-align:center">ESTUDANTES INACTIVOS</th>
            </tr>
            
            <tr style="background-color: #e3e7e8;color: #000000;">
                <th colspan="3" style="padding: 5px;">Faculdade:</th>
                <th colspan="4" style="padding: 5px;">{{ $faculdade ? $faculdade->designacao : 'Todos' }}</th>
            </tr>
            
            <tr style="background-color: #e3e7e8;color: #000000;">
                <th colspan="3" style="padding: 5px;">Curso:</th>
                <th colspan="4" style="padding: 5px;">{{ $curso ? $curso->Designacao : 'Todos' }}</th>
            </tr>
            
            <tr style="background-color: #e3e7e8;color: #000000;">
                <th colspan="3" style="padding: 5px;">Grau:</th>
                <th colspan="4" style="padding: 5px;">{{ $grau ? $grau->designacao : 'Todos' }}</th>
            </tr>
            
            <tr style="background-color: #e3e7e8;color: #000000;">
                <th colspan="3" style="padding: 5px;">Ano Inicio:</th>
                <th colspan="4" style="padding: 5px;">{{ $ano_inicio ? $ano_inicio->Designacao : 'Todos' }}</th>
            </tr>
            
            <tr style="background-color: #e3e7e8;color: #000000;">
                <th colspan="3" style="padding: 5px;">Ano Fim:</th>
                <th colspan="4" style="padding: 5px;">{{ $ano_final ? $ano_final->Designacao : 'Todos' }}</th>
            </tr>
            
            <tr style="background-color: #e3e7e8;color: #000000;">
                <th colspan="7">Total: <strong>{{ count($estudantes) }}</strong></th>
            </tr>
            <tr style="background-color: #152ea2;color: #e3e7e8">
                <th style="text-align:left" width="2px">Nº</th>
                <th style="text-align: center;padding: 4px 2px" >Nº Matricula</th>
                <th style="text-align: center;padding: 4px 2px" >Ano De Ingresso</th>
                <th style="text-align: center;padding: 4px 2px" >Nome</th>
                <th style="text-align: center;padding: 4px 2px" >Bilheite</th>
                <th style="text-align: center;padding: 4px 2px" >Curso</th>
                <th style="text-align: center;padding: 4px 2px" >Telefone</th>
                {{-- <th style="text-align: center;padding: 4px 2px" >E-mail</th> --}}
                <th style="text-align: center;padding: 4px 2px" >Divida(AOA)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($estudantes as $item)
                <tr>
                    <td style="text-align:left">{{$loop->iteration}}</td>
                    <td style="text-align: center;">{{ $item->matricula }} </td>
                    <td style="text-align: center;">{{ $item->anoLectivo ?? 'sem ano' }}</td>
                    <td style="text-align: center;">{{ $item->nome ?? 'nenhum'}}</td>
                    <td style="text-align: center;">{{ $item->bilhete ?? 'nenhum' }}</td>
                    <td style="text-align: center;">{{ $item->curso ?? 'nenhum' }}</td>
                    <td style="text-align: center;">{{ $item->telefone ?? 'nenhum' }}</td>
                    {{-- <td style="text-align: center;">{{ $item->email ?? 'nenhum' }}</td> --}}
                    <td style="text-align: center;">{{ number_format(0, 2, ',', '.') }} kz</td>
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



