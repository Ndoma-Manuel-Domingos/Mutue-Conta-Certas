@php

    function valor_por_extenso( $v ){
    		
        $v = filter_var($v, FILTER_SANITIZE_NUMBER_INT);
       
        $sin = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
        $plu = array("centavos", "", "mil", "milhões", "bilhões", "trilhões","quatrilhões");
    
        $c = array("", "cem", "duzentos", "trezentos", "quatrocentos","quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
        $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta","sessenta", "setenta", "oitenta", "noventa");
        $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze","dezesseis", "dezesete", "dezoito", "dezenove");
        $u = array("", "um", "dois", "três", "quatro", "cinco", "seis","sete", "oito", "nove");
    
        $z = 0;
    
        $v = number_format( $v, 2, ".", "." );
        $int = explode( ".", $v );
    
        for ( $i = 0; $i < count( $int ); $i++ ) 
        {
            for ( $ii = mb_strlen( $int[$i] ); $ii < 3; $ii++ ) 
            {
                $int[$i] = "0" . $int[$i];
            }
        }
    
        $rt = null;
        $fim = count( $int ) - ($int[count( $int ) - 1] > 0 ? 1 : 2);
        for ( $i = 0; $i < count( $int ); $i++ )
        {
            $v = $int[$i];
            $rc = (($v > 100) && ($v < 200)) ? "cento" : $c[$v[0]];
            $rd = ($v[1] < 2) ? "" : $d[$v[1]];
            $ru = ($v > 0) ? (($v[1] == 1) ? $d10[$v[2]] : $u[$v[2]]) : "";
    
            $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
            $t = count( $int ) - 1 - $i;
            $r .= $r ? " " . ($v > 1 ? $plu[$t] : $sin[$t]) : "";
            if ( $v == "000")
                $z++;
            elseif ( $z > 0 )
                $z--;
                
            if ( ($t == 1) && ($z > 0) && ($int[0] > 0) )
                $r .= ( ($z > 1) ? " de " : "") . $plu[$t];
                
            if ( $r )
                $rt = $rt . ((($i > 0) && ($i <= $fim) && ($int[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
        }
     
        $rt = mb_substr( $rt, 1 );
    
        return($rt ? trim( $rt ) : "zero");
    }

@endphp


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>NOTA DE SAÍDA DE CAIXA</title>

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

        tr th {
            background-color: gray;
            color: white;
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
        <div id="logo" style="padding: 0">
            <img src="images/{{$dados_empresa->logotipo_da_empresa ?? 'log.png' }}" style="width: 150px;height: 150px;position: absolute;top: -40px">
        </div>
        <div id="company">
            <h2 class="name">{{$dados_empresa->nome_empresa ?? ""}}</h2>
            <div>{{$dados_empresa->endereco->rua}}, {{$dados_empresa->endereco->bairro ?? ""}}, <br>
            </div>
            <div>+244 947716133/+244 942364667</div>
            <div><a href="mailto:geral@uma.co.ao">geral@uma.co.ao</a></div>
        </div>
        </div>
    </header>
    <main>
        <h2 style="text-align: left;border-bottom: 1px solid #c2c2c2;padding-bottom: 2px;text-transform: uppercase;font-size: 11pt;font-weight: 100">NOTA DE SAÍDA DE CAIXA</h2>
        <h3 style="text-transform: uppercase;">{{ $dados_empresa->nome_empresa ?? "" }}-Sáida Nº: <strong>{{ $movimento->lancamento_atual ?? "" }}</strong></h3>

        <div style="margin:auto 0px"> </div>
        
        <p style="line-height: 30px;border-bottom: 1px solid #dadada;padding-bottom: 60px"><strong style="">Requisitante:</strong> <br> {{ $movimento->requisitante ?? "" }}  </p>
        
        <p style="line-height: 30px;border-bottom: 1px solid #dadada;padding-bottom: 60px"><strong>Centro de Custo:</strong> <br> {{ $movimento->centro_de_custo->designacao ?? "" }}  </p>
            
        <p style="line-height: 30px;border-bottom: 1px solid #dadada"><strong>Referente à Saída: </strong> 
            @foreach ($movimento->items as $item)
                @if ($item->apresentar == "S")
                <span>{{ $item->descricao ?? "" }}</span>
                @endif
            @endforeach
        </p>
        <p style="line-height: 30px;border-bottom: 1px solid #dadada"><strong>Valor (AKz):</strong> {{ number_format(($movimento->credito ?? 0), 2, '.', ',')  }}</p>
        <p style="line-height: 30px;border-bottom: 1px solid #dadada"><strong>Extenso: </strong> {{ valor_por_extenso($movimento->credito ?? 0) }} kwanzas</p>
            
        <div style="margin:auto 0px"> </div>

    </main>

    <div class="footer margin-top">
                
        <div style="width: 100%;display: inline;white-space: nowrap;">
            <div style="text-align: center;width: 48%;display: inline-block;">
                <h5>Entregou</h5>
                <p>_________________________________</p>
            </div>
            <div style="text-align: center;width: 48%;display: inline-block;">
                <h5>Recebeu</h5>
                <p>_________________________________</p>
            </div>
        </div>
        
        <div style="margin:auto 0px"> </div>
    
        <hr>
        <p style="text-align:right">Data: {{ date('Y-m-d H:i:s') }} </p>

        <div align="center"> Documento processado pelo software MUTUE - Contas Certas.</div>
    </div>

</body>
</html>
