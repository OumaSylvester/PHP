<?php
	// This file provides the information for accessing the database.and to MYSQL  
	//First, we define the database access details as constants:
	define('DB_HOST', 'localhost');
	define('DB_USER', 'jenner');
	define('DB_PASSWORD', ' Vacc1nat10n'); 
	define('DB_NAME', 'postaldb');

	//Connect to the db
	try{
		$dbcon = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		//set ecoding to utf-8
		mysqli_set_charset($dbcon, 'utf8');
	}
	catch(Exception $e){
		//print "An Exception occured. Message: " . $e->getMessage();
		print "The system is busy please try later";
	}
	catch(Error $e){
		//print 'An error ocurred. Message: ' . $e->getMessage();
		print 'The sytem is busy please try  later';
	}
?>
