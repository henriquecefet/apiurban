<?php
	$cidade = $_GET['cidade'];
	$hotspots = lerJSON("https://urbanweb.herokuapp.com/apilerhotspot.php?nome=", $cidade);
	$tempo = lerJSON("https://api.hgbrasil.com/weather?key=da6e4d4b&city_name=", $cidade);
	$recomendacaoHoje  = "";
	for($i = 0; $i < count($hotspots["hotspot"]); $i++){
		$hoje = array();
		array_push($hoje, $tempo["results"]["date"]);
		array_push($hoje, $tempo["results"]["description"]);
		if($hotspots["hotspot"][$i]["ar-livre"] == t){
			if($tempo["results"]["description"] == "Tempestestades" || $tempo["results"]["description"] == "Tempestestades Isoldas"){
				$recomendacaoHoje = "Nao recomendado";

			}
			elseif($tempo["results"]["description"] == "Tempo nublado" || $tempo["results"]["description"] == "Trovoadas Dispersas") {
				$recomendacaoHoje  = "Pouco recomendado";
			}
		}
		else{
			$recomendacaoHoje  = "Recomendado";
		}
		$previsao = array();
		array_push($hoje, $recomendacaoHoje);
		$hotspots["hotspot"][$i]["recomedacao"] = $hoje;
		for($j = 1; $j < count($tempo["results"]["forecast"]); $j++){
			$previsaoData = array();
			$diaSemana = $tempo["results"]["forecast"][$j]["weekday"];
			$descricao = $tempo["results"]["forecast"][$j]["description"];
			array_push($previsaoData, $diaSemana." (".$data.")");
			array_push($previsaoData, $descricao);
			if($hotspots["hotspot"][$i]["ar-livre"] == t){
				if($tempo["results"]["forecast"][$j]["condition"] == "storm" ){
					$data = $tempo["results"]["forecast"][$j]["date"];
					array_push($previsaoData, "Nao recomendado");

				}
				elseif($tempo["results"]["forecast"][$j]["condition"] == "cloud" ||  $tempo["results"]["forecast"][$j]["condition"] == "cloudly_day" ){
					array_push($previsaoData, "Pouco recomendado");
				}
			}
			else{
				array_push($previsaoData, "Recomendado");
			}
		}
		$hotspots["hotspot"][$i]["recomedacaoFutura"] = $previsaoData;
	}
	echo json_encode($hotspots);
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
?>