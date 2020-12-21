<?php
$cidade = $_GET['cidade'];
pegarHotspots($cidade);
function pegarHotspots($cidade){
	$url  = "https://urbanweb.herokuapp.com/apilerhotspot.php?nome=".$cidade;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL,$url);
	$result=curl_exec($ch);
	curl_close($ch);
	$dados = json_decode($result, true);
	echo $dados;
}

?>