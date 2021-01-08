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
        case " atualizarDadosCovid":
            atualizarDadosCovid();
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
   function atualizarDadosCovid(){
      $cidade = $_GET['cidade'];
      $city = lerJSON("https://urbanweb.herokuapp.com/apilercidade.php?cidade=", $cidade);
      if($city[0]["pais"]=="brazil"){
         $estado = $city[0]["estado"]
         $covid = lerJSON("https://covid19-brazil-api.now.sh/api/report/v1/brazil/uf/", $estado);
         print_r($covid);
      }
      else{
        $covid = lerJSON("https://covid19-brazil-api.now.sh/api/report/v1/", $city[0]["pais"]);
        print_r($covid);
      }
     
   }
?>