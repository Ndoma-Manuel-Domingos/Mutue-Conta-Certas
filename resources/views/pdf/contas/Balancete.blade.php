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
        <h2 style="text-align:center"> Balancete </h2>
        <div style="margin:auto 0px">

        </div>

        <table class="table table-bordered table-hover" id="tabela_de_balancetes">
            <thead>
              <tr>
                <th style="text-align: center;color:white; background-color: gray;">Conta</th>
                <th style="text-align: center;color:white; background-color: gray;">Descrição</th>
                <th style="text-align: center;color:white; background-color: gray;">Mov. Débito</th>
                <th style="text-align: center;color:white; background-color: gray;">Mov. Crédito</th>
                <th style="text-align: center;color:white; background-color: gray;">Saldo. Débito</th>
                <th style="text-align: center;color:white; background-color: gray;">Saldo. Crédito</th>
                {{-- <th style="text-align: center;color:white; background-color: gray;">Operador</th> --}}
              </tr>
              
              
              <tr>
                <th></th>
                <th></th>
                <th></th>
                <th>Soma Saldos</th>
                <th>{{ number_format(0, 2, ',', '.') }}</th>
                <th>{{ number_format(0, 2, ',', '.') }}</th>
              </tr>
              
              <tr>
                <th></th>
                <th class="text-right" style="background-color: gray; color: white;">Soma Líquida</th>
                 {{-- <th>{{ (object)$resultado }}</th>  --}}
               @foreach ($resultado as $resultado)
               <th>{{number_format($resultado, 2, ',', '.') }}</th> 
               @endforeach
                {{-- <th>{{ number_format($resultado->total_movimento_credito, 2, ',', '.') }}</th>
                <th>{{ number_format($resultado->total_debito, 2, ',', '.') }}</th>
                <th>{{ number_format($resultado->total_credito, 2, ',', '.') }}</th> --}}
              </tr>
              
            </thead>
            
            <tbody>
                @foreach ($movimentos as $item)
                    <tr>
                        <td>{{ $item->subconta->numero }}</td>
                        <td>{{ $item->subconta->designacao }}</td>
                        <td>{{ number_format($item->debito, 2, ',', '.') }}</td>
                        <td>{{ number_format($item->credito, 2, ',', '.') }}</td>
                        <td>{{ number_format($item->debito == $item->credito ? 0 : ($item->debito > $item->credito ? $item->debito - $item->credito : 0), 2, ',', '.') }}</td>
                        <td>{{ number_format($item->credito == $item->debito ? $item->credito - $item->debito : ($item->credito > $item->debito ? $item->credito - $item->debito : 0), 2, ',', '.') }}</td>
                        {{-- <td>{{ $item->criador->name }}</td> --}}
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
    <br>
    <br><br>
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
    <div class="footer margin-top">
        <hr>
        <p style="text-align:right">Data: {{ date('Y-m-d H:i:s') }} </p>

        <div align="center"> Documento processado pelo software MUTUE - Contas Certas.</div>
        
    </div>
</body>

</html>
