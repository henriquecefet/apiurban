<?php
$db;
$host        = "ec2-52-201-59-194.compute-1.amazonaws.com";
$port        = "port = 5432";
$dbname      = "dbname = d6pg29oggq3lk3";
$credentials = "user = jptkmcecjrktsz password=afe78295478b7ba5cf4f94d0107b2dc9df6451c132d051725ac22540f2e5023f";
$db = pg_connect( "$host $port $dbname $credentials"  );
	if(!$db) {
	      echo "Error : Unable to open database\n";
	 } else {
	     //echo "Opened database successfully\n";
	}

?>
