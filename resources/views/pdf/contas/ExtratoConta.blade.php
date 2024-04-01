<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Extrato de Contas</title>

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

        .footer {
            font-size: 0.875rem;
            padding: 1rem;
            background-color: rgb(255, 255, 255);
            bottom: 0;
            position: fixed;
            width: 90%;
            text-align: center;
        }
        th{
            border-right: 1px solid black;
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
        <h2 style="text-align:center"> Extrator de Contas </h2>
        <div style="margin:auto 0px">

        </div>
        <table
        class="table table-bordered table-hover"
        id="tabela_de_balancetes"
      >
        <thead>
          <tr style="border-right: 1px solid black;">
            <th style="text-align: center;color:white; background-color: gray;">Conta</th>
            <th style="text-align: center;color:white; background-color: gray;">Descrição</th>
            <th style="text-align: center;color:white; background-color: gray;">Débito</th>
            <th style="text-align: center;color:white; background-color: gray;">Crédito</th>
            <th style="text-align: center;color:white; background-color: gray;">Data</th>
          </tr>
        </thead>

        <tbody>
          <tr>
            <th></th>
            <th></th>
            @foreach ($resultado as $resultado)
                <th style="font-weight: bold; text-align: left;">{{ number_format($resultado, 2, ',', '.') }}</th>
            @endforeach
            <th></th>
          </tr>
          @foreach ($movimentos as $item)
          <tr>
            <td style="text-align: left;">{{ $item->subconta->numero }}</td>
            <td style="text-align: left;">{{ $item->subconta->designacao }}</td>
            <td style="text-align: left; color: blue;">{{ number_format($item->debito, 2, ',', '.') }}</td>
            <td style="text-align: left; color: red;">{{ number_format($item->credito, 2, ',', '.') }}</td>
            <td style="text-align: left;">{{ $item->movimento->data_lancamento }}</td>
          </tr>
          @endforeach
          
        </tbody>
      </table>
        <script type='text/php'>
            if (isset($pdf)) 
            {               
                $pdf->page_text(60, $pdf->get_height() - 50, "{PAGE_NUM} de {PAGE_COUNT}", null, 12, array(0,0,0));
            }
        </script>
    </main>

    {{-- <br>
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
    <hr>
    <span style="text-align: center; font-size: 12px; align-content: bottom;">
        <p>Documento processado pelo software MUTUE - Contas Certas, desenvolvido pela Mutue - Soluções Tecnológicas
            Inteligentes.</p>
    </span> --}}

    <div class="footer margin-top">
        <hr>
        <p style="text-align:right">Data: {{ date('Y-m-d H:i:s') }} </p>

        <div align="center"> Documento processado pelo software MUTUE - Contas Certas.</div>

    </div>

</body>

</html>
