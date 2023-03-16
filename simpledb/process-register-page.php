<?php 
	/*This script is a query that INSERTS  a record in the users
	tabel*/

	//Check that form has been submitted:
	$errors = array(); //Initialize an error array.
	//Check for a first name:
	$first_name = trim($_POST['first_name']);
	if(empty($first_name)){
		$errors[] = "You forgot to enter your first name";
	}

	//Check for last name
	$last_name = trim($_POST['last_name']);
	if(empty($last_name)){
		$errors[] = "You forgot to enter your last name";
	}

	//Check for email
	$email = trim($_POST['email']);
	if(empty($_POST['email'])){
		$errors[] = "You forgot to enter your email address";
	}

	//Check for a password and match against the confirmed password
	$password1 = trim($_POST['password1']);
	$password2 = trim($_POST['password2']);
	if(!empty($password1)){
		if($password1 !== $password2){
			$errors[] = "Your two passwords did not match.";
		}
	}else{
		$errors[] = "You forgot to enter your password.";
	}

	if(empty($errors)){ //If everything is okay
		try{
			//Register the user in the database...
			//Hash password current 60 characters but can increase
			$hashed_passcode = password_hash($password1, PASSWORD_DEFAULT);
			require("mysqli_connect.php"); //connect to the db
			//Make the query:
			$query = "INSERT INTO users (first_name, last_name, email, password, registration_date) VALUES(?, ?, ?, ?, NOW()) ";
			$q = mysqli_stmt_init($dbcon);
			mysqli_stmt_prepare($q, $query);
			//Use prepared statement to ensure that only text is inserted
			//bind fields to SQL Statement
			mysqli_stmt_bind_param($q, 'ssss', $first_name, $last_name, $email, $hashed_passcode);
			//param must include four variables to match the four s parameters

			//execute query
			mysqli_stmt_execute($q);
			if(mysqli_stmt_affected_rows($q) ==1){
				header("Location: register-thanks.php"); // a redirection
				exit();
			}else{ //if it did not run OK. No Redirection. User still on the register-page.php
				//Public message:
				$errorstring .= 'System Error<br />You could not be registered due to a system error. We apologize for any in covinience';
				echo '<p class="text-center col-sm-8" style="color: red;">' . $errorstring . '</p>'; 

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
		}
		catch(Exception $e) //We finally handle any problems here. Systems errors
		{
			//print "An Exception occurred. Message: " . $e->getMessage();//for debugging purposes only
			print "The system is busy please try again later";
		}
		catch(Error $e)
		{
			//print "An error occured. Message: " .$e->getMessage(); //for debugging purposes only
			print "The system is busy please try again later.";
		}
	}
	else{//Report the errors. >> User errors
		$errorstring = "Error! The following error(s) occured: <br>";
		foreach ($errors as $msg) {//Print each error.
			$errorstring .= " - $msg<br>\n";
		}
		$errorstring .= "Please try again.<br>";
		echo '<p class="text-center col-sm-2" style="color: red">' . $errorstring . '</p>';
	}//End of if (empty($errors)) IF.

 ?>