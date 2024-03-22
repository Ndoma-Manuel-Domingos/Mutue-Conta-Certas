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
    <h2 style="text-align:center"> Classes </h2>
    <div style="margin:auto 0px">

   </div>
      <table id="table_id" class="display" style="background-color:white; border-radius:4px;">
    <thead>
        <tr class="">
            <th style="text-align: center;color:white; background-color: gray; width: 5px;"><b>#</b></th>
            <th style="text-align: center;color:white; background-color: gray;"><b>Número da classe</b></th>
            <th style="text-align: center;color:white; background-color: gray;"><b>Designação da classe</b></th>
            <th style="text-align: center;color:white; background-color: gray;"><b>Empresa</b></th>
            <th style="text-align: center;color:white; background-color: gray;"><b>Estado</b></th>
            
            {{-- <th style="text-align: center;color:white; background-color: gray;"><b>Número</b></th> --}}
        </tr>
    </thead>
    
    <tbody>
        @foreach($movimentos_data as $item)
        <tr>
            <td style="text-align: center;"></td>
            <td style="text-align: center;">{{$item}}</td>
            {{-- <td style="text-align: center;">{{$item->classe->numero}}</td>
            <td style="text-align: center;">{{$item->classe->designacao}}</td>
            <td style="text-align: center;">{{$item->empresa->nome_empresa}}</td>
            <td style="text-align: center;">{{$item->estado}}</td> --}}
            
            {{-- <td style="text-align: center;">{{$item}}</td>             --}}
        </tr>
        @endforeach
    </tbody>
</table>

    </main>
    
    <span style="text-align: center; font-size: 16px;">
      <p>Documento processado pelo software MUTUE - Contas Certas, desenvolvido pela Mutue - Soluções Tecnológicas
          Inteligentes.</p>
  </span>

  </body>
</html>