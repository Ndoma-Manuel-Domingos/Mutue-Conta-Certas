<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Lista de Movimentos</title>
    
    <link rel="stylesheet" href="css/style.css" media="all" />
    <style>

    .page-break {
        page-break-after: always;
    }

    .blue-text{
      color: blue;
    }
    .red-text{
      color: red;
    }
    .green-text{
      color: green;
    }
    .orange-text{
      color: orange;
    }

    tr th{
        background-color: gray;
        color:white;
        text-align: center;
        
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
          <br>Bairro Kinaxixi, Luanda.</div>
         <div>+244 947716133/+244 942364667</div>
        <div><a href="mailto:geral@uma.co.ao">geral@uma.co.ao</a></div>
      </div>
      </div>
    </header>
    <main>
    <h2 style="text-align:center"> Listagem dos Movimentos </h2>
    <div style="margin:auto 0px">

   </div>
      <table id="table_id" class="display" style="background-color:white; border-radius:4px;">
    <thead>
        <tr class="">
            <th><b>Nº</b></th>
            <th><b>Diário</b></th>
            <th><b>Documento</b></th>
            <th><b>Débito</b></th>
            <th><b>Crédito</b></th>
            <th><b>Data</b></th>
            <th><b>Execício</b></th>
            <th><b>Operador</b></th>
            
        </tr>
    </thead>
    
    <tbody>
        @foreach($movimentos_data as $item)
        <tr>
            <td style="text-align: center;">{{$item->id}}</td>
            <td style="text-align: center;">{{$item->diario->numero}} - {{$item->diario->designacao}}</td>
            <td style="text-align: center;">{{$item->tipo_documento->numero}} - {{$item->tipo_documento->designacao}}</td>
            <td style="text-align: center;">{{$item->debito}}</td>
            <td style="text-align: center;">{{$item->credito}}</td>
            <td style="text-align: center;">{{$item->data_lancamento}}</td>
            <td style="text-align: center;">{{$item->exercicio->designacao}}</td>
            <td style="text-align: center;">{{$item->criador->name}}</td>
           
            
        </tr>
        @endforeach
    </tbody>
</table>

    </main>

    <div class="footer margin-top">
      <hr>
      <p style="text-align:right">Data: {{ date('Y-m-d H:i:s') }} </p>

      <div align="center"> Documento processado pelo software MUTUE - Contas Certas.</div>
      
  </div>

  </body>
</html>

