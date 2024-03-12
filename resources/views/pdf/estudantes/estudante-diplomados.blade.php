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

        <title>LISTA ESTUDANTES DIPLOMADOS</title>

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
        </style>
    </head>
<body>

@include('pdf.estudantes.header')
    
<main>

    <table class="table table-stripeds" style="">
        <thead>
        
            <tr style="background-color: #3F51B5;color: #ffffff;">
                <th colspan="11" style="padding: 5px;">LISTA ESTUDANTES DIPLOMADOS</th>
            </tr>
                        
            <tr style="background-color: #a1a4b9;color: #ffffff;">
                <th colspan="3" style="padding: 5px;">Ano Lectivo:</th>
                <th colspan="8" style="padding: 5px;">{{ $ano ? $ano->Designacao : 'Todos' }}</th>
            </tr>
            
            <tr style="background-color: #a1a4b9;color: #ffffff;">
                <th colspan="3" style="padding: 5px;">Graduação:</th>
                <th colspan="8" style="padding: 5px;">{{ $candidatura ? $candidatura->designacao : 'Todos' }}</th>
            </tr>            
            
            <tr style="background-color: #a1a4b9;color: #ffffff;">
                <th colspan="3" style="padding: 5px;">Curso:</th>
                <th colspan="8" style="padding: 5px;">{{ $curso ? $curso->Designacao : 'Todos' }}</th>
            </tr>
            
            <tr style="background-color: #a1a4b9;color: #ffffff;">
                <th colspan="3" style="padding: 5px;">Faculdade:</th>
                <th colspan="8" style="padding: 5px;">{{ $faculdade ?  $faculdade->designacao : 'Todos' }}</th>
            </tr>
            
            <tr style="background-color: #a1a4b9;color: #ffffff;">
                <th colspan="3" style="padding: 5px;">Total de DE REGISTOS:</th>
                <th colspan="8" style="padding: 5px;">{{ count($diplomados) }}</th>
            </tr>
            
    
            <tr style="background-color: #3F51B5;color: #ffffff">
                <th style="text-align:left" width="2px">Nº</th>
                <th style="text-align: center;padding: 4px 0" width="150px">Nº Matrícula</th>
                <th style="text-align: center;padding: 4px 0">Nome</th>
                <th style="text-align: center;padding: 4px 0">Nº Bilhete</th>
                <th style="text-align: center;padding: 4px 0">Gênero</th>
                <th style="text-align: center;padding: 4px 0">Tipo Aluno</th>
                <th style="text-align: center;padding: 4px 0">Data Nascimento</th>
                <th style="text-align: center;padding: 4px 0">Curso</th>
                <th style="text-align: center;padding: 4px 0">Tipo Candidatura</th>
                <th style="text-align: center;padding: 4px 0">Data Matrícula</th>
                <th style="text-align: center;padding: 4px 0">Data Conclusão</th>
                <th style="text-align: center;padding: 4px 0">Nota</th>
            </tr>
            
        </thead>
        <tbody>
            @foreach ($diplomados as $item)
                <tr>
                    <td style="text-align:left">{{$loop->iteration}}</td>
                    <td style="padding: 5px;text-align: center">{{ $item->codigo_matricula }}</td>
                    <td style="text-align: center">{{ $item ? ($item->matricula ? ($item->matricula->admissao ? ($item->matricula->admissao->preinscricao ? $item->matricula->admissao->preinscricao->Nome_Completo : "") : "") : "")  : "" }}</td>
                    <td style="text-align: center">{{ $item ? ($item->matricula ? ($item->matricula->admissao ? ($item->matricula->admissao->preinscricao ? $item->matricula->admissao->preinscricao->Bilhete_Identidade : "") : "") : "")  : "" }}</td>
                    <td style="text-align: center">{{ $item ? ($item->matricula ? ($item->matricula->admissao ? ($item->matricula->admissao->preinscricao ? $item->matricula->admissao->preinscricao->Sexo : "") : "") : "")  : "" }}</td>
                    <td style="text-align: center">Tipo 1</td>
                    <td style="text-align: center">{{ $item ? ($item->matricula ? ($item->matricula->admissao ? ($item->matricula->admissao->preinscricao ? $item->matricula->admissao->preinscricao->Data_Nascimento : "") : "") : "")  : "" }}</td>
                    <td style="text-align: center">{{ $item ? ($item->matricula ? ($item->matricula->curso ? $item->matricula->curso->Designacao : "") : "") : ""  }}</td>
                    <td style="text-align: center">{{ $item ? ($item->matricula ? ($item->matricula->admissao ? ($item->matricula->admissao->preinscricao ? ($item->matricula->admissao->preinscricao->grau_academico ? $item->matricula->admissao->preinscricao->grau_academico->designacao : "") : "") : "") : "")  : "" }}</td>
                    
                    <td style="text-align: center">{{ $item ? ($item->matricula ? $item->matricula->Data_Matricula : "") : ""  }}</td>
                    <td style="text-align: center">{{ $item->data_conclusao }}</td>
                    <td style="text-align: center">{{ $item->nota }}</td>
                    
                </tr>
            @endforeach
        </tbody>
    </table>

</main>

</body>
</html>



