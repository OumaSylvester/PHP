<?php
	// This file provides the information for accessing the database.and to MYSQL  
	//First, we define the constants:
	define('DB_HOST', 'localhost');
	define('DB_USER', 'root');
	define('DB_PASSWORD', ''); //no password. 
	define('DB_NAME', 'simpledb');

	//Connect to the db
	try{
		$dbcon = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		mysqli_set_charset($dbcon, 'utf8');
	}
	catch(Exception $e){
		//print "An Exception occured. Message: " . $e->getMessage();
		print "The system is busy please try later";
	}
?>