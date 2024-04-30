<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Balanço</title>

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
        <div id="logo" style="padding: 0">
            <img src="images/{{$dados_empresa->logotipo_da_empresa ?? 'log.png' }}" style="width: 150px;height: 150px;position: absolute;top: -40px">
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
        <h2 style="text-align: left;border-bottom: 1px solid #c2c2c2;padding-bottom: 2px;text-transform: uppercase;font-size: 11pt">Balanço <span style="float: right">DATA INICIO: {{ $requests['data_inicio'] ?? date('Y-m-d')  }}, DATA FINAL {{ $requests['data_final'] ?? date('Y-m-d') }}</span></h2>
        <h2 style="text-align: left;border-bottom: 1px solid #c2c2c2;padding-bottom: 2px;text-transform: uppercase;font-size: 11pt">Exercício: {{ $exercicio->designacao ?? '' }}</h2>

        <div style="margin:auto 0px"> </div>


        <table class="table table-hover table-bordered text-nowrap">
            <thead>
                <tr>
                    <th style="text-align: left;color:white; background-color: gray;">Designação</th>
                    <th style="text-align: center;color:white; background-color: gray;width: 5px" class="text-center">Notas</th>
                    <th style="text-align: right;color:white; background-color: gray;">2024</th>
                    <th style="text-align: right;color:white; background-color: gray;">2023</th>
                </tr>
            </thead>
            <tbody>
                <!-- <template> -->
                <!-- ================================================================================ -->
                <tr>
                    <th colspan="4" style="text-align: left;color:white; background-color: gray;">Activos não correntes:</th>
                </tr>
                {{-- @foreach ($conta_do_activos_nao_corrente as $key => $item)
                <tr>
                    <td style="text-align: left"> {{ $item->conta->numero ?? "" }} - {{ $item->conta->designacao ?? "" }}</td>
                    <td style="text-align: left">{{ $key + 2 }}</td>
                    <td style="text-align: left">{{ $item->debito > $item->credito ? $item->debito - $item->credito : $item->credito - $item->debito }}</td>
                    <td style="text-align: left">0</td>
                </tr>
                @endforeach --}}
                
                <tr>
                    <td style="text-align: left;">Imobilizações corpóreas ....................................</td>
                    <td style="text-align: center;"><a href="">4</a></td>
                    <td style="text-align: right;">{{ number_format(8000, 2) }}</td>
                    <td style="text-align: right;">{{ number_format(0, 2) }}</td>
                </tr>
           
                <tr>
                    <td style="text-align: left;">Imobilizações incorpóreas ....................................</td>
                    <td style="text-align: center;"><a href="">5</a></td>
                    <td style="text-align: right;">{{ number_format(9000, 2) }}</td>
                    <td style="text-align: right;">{{ number_format(0, 2) }}</td>
                </tr>
           
                <tr>
                    <td style="text-align: left;">Investimentos em subsidiárias e associadas ....................................</td>
                    <td style="text-align: center;"><a href="">6</a></td>
                    <td style="text-align: right;">{{ number_format(1000, 2) }}</td>
                    <td style="text-align: right;">{{ number_format(0, 2) }}</td>
                </tr>
                  
                <tr>
                    <td style="text-align: left;">Outros activos financeiros ....................................</td>
                    <td style="text-align: center;"><a href="">7</a></td>
                    <td style="text-align: right;">{{ number_format(0, 2) }}</td>
                    <td style="text-align: right;">{{ number_format(0, 2) }}</td>
                </tr>
                <tr>
                    <td style="text-align: left;">Outros activos não correntes ....................................</td>
                    <td style="text-align: center;"><a href="">9</a></td>
                    <td style="text-align: right;">{{ number_format(0, 2) }}</td>
                    <td style="text-align: right;">{{ number_format(0, 2) }}</td>
                </tr>
                
                <tr>
                    <th></th>
                    <th style="text-align: center"></th>
                    <td style="text-align: right">{{ number_format(1800, 2) }}</td>
                    <th style="text-align: right">0</th>
                </tr>
                <!-- </template> -->
                <!-- ================================================================================ -->
                <!-- <template> -->
                <tr>
                    <th colspan="4" style="text-align: left;color:white; background-color: gray;">Activos correntes:</th>
                </tr>
                
                {{-- @foreach ($conta_do_activos_corrente as $key => $item)
                <tr>
                    <td style="text-align: left"> {{ $item->conta->numero ?? "" }} - {{ $item->conta->designacao ?? "" }}</td>
                    <td style="text-align: left">{{ $key + 4 }}</td>
                    <td style="text-align: left">{{ $item->debito > $item->credito ? $item->debito - $item->credito : $item->credito - $item->debito }}</td>
                    <td style="text-align: left">0</td>
                </tr>
                @endforeach --}}
                
                <tr>
                    <td style="text-align: left">Existências ....................................</td>
                    <td style="text-align: center"><a href="">8</a></td>
                    <td style="text-align: right">{{ number_format(0, 2) }}</td>
                    <td style="text-align: right">{{ number_format(0, 2) }}</td>
                  </tr>

                  <tr>
                    <td style="text-align: left">Contas a receber ....................................</td>
                    <td style="text-align: center"><a href="">9</a></td>
                    <td style="text-align: right">{{ number_format(0, 2) }}</td>
                    <td style="text-align: right">{{ number_format(0, 2) }}</td>
                  </tr>
                  
                  <tr>
                    <td style="text-align: left">Disponibilidades ....................................</td>
                    <td style="text-align: center"><a href="">10</a></td>
                    <td style="text-align: right">{{ number_format(0, 2) }}</td>
                    <td style="text-align: right">{{ number_format(0, 2) }}</td>
                  </tr>
                  
                  <tr>
                    <td style="text-align: left">Outros activos correntes  ....................................</td>
                    <td style="text-align: center"><a href="">11</a></td>
                    <td style="text-align: right">{{ number_format(0, 2) }}</td>
                    <td style="text-align: right">{{ number_format(0, 2) }}</td>
                  </tr>
                
                {{-- <tr>
                    <th></th>
                    <th style="text-align: center"></th>
                    <th style="text-align: left">0</th>
                    <th style="text-align: left">0</th>
                </tr> --}}

                <tr>
                    <th style="text-align: right">Total do activo</th>
                    <th style="text-align: center"></th>
                    <th style="text-align: right">{{ number_format(1800, 2) }}</th>
                    <th style="text-align: right">0</th>
                </tr>
                <!-- </template> -->

                <!-- <template> -->
                <!-- ================================================================================ -->
                <tr>
                    <th colspan="4" style="text-align: left">CAPITAL PRÓPRIO E PASSIVO</th>
                </tr>

                <tr>
                    <th colspan="4" style="text-align: left;color:white; background-color: gray;">Capital próprio:</th>
                </tr>
                <tr>
                    <td style="text-align: left">Capital ....................................</td>
                    <td style="text-align: center"><a href="">12</a></td>
                    <td style="text-align: right">{{ number_format(0, 2) }}</td>
                    <td style="text-align: right">{{ number_format(0, 2) }}</td>
                  </tr>

                  <tr>
                    <!-- ---------------------------------------- -->
                    <td style="text-align: left">Reservas ....................................</td>
                    <td style="text-align: center"><a href="">13</a></td>
                    <td style="text-align: right">{{ number_format(0, 2) }}</td>
                    <td style="text-align: right">{{ number_format(0, 2) }}</td>
                  </tr>
                  
                  <tr>
                    <!-- ---------------------------------------- -->
                    <td style="text-align: left">Resultados transitados ....................................</td>
                    <td style="text-align: center"><a href="">14</a></td>
                    <td style="text-align: right">{{ number_format(0, 2) }}</td>
                    <td style="text-align: right">{{ number_format(0, 2) }}</td>
                  </tr>
                  <tr>
                    <!-- ---------------------------------------- -->
                    <td style="text-align: left">Resultados do exercício ....................................</td>
                    <td style="text-align: center"><a href="">13</a></td>
                    <td style="text-align: right">{{ number_format(0, 2) }}</td>
                    <td style="text-align: right">{{ number_format(0, 2) }}</td>
                  </tr>

                  <tr>
                    <th></th>
                    <th style="text-align: center"></th>
                    <th style="text-align: right"></th>
                    <th style="text-align: right">{{ number_format(0, 2) }}</th>
                  </tr>

                <!-- ================================================================================ -->

                <tr>
                    <th colspan="4" style="text-align: left;color:white; background-color: gray;">Passivo não corrente:</th>
                </tr>
                {{-- @foreach ($conta_do_passivos_nao_corrente as $key => $item)
                <tr>
                    <td style="text-align: left">{{ $item->conta->numero ?? "" }} - {{ $item->conta->designacao ?? "" }}</td>
                    <td style="text-align: left">{{ $key + 6 }}</td>
                    <td style="text-align: left">{{ $item->debito > $item->credito ? $item->debito - $item->credito : $item->credito - $item->debito }}</td>
                    <td style="text-align: left">0</td>
                </tr>
                @endforeach --}}
                
                <tr>
                    <td style="text-align: left">Impostos diferidos. . . .</td>
                    <td style="text-align: left" style="text-align: center;"><a href="">16</a></td>
                    <td style="text-align: right;;">{{ number_format(0,2) }}</td>
                    <td style="text-align: right;">{{ number_format(0,2) }}</td>
                  </tr>
                  
                  <tr>
                    <td style="text-align: left">Provisões para pensões. . . .</td>
                    <td style="text-align: left" style="text-align: center;"><a href="">17</a></td>
                    <td style="text-align: right;;">{{ number_format(0,2) }}</td>
                    <td style="text-align: right;">{{ number_format(0,2) }}</td>
                  </tr>
                    
                  <tr>
                    <td style="text-align: left">Provisões para outros riscos e encargos. . . .</td>
                    <td style="text-align: left" style="text-align: center;"><a href="">18</a></td>
                    <td style="text-align: right;;">{{ number_format(0,2) }}</td>
                    <td style="text-align: right;">{{ number_format(0,2) }}</td>
                  </tr>
                  
                  <tr>
                    <td style="text-align: left">Outros passivos não correntes. . . .</td>
                    <td style="text-align: left" style="text-align: center;"><a href="">19</a></td>
                    <td style="text-align: right;;">{{ number_format(0,2) }}</td>
                    <td style="text-align: right;">{{ number_format(0,2) }}</td>
                  </tr>

                <tr>
                    <th></th>
                    <th style="text-align: left"></th>
                    <th style="text-align: right">0</th>
                    <th style="text-align: right">0</th>
                </tr>

                <!-- ================================================================================ -->

                <tr>
                    <th colspan="4" style="text-align: left;color:white; background-color: gray;">Passivo corrente:</th>
                </tr>
                
                <tr>
                    <td style="text-align: left">Contas a pagar. . . . .</td>
                    <td style="text-align: center;"><a href="">19</a></td>
                    <td style="text-align: right">{{ number_format(0,2) }}</td>
                    <td style="text-align: right">{{ number_format(0,2) }}</td>
                  </tr>
                  
                  <tr>
                    <td style="text-align: left">Empréstimos de curto prazo. . . . .</td>
                    <td style="text-align: center;"><a href="">20</a></td>
                    <td style="text-align: right">{{ number_format(0,2) }}</td>
                    <td style="text-align: right">{{ number_format(0,2) }}</td>
                  </tr>
                  
                  <tr>
                    <td style="text-align: left">Parte cor. dos empr. a médio e longo prazos. . . . .</td>
                    <td style="text-align: center;"><a href="">15</a></td>
                    <td style="text-align: right">{{ number_format(0,2) }}</td>
                    <td style="text-align: right">{{ number_format(0,2) }}</td>
                  </tr>
                  
                  <tr>
                    <td style="text-align: left">Outros passivos correntes. . . . .</td>
                    <td style="text-align: center;"><a href="">21</a></td>
                    <td style="text-align: right">{{ number_format(0,2) }}</td>
                    <td style="text-align: right">{{ number_format(0,2) }}</td>
                  </tr>
                
                {{-- @foreach ($conta_do_passivos_corrente as $key => $item)
                <tr>
                    <td style="text-align: left">{{ $item->conta->numero ?? "" }} - {{ $item->conta->designacao ?? "" }}</td>
                    <td style="text-align: left">{{ $key + 8 }}</td>
                    <td style="text-align: left">{{ $item->debito > $item->credito ? $item->debito - $item->credito : $item->credito - $item->debito }}</td>
                    <td style="text-align: left"></td>
                </tr>
                @endforeach --}}
                <tr>
                    <th></th>
                    <th style="text-align: left"></th>
                    <th style="text-align: right">0</th>
                    <th style="text-align: right">0</th>
                </tr>

                <tr>
                    <th></th>
                    <th style="text-align: left">Total do capital próprio e passivo</th>
                    <th style="text-align: right">0</th>
                    <th style="text-align: right">0</th>
                </tr>
                <!-- </template> -->
            </tbody>
        </table>




        <script type='text/php'>
            if (isset($pdf)) 
            {               
                $pdf->page_text(60, $pdf->get_height() - 50, "{PAGE_NUM} de {PAGE_COUNT}", null, 12, array(0,0,0));
            }
        </script>
    </main>


    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

    <div class="footer margin-top">
        <hr>
        <p style="text-align:right">Data: {{ date('Y-m-d H:i:s') }} </p>

        <div align="center"> Documento processado pelo software MCC(Mutue Contas Certas).</div>

    </div>
</body>

</html>
