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

        .footer {
            font-size: 0.875rem;
            padding: 1rem;
            background-color: rgb(255, 255, 255);
            bottom: 0;
            position: fixed;
            width: 90%;
            text-align: center;
        }
    </style>
</head>

<body>
    <header class="clearfix">
        <div id="logo">
            <img src="images/log.png">
        </div>
        <div id="company">
            <h2 class="name">Mutue - Soluções Tecnológicas Inteligentes</h2>
            <div>Rua Nossa Senhora da Muxima Nº 10, <br>
                <br>Bairro Kinaxixi, Luanda.
            </div>
            <div>+244 947716133/+244 942364667</div>
            <div><a href="mailto:geral@uma.co.ao">geral@uma.co.ao</a></div>
        </div>
        </div>
    </header>
    <main>
        <h2 style="text-align:center"> Periodos </h2>
        <div style="margin:auto 0px">

        </div>
        <table id="table_id" class="display" style="background-color:white; border-radius:4px;">
            <thead>
                <tr class="">

                    <th style="text-align: center;color:white; background-color: gray;"><b>Designação da conta</b></th>
                    <th style="text-align: center;color:white; background-color: gray;"><b>Exercício</b></th>
                    <th style="text-align: center;color:white; background-color: gray;"><b>Estado</b></th>

                </tr>
            </thead>

            <tbody>
                @foreach ($periodo_data as $item)
                    <tr>
                        <td style="text-align: left;">{{ $item->designacao }}</td>
                        <td style="text-align:left;">{{ $item->exercicio->designacao }}</td>
                        <td style="text-align: left;">{{ $item->estado }}</td>

                        {{-- <td style="text-align: center;">{{$item}}</td>             --}}
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
    <hr>
    <span style="text-align: center; font-size: 12px; align-content: bottom;">
      <p style="text-align:right">Data: {{ date('Y-m-d H:i:s') }} </p>
      <p>Documento processado pelo software MUTUE - Contas Certas.</p>
  </span>

</body>

</html>
