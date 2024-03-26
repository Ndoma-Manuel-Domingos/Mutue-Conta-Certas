<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Lista de Avaliações</title>

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
        <div id="logo">
            <img src="images/log.png">
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
        <h2 style="text-align:center; font-size: 30px; color: black;"> PGC - Plano Geral de Contabilidade </h2>
        <div style="margin:auto 0px">

        </div>
        {{-- <table id="table_id" class="display" style="background-color:white; border-radius:4px;">
            <thead>
                <tr class="">
                
                <th style="text-align: center;color:white; background-color: gray;"><b>Designação da conta</b></th>
                <th style="text-align: center;color:white; background-color: gray;"><b>Exercício</b></th>
                <th style="text-align: center;color:white; background-color: gray;"><b>Estado</b></th>

                </tr>
            </thead>

            <tbody>
                @foreach ($plano_data as $item)
                    <tr>
                        <td style="text-align: left;">{{ $item }}</td>
                         <td style="text-align:left;">{{$item->exercicio->designacao}}</td>
            <td style="text-align: left;">{{$item->estado}}</td> 

                        <td style="text-align: center;">{{$item}}</td>            
                    </tr>
                @endforeach
            </tbody>
        </table> --}}

        @foreach ($plano_data as $plano)
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr class="btn-info">
                        <th class="text-uppercase" style="color: white; background-color: #17a2b8; font-size: 20px">
                            {{ $plano->classe->numero }} - {{ $plano->classe->designacao }}</th>
                    </tr>
                </thead>
                @foreach ($plano->classe->contas_empresa as $conta)
                    <tbody>
                        <tr>
                            <th><span
                                    style="margin-left: 35px; color: black; font-family: sans-serif; font-weight: bold; font-size: 16px;">{{ $conta->conta->numero }}
                                    - {{ $conta->conta->designacao }} </span></th>
                        </tr>
                        @foreach ($conta->sub_contas_empresa as $sub_conta)
                            <tr>
                                <td><span style="margin-left: 100px; font-size: 16px; color: black;">
                                        {{ $sub_conta->numero }} - {{ $sub_conta->designacao }}</span></td>
                            </tr>
                        @endforeach

                    </tbody>
                @endforeach
            </table>
            
        @endforeach
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
    <br><br>
    <br>
    <br>
    <br>
    <br><br>
    <br>
    <br>
    <br>
    <br><br>
    <br>
    <br>
    <br>
    <br><br>
    <br>
    <br>
    <br>
    <br><br>
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
    <hr>
    <span style="text-align: center; font-size: 16px; align-content: bottom;">
        <p style="text-align:right">Data: {{ date('Y-m-d H:i:s') }} </p>

        <p>Documento processado pelo software MUTUE - Contas Certas.</p>
    </span>
  
                
</body>

</html>

<style>
    tr {
        background-color: red;
    }

    tr th {
        text-align: left;
        margin-left: 50px;
    }

    tr td {
        text-align: left;
        margin-top: 160px;
    }

    .footer {
        font-size: 0.875rem;
        padding: 1rem;
        background-color: rgb(255, 255, 255);
    }

    .page:after {
        position: absolute;
        content: counter(page);
    }
</style>
