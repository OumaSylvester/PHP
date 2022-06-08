<?php 
	/*This script is a query that INSERTS  a record in the users
	tabel*/

	//Check that form has been submitted:
	$errors = array(); //Initialize an error array.
	//Check for a first name:
	$first_name = filter_var($_POST['first_name'], FILTER_SANITIZE_STRING);
	if(!empty($first_name) && (preg_match('/[a-z\s]/i', $first_name)) && (strlen($first_name) <= 30)){
		$first_name = $first_name;
	}else{
		$errors[] = "First name missing or not alphabetic and space characters. Max 30";
	}

	//Check for last name
	$last_name = filter_var($_POST['last_name'], FILTER_SANITIZE_STRING);
	if(!empty($last_name) && (preg_match('/[a-z\s]/i', $last_name)) && (strlen($last_name) <= 40)){
		$lasr_name = $lasr_name;
	}else{
		$errors[] = "Last name missing or not alphabetic and space characters. Max 40";
	}

	//Check for email
	$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
	if(empty($email) || (!filter_var($email, FILTER_VALIDATE_EMAIL))){
		$errors[] = "You forgot to enter your email address";
	}

	//Check for a password and match against the confirmed password
	$password1 = filter_var($_POST['password1'], FILTER_SANITIZE_STRING);
	$password2 = filter_var($_POST['password2'], FILTER_SANITIZE_STRING);
	if(!empty($password1)){
		if($password1 !== $password2){
			$errors[] = "Your two passwords did not match.";
		}
	}else{
		$errors[] = "You forgot to enter your password.";
	}

	//Check for an membership class
	if(isset($_POST['level'])){
		$class = filter_var($_POST['level'], FILTER_SANITIZE_STRING);
	}
	if(!empty($class) && (strlen($class) <= 3)){
		$class = filter_var($class, FILTER_SANITIZE_NUMBER_INT);
	}else{
		$errors[] = "Missing Level Selection.";
	}
	//Check for address:
	$address1 = filter_var($_POST['address1'], FILTER_SANITIZE_STRING);
	if(empty($address1)){
		$errors[] = "You forgot to enter your address.";
	}
	//Check for address2
	$address2 = filter_var($_POST['address2'], FILTER_SANITIZE_STRING);
	if(empty($address2)){
		$address2 = NULL;
	}

	//Check for city
	$city = filter_var($_POST["city"], FILTER_SANITIZE_STRING);
	if(empty($city)){
		$errors[] = "You forgot to enter your City";
	}

	//Check for the country
	$state_country = filter_var($_POST["state_country"], FILTER_SANITIZE_STRING);
	if(empty($state_country)){
		$errors[] = "You forgot to enter your country";
	}
	//Check from post code
	$zcode_pcode = filter_var($_POST["zcode_pcode"], FILTER_SANITIZE_STRING);
	if(empty($zcode_pcode)){
		$errors[] = "You forgot to enter your post code";
	}
	//Check for the phone number 
	$phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
	if(!empty($phone) && (strlen($phone) <= 30)){
		$phone = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
		$phone = preg_replace("/[^0-9]/", '', $phone);
	}else{
		$phone = NULL;
	}

	if(empty($errors)){ //If everything is okay
		try{
			//Determine wether the email email address has already been registered
			require 'includes/mysqli_connect.php'; //connect to the db
			
			$query = "SELECT user_id  FROM users WHERE email = ?";
			$q = mysqli_stmt_init($dbcon);
			mysqli_stmt_prepare($q, $query);
			mysqli_stmt_bind_param($q, 's', $email);
			mysqli_stmt_execute($q);
			$result = mysqli_stmt_get_result($q);
			if(mysqli_num_rows($result) ==  0){
				//The email address has not been registered 

				//Register the user in the database...
				//Hash password current 60 characters but can increase
				$hashed_passcode = password_hash($password1, PASSWORD_DEFAULT);
				//require 'mysqli_connect.php'; //connect to the db
				//Make the query:
				$query = "INSERT INTO users (user_id, first_name, last_name, email, password, class, address1, address2, city, state_country, zcode_pcode, phone, registration_date) VALUES(' ', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW()) ";


				$q = mysqli_stmt_init($dbcon);
				//Use prepared statement to ensure that only text is inserted
				mysqli_stmt_prepare($q, $query);
				
				//bind fields to SQL Statement
				mysqli_stmt_bind_param($q, 'sssssssssss', $first_name, $last_name, $email, $hashed_passcode, $class, $address1, $address2, $city, $state_country, $zcode_pcode, $phone);
				//param must include four variables to match the four s parameters

				//execute query
				mysqli_stmt_execute($q);
				if(mysqli_stmt_affected_rows($q) ==1){
					header("Location: register-thanks.php?class=$class"); // a redirection
					exit();
				}else{ //if it did not run OK. No Redirection. User still on the register-page.php
					//Public message:
					$errorstring ='System Error<br />You could not be registered due to a system error. We apologize for any incovinience';
					echo '<p class="text-center col-sm-2" style="color: red;">' . $errorstring . '</p>'; 

					//Debugging message below do not use in production
					//echo '<p>' . mysqli_error($dbcon) . '<br><br>Query: ' .$query . '</p>';
					//Close the database connection.
					mysqli_close($dbcon);

					//include footer then close program to stop execution
					/*echo '<footer class="jumbotron text-center col-sm-12" style="padding-bottom:1px; padding-top:8px;">';
					 include("footer.php");
					 echo '</footer>';*/
					exit();
				}
			}else{//The email adddress is already registered
				$errorstring = "The email address is already registered.";
				echo '<p class="text-center col-sm-2" style="color:red;">' . $errorstring;

			}
		}
		catch(Exception $e) //We finally handle any problems here. Systems errors
		{
			print "An Exception occurred. Message: " . $e->getMessage() . '<br>';//for debugging purposes only
			print "The system is busy please try again later";
		}
		catch(Error $e)
		{
			print "An error occured. Message: " .$e->getMessage() . '<br>'; //for debugging purposes only
			print "The system is busy please try again later.";
		}
	}
	else{//Report the errors. >> User errors
		$errorstring = "Error! The following error(s) occured: <br>";
		foreach ($errors as $msg) {//Print each error.
			$errorstring .= " - $msg<br>";
		}
		$errorstring .= "Please try again.<br>";
		echo '<p class="text-center col-sm-2" style="color: red">' . $errorstring . '</p>';
	}//End of if (empty($errors)) IF.

 ?>