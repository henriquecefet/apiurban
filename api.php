<?php
include("conexao.php");
header("Content-type: text/html; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
$GLOBALS['taxaFechadaAlta'] = 0.01;
$GLOBALS['taxaFechadaMedia'] = 0.006;
$GLOBALS['taxaAbertaAlta'] = 0.02;
$GLOBALS['taxaAbertaMedia'] = 0.012;
if(isset($_GET['funcao'])){
    $funcao = $_GET['funcao'];
    switch ($funcao) {
        case "recomendarCidadeClima":
            recomendarCidadeClima();
            break;
        case "recomendarHotspotClima":
            recomendarHotspotClima();
            break;
        case "atualizarDados":
            atualizarDados();
            break;
        case "recomendarCidadeCovid":
            recomendarCidadeCovid();
            break;
        case "recomendarHotspotCovid":
            recomendarHotspotCovid();
            break;
        case "recomendarCidadeClimaCovid":
            recomendarCidadeClimaCovid();
            break;
        case "recomendarHotspotClimaCovid":
            recomendarHotspotClimaCovid();
            break;
    }
}
else{
    http_response_code(404);
}
// A função abaixo recebe o nome de uma cidade e retorna uma lista de hotspots e sua recomendação baseada no clima de hoje e da previsão para os 9 dias seguintes.
function recomendarCidadeClima(){
    if(isset($_GET['cidade'])){
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
                    $recomendacaoHoje = "Não recomendado";
                }
                elseif($condicaoHoje =="cloud" ||$condicaoHoje =="fog" ) {
                    $recomendacaoHoje  = "Pouco recomendado";
                }
                else{
                  $recomendacaoHoje  = "Recomendado";
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
                        array_push($previsaoData, "Não recomendado");
                    }
                    elseif($condicao == "cloud" || $condicao == "fog" ){
                        array_push($previsaoData, "Pouco recomendado");
                    }
                    else{
                      array_push($previsaoData, "Recomendado");
                    }
                }
                elseif($hotspots["hotspot"][$i]["ar-livre"] == f){
                    array_push($previsaoData, "Recomendado");
                }
                array_push($previsao, $previsaoData);
            }
            $hotspots["hotspot"][$i]["recomedacaoFutura"] = $previsao;
        }
        http_response_code(200);
        echo json_encode($hotspots, JSON_UNESCAPED_UNICODE);
    }
    else{
        http_response_code(404);
    }
}

function recomendarHotspotClima(){
    if(isset($_GET["nome"])){
        $nome = $_GET["nome"];
        $hotspot = lerJSON("https://urbanweb.herokuapp.com/apilerumhotspot.php?nome=", $nome);
        $tempo = lerJSON("https://api.hgbrasil.com/weather?key=da6e4d4b&city_name=", $hotspot["hotspot"][0]["cidade"]);
        $recomendacaoHoje  = "";
        $hoje = array();
        array_push($hoje, $tempo["results"]["date"]);
        array_push($hoje, $tempo["results"]["description"]);
        if($hotspot["hotspot"][0]["ar-livre"] == t){
            $condicaoHoje = $tempo["results"]["forecast"][0]["condition"];
            if($condicaoHoje == "storm" || $condicaoHoje == "hail" || $condicaoHoje == "rain" ){
                $recomendacaoHoje = "Não recomendado";

            }
            elseif($condicaoHoje =="cloud" ||$condicaoHoje =="fog" ) {
                $recomendacaoHoje  = "Pouco recomendado";
            }
            else{
                  $recomendacaoHoje  = "Recomendado";
            }
        }
        elseif($hotspot["hotspot"][0]["ar-livre"] == f){
            $recomendacaoHoje  = "Recomendado";
        }
        $previsao = array();
        array_push($hoje, $recomendacaoHoje);
        $hotspot["hotspot"][0]["recomendacao"] = $hoje;
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
                    array_push($previsaoData, "Não recomendado");
                }
                elseif($condicao == "cloud"  || $condicao == "fog" ){
                    array_push($previsaoData, "Pouco recomendado");
                }
                else{
                    array_push($previsaoData, "Recomendado");
                }
            }
            elseif($hotspot["hotspot"][0]["ar-livre"] == f){
                array_push($previsaoData, "Recomendado");
            }
            array_push($previsao, $previsaoData);
        }
        $hotspot["hotspot"][0]["recomendacaoFutura"] = $previsao;
        http_response_code(200);
        echo json_encode($hotspot, JSON_UNESCAPED_UNICODE);
    }
    else{
        http_response_code(404);
    }
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
    $estados = array();
    for($i = 0; $i < count($cidades["cidades"]); $i++){
        if(!in_array($cidades["cidades"][$i]["estado"], $estados)){
            atualizarDadosCovid($cidades["cidades"][$i]["nome"]);
            array_push($estados, $cidades["cidades"][$i]["estado"]);
        }
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
function recomendarCidadeCovid(){
    if(isset($_GET['cidade'])){
        $cidade = $_GET['cidade'];
        $city = lerJSON("https://urbanweb.herokuapp.com/apilercidade.php?cidade=", $cidade);
        $crescimento_casos = chamarFuncaoSQL("getcasos", $city["cidades"][0]["estado"]);
        $crescimento_mortes = chamarFuncaoSQL("getmortes", $city["cidades"][0]["estado"]);
        $hotspots = lerJSON("https://urbanweb.herokuapp.com/apilerhotspot.php?nome=", $cidade);
        $hotspots["situacao_covid"]["crescimento_casos"] = round($crescimento_casos*100, 4);
        $hotspots["situacao_covid"]["crescimento_mortes"] = round($crescimento_mortes*100, 4);
        for($i = 0; $i < count($hotspots["hotspot"]); $i++){
            if($hotspots["hotspot"][$i]["ar-livre"] == f){
                if($crescimento_casos > $GLOBALS['taxaFechadaAlta'] || $crescimento_mortes > $GLOBALS['taxaFechadaAlta']){
                    $hotspots["hotspot"][$i]["recomendacao"] = "Não recomendado";
                }
                elseif($crescimento_casos >$GLOBALS['taxaFechadaMedia'] || $crescimento_mortes >$GLOBALS['taxaFechadaMedia']){
                    $hotspots["hotspot"][$i]["recomendacao"] = "Pouco recomendado";
                }
                else{
                    $hotspots["hotspot"][$i]["recomendacao"] = "Recomendado";
                }
            }
            else{
                if($crescimento_casos > $GLOBALS['taxaAbertaAlta'] || $crescimento_mortes > $GLOBALS['taxaAbertaAlta']){
                    $hotspots["hotspot"][$i]["recomendacao"] = "Não recomendado";
                }
                elseif($crescimento_casos > $GLOBALS['taxaFechadaAlta'] || $crescimento_mortes > $GLOBALS['taxaFechadaAlta']){
                    $hotspots["hotspot"][$i]["recomendacao"] = "Pouco recomendado";
                }
                else{
                    $hotspots["hotspot"][$i]["recomendacao"] = "Recomendado";
                }
            }
        }
        http_response_code(200);
        echo json_encode($hotspots, JSON_UNESCAPED_UNICODE);
    }
    else{
        http_response_code(404);
    }
}
function recomendarHotspotCovid(){
    if(isset($_GET['nome'])){
        $nome = $_GET["nome"];
        $hotspot = lerJSON("https://urbanweb.herokuapp.com/apilerumhotspot.php?nome=", $nome);
        $city = lerJSON("https://urbanweb.herokuapp.com/apilercidade.php?cidade=", $hotspot["hotspot"][0]["cidade"]);
        $crescimento_casos = chamarFuncaoSQL("getcasos", $city["cidades"][0]["estado"]);
        $crescimento_mortes = chamarFuncaoSQL("getmortes", $city["cidades"][0]["estado"]);
        $hotspot["hotspot"][0]["situacao_covid"]["crescimento_casos"] = round($crescimento_casos*100, 4);
        $hotspot["hotspot"][0]["situacao_covid"]["crescimento_mortes"] = round($crescimento_mortes*100, 4);
        if($hotspot["hotspot"][0]["ar-livre"] == f){
            if($crescimento_casos > $GLOBALS['taxaFechadaAlta'] || $crescimento_mortes > $GLOBALS['taxaFechadaAlta']){
                $hotspot["hotspot"][0]["recomendacao"] = "Não recomendado";
            }
            elseif($crescimento_casos >$GLOBALS['taxaFechadaMedia'] || $crescimento_mortes >$GLOBALS['taxaFechadaMedia']){
                $hotspot["hotspot"][0]["recomendacao"] = "Pouco recomendado";
            }
            else{
                $hotspot["hotspot"][0]["recomendacao"] = "Recomendado";
            }
        }
        else{
            if($crescimento_casos > $GLOBALS['taxaAbertaAlta'] || $crescimento_mortes > $GLOBALS['taxaAbertaAlta']){
                $hotspot["hotspot"][0]["recomendacao"] = "Não recomendado";
            }
            elseif($crescimento_casos > $GLOBALS['taxaFechadaAlta'] || $crescimento_mortes > $GLOBALS['taxaFechadaAlta']){
                $hotspot["hotspot"][0]["recomendacao"] = "Pouco recomendado";
            }
            else{
                $hotspot["hotspot"][0]["recomendacao"] = "Recomendado";
            }
        }
        http_response_code(200);
        echo json_encode($hotspot, JSON_UNESCAPED_UNICODE);
    }
    else{
        http_response_code(404);
    }
}
function chamarFuncaoSQL($funcao, $estado){
    $host = "host = ec2-23-20-129-146.compute-1.amazonaws.com";
    $port = "port = 5432";
    $dbname = "dbname = d4lbqqmnpeve28";
    $credentials = "user = zwifcqhcjeiokg password=1ff276855a41e7c3da65d0eabb32545502930e1e2f8250dad7b389adbd09cbcf";
    $db = pg_connect("$host $port $dbname $credentials");
    switch ($funcao) {
        case "getcasos":
            $sql = <<<EOF
   SELECT getcasos('$estado');
EOF;
            $ret = pg_query($db, $sql);
            if (!$ret) {
                echo pg_last_error($db);
                exit;
            }
            $casos = 0;
            while ($row = pg_fetch_row($ret)) {
                $casos = $row[0];
            }
            return $casos;
            break;
        case "getmortes":
            $sql = <<<EOF
  SELECT getmortes('$estado');
EOF;
            $ret = pg_query($db, $sql);
            if (!$ret) {
                echo pg_last_error($db);
                exit;
            }
            $mortes = 0;
            while ($row = pg_fetch_row($ret)) {
                $mortes = $row[0];
            }
            return $mortes;
            break;

    }
}
    function recomendarCidadeClimaCovid(){
        if (isset ($_GET ['cidade'])) {
            $cidade = $_GET ['cidade'];
            $hotspots = lerJSON("https://urbanweb.herokuapp.com/apilerhotspot.php?nome=", $cidade);
            $tempo = lerJSON("https://api.hgbrasil.com/weather?key=da6e4d4b&city_name=", $cidade);
            $recomendacaoHoje = "";
            $city = lerJSON("https://urbanweb.herokuapp.com/apilercidade.php?cidade=", $cidade);
            $crescimento_casos = chamarFuncaoSQL("getcasos", $city["cidades"][0]["estado"]);
            $crescimento_mortes = chamarFuncaoSQL("getmortes", $city ["cidades"] [0] ["estado"]);
            $hotspots["situacao_covid"]["crescimento_casos"] = round($crescimento_casos * 100, 4);
            $hotspots["situacao_covid"]["crescimento_mortes"] = round($crescimento_mortes * 100, 4);
            for ($i = 0; $i < count($hotspots["hotspot"]); $i++) {
                $hoje = array();
                array_push($hoje, $tempo["results"]["date"]);
                array_push($hoje, $tempo["results"]["description"]);
                if ($hotspots["hotspot"][$i]["ar-livre"] == t) {
                    $condicaoHoje = $tempo["results"]["forecast"][0]["condition"];
                    if ($condicaoHoje == "storm" || $condicaoHoje == "hail" || $condicaoHoje == "rain") {
                        $recomendacaoHoje = "Não recomendado";

                    } elseif($condicaoHoje == "cloud" || $condicaoHoje == "fog") {
                        $recomendacaoHoje = "Pouco recomendado";
                    }
                    else{
                      $recomendacaoHoje  = "Recomendado";
                    }
                    if($crescimento_casos > $GLOBALS['taxaAbertaAlta'] || $crescimento_mortes > $GLOBALS['taxaAbertaAlta']) {
                        $hotspots["hotspot"][$i]["recomendacao"] = "Não recomendado";
                    }elseif ($crescimento_casos > $GLOBALS['taxaFechadaAlta'] || $crescimento_mortes > $GLOBALS['taxaFechadaAlta']) {
                        $hotspots["hotspot"][$i]["recomendacao"] = "Pouco recomendado";
                    }else {
                        $hotspots["hotspot"][$i]["recomendacao"] = "Recomendado";
                    }
                    if($recomendacaoHoje == "Não recomendado" && $hotspots["hotspot"][$i]["recomendacao"] == "Pouco recomendado") {
                        $hotspots["hotspot"][$i]["recomendacao"] = "Não recomendado";
                    } elseif($recomendacaoHoje == "Não recomendado" && $hotspots["hotspot"][$i]["recomendacao"] == "Recomendado") {
                        $hotspots["hotspot"][$i]["recomendacao"] = "Não recomendado";
                    } elseif($recomendacaoHoje == "Pouco recomendado" && $hotspots["hotspot"][$i]["recomendacao"] == "Recomendado") {
                        $hotspots["hotspot"][$i]["recomendacao"] = "Pouco recomendado";
                    }
                } elseif ($hotspots["hotspot"][$i]["ar-livre"] == f) {
                    if($crescimento_casos > $GLOBALS['taxaFechadaAlta'] || $crescimento_mortes > $GLOBALS['taxaFechadaAlta']) {
                        $hotspots["hotspot"][$i]["recomendacao"] = "Não recomendado";
                    }elseif ($crescimento_casos > $GLOBALS['taxaFechadaMedia'] || $crescimento_mortes > $GLOBALS['taxaFechadaMedia']) {
                        $hotspots["hotspot"][$i]["recomendacao"] = "Pouco recomendado";
                    }else {
                        $hotspots["hotspot"][$i]["recomendacao"] = "Recomendado";
                    }
                }
                $previsao = array();
                array_push($hoje, $recomendacaoHoje);
                $hotspots["hotspot"][$i]["recomendacao"] = $hoje;
                for ($j = 1; $j < count($tempo["results"]["forecast"]); $j++) {
                    $previsaoData = array();
                    $diaSemana = $tempo["results"]["forecast"][$j]["weekday"];
                    if ($diaSemana == "Sáb") {
                        $diaSemana = str_replace("Sáb", "Sab", $diaSemana);
                    }
                    $descricao = $tempo["results"]["forecast"][$j]["description"];
                    $data = $tempo["results"]["forecast"][$j]["date"];
                    array_push($previsaoData, $diaSemana . " (" . $data . ")");
                    array_push($previsaoData, $descricao);
                    $recomendacaoData = "";
                    $recomendacaoCovid = "";
                    $recomendacaoGeral = "";
                    $condicao = $tempo["results"]["forecast"][$j]["condition"];
                    if ($hotspots["hotspot"][$i]["ar-livre"] == t) {
                        if ($condicao == "storm" || $condicao == "hail" || $condicao == "rain") {
                            $recomendacaoData = "Não recomendado";
                        } elseif ($condicao == "cloud"  || $condicao == "fog") {
                            $recomendacaoData = "Pouco recomendado";
                        }
                         else{
                            $recomendacaoData = "Recomendado";
                        }
                        if($crescimento_casos > $GLOBALS['taxaAbertaAlta'] || $crescimento_mortes > $GLOBALS['taxaAbertaAlta']) {
                          $recomendacaoCovid = "Não recomendado";
                        }elseif ($crescimento_casos > $GLOBALS['taxaFechadaAlta'] || $crescimento_mortes > $GLOBALS['taxaFechadaAlta']) {
                            $recomendacaoCovid = "Pouco recomendado";
                        }else {
                            $recomendacaoCovid = "Recomendado";
                        }
                        if($recomendacaoData == "Não recomendado" && $recomendacaoCovid == "Pouco recomendado") {
                            $recomendacaoGeral = "Não recomendado";
                        } elseif($recomendacaoData == "Não recomendado" && $recomendacaoCovid == "Recomendado") {
                             $recomendacaoGeral = "Não recomendado";
                        } elseif($recomendacaoData == "Pouco recomendado" && $recomendacaoCovid == "Recomendado") {
                             $recomendacaoGeral = "Pouco recomendado";
                        }
                        else{
                            $recomendacaoGeral = $recomendacaoData;
                        }
                        array_push($previsaoData,  $recomendacaoData);
                    } elseif ($hotspots["hotspot"][$i]["ar-livre"] == f) {
                       if($crescimento_casos > $GLOBALS['taxaFechadaAlta'] || $crescimento_mortes > $GLOBALS['taxaFechadaAlta']) {
                          array_push($previsaoData, "Não recomendado");
                        }elseif ($crescimento_casos > $GLOBALS['taxaFechadaMedia'] || $crescimento_mortes > $GLOBALS['taxaFechadaMedia']) {
                              array_push($previsaoData, "Pouco recomendado");
                        }else {
                             array_push($previsaoData, "Recomendado");
                        }
                    }
                    array_push($previsao, $previsaoData);
                }
                $hotspots["hotspot"][$i]["recomendacaoFutura"] = $previsao;
            }
            http_response_code(200);
            echo json_encode($hotspots, JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(404);
        }
    }
    function recomendarHotspotClimaCovid(){
      if (isset ($_GET ["nome"])) {
        $nome = $_GET["nome"];
        $hotspot = lerJSON("https://urbanweb.herokuapp.com/apilerumhotspot.php?nome=", $nome);
        $tempo = lerJSON("https://api.hgbrasil.com/weather?key=da6e4d4b&city_name=", $hotspot["hotspot"][0]["cidade"]);
        $recomendacaoHoje  = "";
        $recomendacaoCovidHoje  = "";
        $hoje = array();
        array_push($hoje, $tempo["results"]["date"]);
        array_push($hoje, $tempo["results"]["description"]);
        $city = lerJSON("https://urbanweb.herokuapp.com/apilercidade.php?cidade=", $hotspot["hotspot"][0]["cidade"]);
        $crescimento_casos = chamarFuncaoSQL("getcasos", $city["cidades"][0]["estado"]);
        $crescimento_mortes = chamarFuncaoSQL("getmortes", $city["cidades"][0]["estado"]);
        $hotspot["hotspot"][0]["situacao_covid"]["crescimento_casos"] = round($crescimento_casos*100, 4);
        $hotspot["hotspot"][0]["situacao_covid"]["crescimento_mortes"] = round($crescimento_mortes*100, 4);
        if($hotspot["hotspot"][0]["ar-livre"] == t){
            $condicaoHoje = $tempo["results"]["forecast"][0]["condition"];
            if($condicaoHoje == "storm" || $condicaoHoje == "hail" || $condicaoHoje == "rain" ){
                $recomendacaoHoje = "Não recomendado";

            }
            elseif($condicaoHoje =="cloud" || $condicaoHoje =="fog" ) {
                $recomendacaoHoje  = "Pouco recomendado";
            }
            else{
                $recomendacaoHoje  = "Recomendado";
            }
            if($crescimento_casos > $GLOBALS['taxaAbertaAlta'] || $crescimento_mortes > $GLOBALS['taxaAbertaAlta']) {
                $recomendacaoCovidHoje = "Não recomendado";
            }elseif ($crescimento_casos > $GLOBALS['taxaFechadaAlta'] || $crescimento_mortes > $GLOBALS['taxaFechadaAlta']) {
                $recomendacaoCovidHoje = "Pouco recomendado";
            }
            else {
                $recomendacaoCovidHoje = "Recomendado";
            }
            if($recomendacaoHoje == "Não recomendado" && $recomendacaoCovidHoje == "Pouco recomendado"){
                   $recomendacaoHoje = "Não recomendado";
            } elseif($recomendacaoHoje == "Não recomendado" && $recomendacaoCovidHoje == "Recomendado") {
                $recomendacaoHoje = "Não recomendado";
            } elseif($recomendacaoHoje == "Pouco recomendado" && $recomendacaoCovidHoje == "Recomendado") {
                $recomendacaoHoje = "Pouco recomendado";
            }
        }
        elseif($hotspot["hotspot"][0]["ar-livre"] == f){
            if($crescimento_casos > $GLOBALS['taxaFechadaAlta'] || $crescimento_mortes > $GLOBALS['taxaFechadaAlta']) {
                 $recomendacaoHoje  = "Não recomendado";
            }elseif ($crescimento_casos > $GLOBALS['taxaFechadaMedia'] || $crescimento_mortes > $GLOBALS['taxaFechadaMedia']) {
                 $recomendacaoHoje  = "Pouco recomendado";
            }else {
                 $recomendacaoHoje  = "Recomendado";
            }
        }
        $previsao = array();
        array_push($hoje, $recomendacaoHoje);
        $hotspot["hotspot"][0]["recomendacao"] = $hoje;
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
            $recomendacaoData = "";
            $recomendacaoCovid = "";
            $recomendacaoGeral = "";
            $condicao = $tempo["results"]["forecast"][$j]["condition"];
            if ($hotspot["hotspot"][0]["ar-livre"] == t) { 
                if ($condicao == "storm" || $condicao == "hail" || $condicao == "rain") {
                    $recomendacaoData = "Não recomendado";
                } elseif ($condicao == "cloud"  || $condicao == "fog" ) {
                    $recomendacaoData = "Pouco recomendado";
                }
                else{
                    $recomendacaoData = "Recomendado";
                }
                if($crescimento_casos > $GLOBALS['taxaAbertaAlta'] || $crescimento_mortes > $GLOBALS['taxaAbertaAlta']) {
                    $recomendacaoCovid = "Não recomendado";
                }elseif ($crescimento_casos > $GLOBALS['taxaFechadaAlta'] || $crescimento_mortes > $GLOBALS['taxaFechadaAlta']) {
                    $recomendacaoCovid = "Pouco recomendado";
                }else {
                    $recomendacaoCovid = "Recomendado";
                }
                if($recomendacaoData == "Não recomendado" && $recomendacaoCovid == "Pouco recomendado") {
                    $recomendacaoGeral = "Não recomendado";
                } elseif($recomendacaoData == "Não recomendado" && $recomendacaoCovid == "Recomendado") {
                    $recomendacaoGeral = "Não recomendado";
                } elseif($recomendacaoData == "Pouco recomendado" && $recomendacaoCovid == "Recomendado") {
                    $recomendacaoGeral = "Pouco recomendado";
                }
                array_push($previsaoData,  $recomendacaoData);
            } elseif ($hotspot["hotspot"][0]["ar-livre"] == f) {
                if($crescimento_casos > $GLOBALS['taxaFechadaAlta'] || $crescimento_mortes > $GLOBALS['taxaFechadaAlta']) {
                    array_push($previsaoData, "Não recomendado");
                }elseif ($crescimento_casos > $GLOBALS['taxaFechadaMedia'] || $crescimento_mortes > $GLOBALS['taxaFechadaMedia']) {
                    array_push($previsaoData, "Pouco recomendado");
                  }else {
                      array_push($previsaoData, "Recomendado");
                   }
             }
        array_push($previsao, $previsaoData);
        }
        $hotspot["hotspot"][0]["recomendacaoFutura"] = $previsao;
        http_response_code(200);
        echo json_encode($hotspot, JSON_UNESCAPED_UNICODE);
      }
      else{
        http_response_code(404);
      }
    }
?>