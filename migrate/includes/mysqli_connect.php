<?php
	// This file provides the information for accessing the database.and to MYSQL  
	//First, we define the database access details as constants:
	define('DB_HOST', 'localhost');
	define('DB_USER', 'trevithick');
	define('DB_PASSWORD', 'Asdfgh12'); 
	define('DB_NAME', 'migrate');

	//Connect to the db
	try{
		$dbcon = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		//set ecoding to utf-8
		mysqli_set_charset($dbcon, 'utf8');
	}
	catch(Exception $e){
		//print "An Exception occured. Message: " . $e->getMessage();
		print "The system is busy please try later<br>";
	}
	catch(Error $e){
		//print 'An error ocurred. Message: ' . $e->getMessage();
		print 'The sytem is busy please try  later<br>';
	}
?>
