<?php
	$cidade = $_GET['cidade'];
	lerCidade($cidade);
	lerClima($cidade);
	function lerCidade($cidade){
		$url  = "https://urbanweb.herokuapp.com/apilerhotspot.php?nome=".$cidade;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL,$url);
		$result=curl_exec($ch);
		curl_close($ch);
		$dados = json_decode($result, true);
		echo '<pre>'; print_r($dados); echo '</pre>';
		echo "<br>";
	}
	function lerClima($cidade){
		$url  = "https://api.hgbrasil.com/weather?key=da6e4d4b&city_name=".$cidade;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL,$url);
		$result=curl_exec($ch);
		curl_close($ch);
		$dados = json_decode($result, true);
		echo '<pre>'; print_r($dados); echo '</pre>';
		echo "<br>";
	}
?>