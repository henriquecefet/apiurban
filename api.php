<?php
    include("conexao.php");
    $funcao = $_GET['funcao'];
    switch ($funcao) {
        case "hotspotsClima":
            hotspotsClima();
            break;
        case "retornaHotspot":
            retornaHotspot();
            break;
        case "atualizarDados":
            atualizarDados();
            break;
        case "hotspotsCovid":
            hotspotsCovid();
            break;
        case "covidHotspot":
            covidHotspot();
            break;
    }
    // A função abaixo recebe o nome de uma cidade e retorna uma lista de hotspots e sua recomendação baseada no clima de hoje e da previsão para os 9 dias seguintes.
   function hotspotsClima(){
      $cidade = $_GET['cidade'];
      $hotspots = lerJSON("https://urbanweb.herokuapp.com/apilerhotspot.php?nome=", $cidade);
      $tempo = lerJSON("https://api.hgbrasil.com/weather?key=da6e4d4b&city_name=", $cidade);
      $recomendacaoHoje  = "";
      for($i = 0; $i < count($hotspots["hotspot"]); $i++){
         $hoje = array();
         array_push($hoje, $tempo["results"]["date"]);
         array_push($hoje, $tempo["results"]["description"]);
         if($hotspots["hotspot"][$i]["ar-livre"] == t){
            $condicaoHoje = $tempo["results"]["forecast"][0]["condition"];
            if($condicaoHoje == "storm" || $condicaoHoje == "hail" || $condicaoHoje == "rain" ){
               $recomendacaoHoje = "Nao recomendado";

            }
            elseif($condicaoHoje =="cloud" || $condicaoHoje =="cloudly_day"||$condicaoHoje =="fog"|| $condicaoHoje=="cloudly_night" ) {
               $recomendacaoHoje  = "Pouco recomendado";
            }
         }
         elseif($hotspots["hotspot"][$i]["ar-livre"] == f){
            $recomendacaoHoje  = "Recomendado";
         }
         $previsao = array();
         array_push($hoje, $recomendacaoHoje);
         $hotspots["hotspot"][$i]["recomedacao"] = $hoje;
         for($j = 1; $j < count($tempo["results"]["forecast"]); $j++){
            $previsaoData = array();
            $diaSemana = $tempo["results"]["forecast"][$j]["weekday"];
            if($diaSemana == "Sáb"){
               $diaSemana = str_replace("Sáb", "Sab",$diaSemana);
            }
            $descricao = $tempo["results"]["forecast"][$j]["description"];
            $data = $tempo["results"]["forecast"][$j]["date"];
            array_push($previsaoData, $diaSemana." (".$data.")");
            array_push($previsaoData, $descricao);
            $condicao = $tempo["results"]["forecast"][$j]["condition"];
            if($hotspots["hotspot"][$i]["ar-livre"] == t){ 
               if($condicao == "storm" || $condicao == "hail" || $condicao == "rain" ){
                  array_push($previsaoData, "Nao recomendado");

               }
               elseif($condicao == "cloud" ||  $condicao == "cloudly_day" || $condicao == "fog" ||  $condicao == "cloudly_night" ){
                  array_push($previsaoData, "Pouco recomendado");
               }
            }
            elseif($hotspots["hotspot"][$i]["ar-livre"] == f){
               array_push($previsaoData, "Recomendado");
            }
            array_push($previsao, $previsaoData);
         }
         $hotspots["hotspot"][$i]["recomedacaoFutura"] = $previsao;
      }
      echo json_encode($hotspots);
   }

   function retornaHotspot(){
        $nome = $_GET["nome"];
        $hotspot = lerJSON("https://urbanweb.herokuapp.com/apilerumhotspot.php?nome=", $nome);
        $tempo = lerJSON("https://api.hgbrasil.com/weather?key=da6e4d4b&city_name=", $hotspot[0]["cidade"]);
        $recomendacaoHoje  = "";
        $hoje = array();
        array_push($hoje, $tempo["results"]["date"]);
        array_push($hoje, $tempo["results"]["description"]);
        if($hotspot["hotspot"][0]["ar-livre"] == t){
            $condicaoHoje = $tempo["results"]["forecast"][0]["condition"];
            if($condicaoHoje == "storm" || $condicaoHoje == "hail" || $condicaoHoje == "rain" ){
                $recomendacaoHoje = "Nao recomendado";

            }
            elseif($condicaoHoje =="cloud" || $condicaoHoje =="cloudly_day"||$condicaoHoje =="fog"|| $condicaoHoje=="cloudly_night" ) {
                $recomendacaoHoje  = "Pouco recomendado";
            }
        }
        elseif($hotspot["hotspot"][0]["ar-livre"] == f){
            $recomendacaoHoje  = "Recomendado";
        }
        $previsao = array();
        array_push($hoje, $recomendacaoHoje);
        $hotspot[0]["recomedacao"] = $hoje;
        for($j = 1; $j < count($tempo["results"]["forecast"]); $j++){
            $previsaoData = array();
            $diaSemana = $tempo["results"]["forecast"][$j]["weekday"];
            if($diaSemana == "Sáb"){
                $diaSemana = str_replace("Sáb", "Sab",$diaSemana);
            }
            $descricao = $tempo["results"]["forecast"][$j]["description"];
            $data = $tempo["results"]["forecast"][$j]["date"];
            array_push($previsaoData, $diaSemana." (".$data.")");
            array_push($previsaoData, $descricao);
            $condicao = $tempo["results"]["forecast"][$j]["condition"];
            if($hotspot["hotspot"][0]["ar-livre"] == t){
                if($condicao == "storm" || $condicao == "hail" || $condicao == "rain" ){
                    array_push($previsaoData, "Nao recomendado");
                }
                elseif($condicao == "cloud" ||  $condicao == "cloudly_day" || $condicao == "fog" ||  $condicao == "cloudly_night" ){
                    array_push($previsaoData, "Pouco recomendado");
                }
            }
            elseif($hotspot["hotspot"][0]["ar-livre"] == f){
                array_push($previsaoData, "Recomendado");
            }
            array_push($previsao, $previsaoData);
        }
        $hotspot[0]["recomedacaoFutura"] = $previsao;
        echo json_encode($hotspot);
    }

   function lerJSON($link, $cidade){
      $url  = $link."".urlencode($cidade);
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_URL,$url);
      $result=curl_exec($ch);
      curl_close($ch);
      $dados = json_decode($result, true);
      return $dados;
   }
   function atualizarDados(){
    $cidades = lerJSON("https://urbanweb.herokuapp.com/apilervariascidades.php", "");
    for($i = 0; $i < count($cidades["cidades"]); $i++){
       atualizarDadosCovid($cidades["cidades"][$i]["nome"]);
    }
   }

   function atualizarDadosCovid($cidade){
      $host        = "host = ec2-23-20-129-146.compute-1.amazonaws.com";
      $port        = "port = 5432";
      $dbname      = "dbname = d4lbqqmnpeve28";
      $credentials = "user = zwifcqhcjeiokg password=1ff276855a41e7c3da65d0eabb32545502930e1e2f8250dad7b389adbd09cbcf";
      $db = pg_connect( "$host $port $dbname $credentials"  );
      $city = lerJSON("https://urbanweb.herokuapp.com/apilercidade.php?cidade=", $cidade);
      if($city["cidades"][0]["pais"]=="brazil"){
         $estado = $city["cidades"][0]["estado"];
         $covid = lerJSON("https://covid19-brazil-api.now.sh/api/report/v1/brazil/uf/", $estado);
         $pais = $city["cidades"][0]["pais"];
         $casos = $covid["cases"];
         $mortes = $covid["deaths"];
         $sql =<<<EOF
           SELECT dados('$estado', '$pais', $casos, $mortes);
EOF;
          $ret = pg_query($db, $sql);
          if(!$ret) {
               echo pg_last_error($db);
           exit;
          }
          else{
            echo "sucesso";
          }
        }
        else{
          $covid = lerJSON("https://covid19-brazil-api.now.sh/api/report/v1/", $city["cidades"][0]["pais"]);
          $estado = $city["cidades"][0]["estado"];
          $pais = $city["cidades"][0]["pais"];
          $casos = $covid["data"]["cases"];
          $mortes = $covid["data"]["deaths"];
          $sql =<<<EOF
             SELECT dados('$estado', '$pais', $casos, $mortes);
EOF;
          $ret = pg_query($db, $sql);
          if(!$ret) {
               echo pg_last_error($db);
           exit;
          }
          else{
            echo "sucesso";
          }
      }
   }
   function hotspotsCovid(){  
    $cidade = $_GET['cidade'];
    $city = lerJSON("https://urbanweb.herokuapp.com/apilercidade.php?cidade=", $cidade);
    $crescimento_casos = chamarFuncaoSQL("getcasos", $city["cidades"][0]["estado"]);
    $crescimento_mortes = chamarFuncaoSQL("getmortes", $city["cidades"][0]["estado"]);
    $hotspots = lerJSON("https://urbanweb.herokuapp.com/apilerhotspot.php?nome=", $cidade);
    $hotspots["situacao_covid"]["crescimento_casos"] = round($crescimento_casos*100, 4);
    $hotspots["situacao_covid"]["crescimento_mortes"] = round($crescimento_mortes*100, 4);
    for($i = 0; $i < count($hotspots["hotspot"]); $i++){
      if($hotspots["hotspot"][$i]["ar-livre"] == f){
        if($crescimento_casos > 0.01 || $crescimento_mortes > 0.01){
          $hotspots["hotspot"][$i]["recomendacao"] = "Nao recomendado";
        }
        else{
          $hotspots["hotspot"][$i]["recomendacao"] = "Recomendado";
        }
      }
      else{
        $hotspots["hotspot"][$i]["recomendacao"] = "Recomendado";
      }
    }
    echo json_encode($hotspots);
   }
   function covidHotspot(){
    $nome = $_GET["nome"];
    $hotspot = lerJSON("https://urbanweb.herokuapp.com/apilerumhotspot.php?nome=", $nome);
    $city = lerJSON("https://urbanweb.herokuapp.com/apilercidade.php?cidade=", $hotspot[0]["cidade"]);
    $crescimento_casos = chamarFuncaoSQL("getcasos", $city["cidades"][0]["estado"]);
    $crescimento_mortes = chamarFuncaoSQL("getmortes", $city["cidades"][0]["estado"]);
    $hotspot[0]["situacao_covid"]["crescimento_casos"] = round($crescimento_casos*100, 4);
    $hotspot[0]["situacao_covid"]["crescimento_mortes"] = round($crescimento_mortes*100, 4);
    if($hotspot["hotspot"][0]["ar-livre"] == f){
      if($crescimento_casos > 0.01 || $crescimento_mortes > 0.01){
        $hotspot[0]["recomendacao"] = "Nao recomendado";
      }
      else{
        $hotspot[0]["recomendacao"] = "Recomendado";
      }
    }
    else{
      $hotspot[0]["recomendacao"] = "Recomendado";
    }
    echo json_encode($hotspot);
   }
   function chamarFuncaoSQL($funcao, $estado){
    $host        = "host = ec2-23-20-129-146.compute-1.amazonaws.com";
    $port        = "port = 5432";
    $dbname      = "dbname = d4lbqqmnpeve28";
    $credentials = "user = zwifcqhcjeiokg password=1ff276855a41e7c3da65d0eabb32545502930e1e2f8250dad7b389adbd09cbcf";
    $db = pg_connect( "$host $port $dbname $credentials"  );
    switch ($funcao) {
       case "getcasos":
           $sql =<<<EOF
           SELECT getcasos('$estado');
EOF;
          $ret = pg_query($db, $sql);
          if(!$ret) {
               echo pg_last_error($db);
           exit;
          }
          $casos = 0;
          while($row = pg_fetch_row($ret)) {
            $casos = $row[0];
          }
          return $casos;
          break;
      case "getmortes":
        $sql =<<<EOF
           SELECT getmortes('$estado');
EOF;
          $ret = pg_query($db, $sql);
          if(!$ret) {
               echo pg_last_error($db);
           exit;
          }
          $mortes = 0;
          while($row = pg_fetch_row($ret)) {
            $mortes = $row[0];
          }
          return $mortes;
          break;
   
   }
 }
?>