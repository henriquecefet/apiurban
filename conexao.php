<?php
$host        = "host = ec2-23-20-129-146.compute-1.amazonaws.com";
$port        = "port = 5432";
$dbname      = "dbname = d4lbqqmnpeve28";
$credentials = "user = zwifcqhcjeiokgpassword=1ff276855a41e7c3da65d0eabb32545502930e1e2f8250dad7b389adbd09cbcf";
$db = pg_connect( "$host $port $dbname $credentials"  );
	if(!$db) {
	      //echo "Error : Unable to open database\n";
	 } else {
	     //echo "Opened database successfully\n";
	}}

?>