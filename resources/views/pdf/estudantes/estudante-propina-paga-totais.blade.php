
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

        <title>LISTA ESTUDANTES COM MENSALIDADES TOTAIS</title>

        <style type="text/css">
            *{
                margin: 0;
                padding: 0;
                -webkit-box-sizing: border-box;
                box-sizing: border-box;
                font-family: Arial, Helvetica, sans-serif;
                text-align: left;
            }

            @page {
                size: A3;
                margin: 20mm 15mm; /* Adapte as margens conforme necessário */
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

 <script>
    function adicionarTimestampAoRodape() {
        var timeInMs = Date.now();
        document.querySelector('#timestamp').textContent = 'Timestamp Atual: '+timeInMs;
    }

</script>
    </head>
<body>


    

@include('pdf.estudantes.header')
    
<table>
    <tr style="background-color: #e3e7e8;color: #000000;">
        <th colspan="6" style="padding: 5px; text-align:center
        ">TOTAL DE ESTUDANTES COM MENSALIDADES PAGAS</th>
    </tr>
    
    <tr style="background-color: #e3e7e8;color: #000000;">
        <th colspan="3" style="padding: 5px;">Ano Lectivo:</th>
        <th colspan="3" style="padding: 5px;">{{ $ano ? $ano->Designacao : 'Todos' }}</th>
    </tr>
    
    <tr style="background-color: #e3e7e8;color: #000000;">
        <th colspan="3" style="padding: 5px;">Faculdade:</th>
        <th colspan="3" style="padding: 5px;">{{ $faculdade ? $faculdade->designacao : 'Todos' }}</th>
    </tr>            
    
    <tr style="background-color: #e3e7e8;color: #000000;">
        <th colspan="3" style="padding: 5px;">Curso:</th>
        <th colspan="3" style="padding: 5px;">{{ $curso ? $curso->Designacao : 'Todos' }}</th>
    </tr>
    
    <tr style="background-color: #e3e7e8;color: #000000;">
        <th colspan="3" style="padding: 5px;">Turno:</th>
        <th colspan="3" style="padding: 5px;">{{ $turno ?  $turno->Designacao : 'Todos' }}</th>
    </tr>
    
    <tr style="background-color: #e3e7e8;color: #000000;">
        <th colspan="3" style="padding: 5px;">Mês/Parcela:</th>
        <th colspan="3" style="padding: 5px;">{{ $mes ? $mes->designacao : 'Todos' }}</th>
    </tr>
</table>
<main>

    <table class="table table-stripeds" style="">
        <thead>      
        
            <tr style="background-color: #152ea2;color: #e3e7e8">
                <th style="text-align: left;padding: 4px 5px">Curso</th>
                <th style="text-align: center;padding: 4px 5px">Total de Estudantes</th>
            </tr>
        </thead>
        @php
            $totais = 0;
        @endphp
        <tbody>
            @foreach ($cursos as  $item)
                <tr>
                    @php
                        $totais = $totais + $item->totalEstudantes;
                    @endphp
                    <td style="text-align: left">{{ $item->curso }}</td>
                    <td style="text-align: center">{{ $item->totalEstudantes}}</td>
                </tr>
            @endforeach
            
            <tr style="background-color: #152ea2;color: #e3e7e8">
                <td style="text-align: left;padding: 4px 5px">
                    <strong>TOTAL:</strong> 
                </td>
                <td style="text-align: center;padding: 4px 5px">
                    <strong>{{ $totais }}</strong>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="footer">
        <p>Documento Processado Pelo Mutue-Finanças, Mutue - Soluções Tecnológicas Inteligentes</p>
    </div>
</main>

   

</body>
</html>

