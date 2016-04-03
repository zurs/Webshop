<?php

	$dbhost = "";
	$dbname = "";
	$dbusername = "";
	$dbpasswd = "";
	
	global $dbh; // Used to easily connect to the database
	$dbh = new PDO("mysql:host={$dbhost}; charset=utf8; dbname={$dbname};", $dbusername, $dbpasswd);

?>