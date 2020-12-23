<?php
	$cidade = $_GET['cidade'];
	$hotspots = lerJSON("https://urbanweb.herokuapp.com/apilerhotspot.php?nome=", $cidade);
	$tempo = lerJSON("https://api.hgbrasil.com/weather?key=da6e4d4b&city_name=", $cidade);
	$recomendacaoHoje  = "";
	for($i = 0; $i < count($hotspots["hotspot"]); $i++){
		if($hotspots["hotspot"][$i]["ar-livre"] == t){
			if($tempo["results"]["description"] == "Tempestestades" || $tempo["results"]["description"] == "Tempestestades Isoldas"){
				$recomendacaoHoje = "Não recomendado";
			}
			elseif($tempo["results"]["description"] == "Tempo nublado" || $tempo["results"]["description"] == "Trovoadas Dispersas") {
				$recomendacaoHoje  = "Pouco recomendado";
			}
		}
		else{
			$recomendacaoHoje  = "Recomendado";
		}
		$previsao = array();
		$hotspots["hotspot"][$i]["recomedacao"] = $recomendacaoHoje;
		for($j = 0; $j < count($tempo["results"]["forecast"]); $j++)){
			if($hotspots["hotspot"][$i]["ar-livre"] == t){
				if($tempo["results"]["forecast"][$j]["condition"] == "storm" ){
					array.push($previsao, "Não recomendado");
				}
				elseif($tempo["results"]["forecast"][$j]["condition"] == "cloud" ||  $tempo["results"]["forecast"][$j]["condition"] == "cloudly_day" ){
					array.push($previsao, "Pouco recomendado");
				}
			}
			else{
				array.push($previsao, "Recomendado");
			}
		}
		$hotspots["hotspot"][$i]["recomedacaoFutura"] = $previsao;
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