<?php
$db;
$host        = "ec2-3-222-183-44.compute-1.amazonaws.com";
$port        = "port = 5432";
$dbname      = "dbname = d5652rk5upj4qb";
$credentials = "user = pdgjnondfgzvnr password=afb1574a2e12a2dee262b16e727cc67fee67cd8cdef5f40c01fb1f0f8faf791e";
$db = pg_connect( "$host $port $dbname $credentials"  );
	if(!$db) {
	      //echo "Error : Unable to open database\n";
	 } else {
	     //echo "Opened database successfully\n";
	}

?>
