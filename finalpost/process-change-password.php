<?php
	//The scriptt is a query that UPDATES the password in the users table

	require 'includes/mysqli_connect.php'; //Connect to the db
	$errors = array(); //Initialize an errror array
	//Check for an email address:

	$email = trim($_POST['email']);
	if(empty($email)){
		$errors[] = "You forgot to enter your email address.";
	}

	//Check for a password and match againsts the confirmed password
	$password = trim($_POST['password']);
	if(empty($password)){
		$errors[] = "Yo forgot to enter your old password.";
	}	 

	//Prepare and check new Password
	$password1 = trim($_POST['password1']);
	$password2 =trim($_POST['password2']);
	if(!empty($password1)){
		if(($password1 != $password2) || ($password == $password1)){
			$errors[] = 'You new password did not match the confirmed  password and/or ';
			$errors[] = 'Your old password is the same as your new password.';
		}
	}else{
		$errors = 'You did not enter a new password';
	}

	if(empty($errors)){ //If everything's is OK
		try{
			//check that the user has entered the right email address/ password combination:
			$query = "SELECT user_id, password FROM users WHERE(email=?)";
			$q = mysqli_stmt_init($dbcon); 
			mysqli_stmt_prepare($q, $query);
			//use prepared stamement to ensure  that only text is inserted
			//bind fields to SQL Statements
			mysqli_stmt_bind_param($q, 's', $email);
			//Execute query
			mysqli_stmt_execute($q);

			$result = mysqli_stmt_get_result($q);
			$row = mysqli_fetch_array($result, MYSQLI_ASSOC); //Create an associative array of the results

			if((mysqli_num_rows($result) == 1) && (password_verify($password, $row['password']))){
				//Found record
				//Change the password in the database...
				//Hash password current 60 characters but can increase
				$hashed_passcode = password_hash($password1, PASSWORD_DEFAULT);

				//Make the query: To update the password
				$query = "UPDATE users SET password=? WHERE email=?";
				$q = mysqli_stmt_init($dbcon);
				mysqli_stmt_prepare($q, $query);
				//use prepared statement to ensure thar olny text is inserted
				//bind fields to SQL Statment
				mysqli_stmt_bind_param($q, 'ss', $hashed_passcode, $email);
				//execute query
				mysqli_stmt_execute($q);
				if(mysqli_stmt_affected_rows($q) == 1){
					//one row updated. Thank you
					header("location: includes/password-thanks.php");
				}else{//If it did not run Ok. No redirection
					//Public message:
					$errorstring = "System Error! <br >You could not change password due to a system error.";
					$errorstring .= "We appologize for any inconvinience.";
					echo '<p class="text-center col-sm-2" style="color:red;">' .$errorstring . '</p>';
					//Debugging message below do not use in production
					//echo '<p>' . mysqli_error($dbcon) . '<br><br>Query: ' . $query . '</p>';

					//This code  is repeating 
					//Include footer then close program to stop execution
					//echo '<footer class="jumbotron text-center col-sm-12" style = "padding-bottom:1px; padding-top: 8px;">';
					//include 'footer.php';
				}
			}else{//Invalid email address/password combination.
				$errorstring = 'Error! <br>';
				$errorstring .= "The email address and or the password do not match those on file.";
				$errorstring .= "Please try again.";
				echo '<p class="text-center col-sm-2" style="color:red;">' . $errorstring .'</p>';

			}

		}catch(Exception $e)//We Finally handle any problems here
		{
			print 'An Exception occured. Message: ' . $e->getMessage() . '</br>';
			print 'The system is busy please try later<br>';
		}
		catch(Error $e)
		{
			print 'An Error occurred. Message: '. $e->getMessage() .'</br>';
			print 'The system is busy please try again later.<br>';
		}

	}else{//Report the errors.
		//header("location: register-page.php");
		$errorstring = "Error! The following error(s) occurred<br>";
		foreach($errors as $msg){//Print each error
			$errorstring .= " - $msg<br>";
		}
		$errorstring .= "Please try again.<br>";
		echo '<p class="text-center col-sm-2" style="color:red;">' . $errorstring . '</p>';
	}//End of main Submit conditional
?>