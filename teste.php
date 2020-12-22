<?php
	$cidade = $_GET['cidade'];
	lerCidade($cidade);
	lerClima($cidade);
	$hotspots = lerJSON("https://urbanweb.herokuapp.com/apilerhotspot.php?nome=", $cidade);
	$tempo = lerJSON("https://api.hgbrasil.com/weather?key=da6e4d4b&city_name=", $cidade);
	echo '<pre>'; print_r($hotspots); echo '</pre>'; echo "<br>";
	echo "<br>";
	echo '<pre>'; print_r($tempo); echo '</pre>'; echo "<br>";

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