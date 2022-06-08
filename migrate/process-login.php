<?php
	//This section processes submissions from the login form
	//Check if the form has been submitted

	if($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		//Connect to database
		try{
			require 'includes/mysqli_connect.php';
			//Validate the email address
			//Check foa an email address
			$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
			if(empty($email) || (!filter_var($email, FILTER_VALIDATE_EMAIL)))
			{
				$errors[] = 'You forgot to enter your email address';
				$errors[] = ' or the e-mail format is incorrect.';
			}

			//Validate the password
			$password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
			if(empty($password))
			{
				$errors[] = 'You forgot to enter your password';
			}

			if(empty($errors))//If everything's Ok
			{
				//Retrieve the user_id, password, first_name and user_level for  that email/password combiantion from the database
				$query = "SELECT user_id, password, first_name, user_level FROM users WHERE paid = 'Yes' AND email=?";
				$q=mysqli_stmt_init($dbcon);
				mysqli_stmt_prepare($q, $query);
				//bind the $email to SQL Statment
				mysqli_stmt_bind_param($q, "s", $email);
				//execute query
				mysqli_stmt_execute($q);
				$result = mysqli_stmt_get_result($q);
				$row = mysqli_fetch_array($result, MYSQLI_NUM);

				if(mysqli_num_rows($result) == 1)
				{
					//if one database row(record) matches the input
					//Start the session, fetch the record and insert the record and insert the values in an array
					if(password_verify($password, $row[1]))
					{
						session_start();
						//Ensure that the user level is an integer.
						$_SESSION['user_level'] = (int) $row[3];
						$_SESSION['user_id'] = (int) $row[0]; //determine individual user
						//Use a ternary operation to set the URL
						$url = ($_SESSION['user_level'] === 1) ? 'admin-page.php': 'members-page.php';
							//Make the browser(redirect) load either the members or the admin page
						header('Location: '. $url);

					}//end if password match 
					else //no password match was made
					{
						$errors[] = 'E-mail/Password entered does not match.';

						$errors[] = 'Perhaps you need to register, just click the Register button on the header menu';
					}
				} //end is email not empty/invalid
				else //No  e-mail match was made
				{
					$errors[] = 'E-mail/Password entered does not match.';
					$errors[] = 'Perhaps your fee has not yet been processed from paypal or credit/debit card.';
					$errors[] = 'Perhaps you need to register, just click the Register button on the header menu.';

				}
				mysqli_stmt_free_result($q);
				mysqli_stmt_close($q);
			}//end if  empty errors

			if(!empty($errors)) //Errors occured in email or password entries
			{
				$errorstring = "Error! <br> The following error(s) occurred:<br>";
				foreach ($errors as $msg) {
				 	$errorstring .= " $msg<br>\n";
				 } 
				 $errorstring .= "Please try again.<br>";
				 echo '<p class="text-center col-sm-2" style="color:red;">' . $errorstring ;
			}// end of if (!empty($errors))

			
		} //end try

		catch(Exception $e)//We finally handle any problems here
		{
			print 'An Exception occurred. Message: ' . $e->getMessage() . '<br>';
			print 'The system is busy please try later';
		}
		catch(Error $e)
		{
			print 'An Error occurred. Message: ' . $e->getMessage() . '<br>';
			print 'The system is busy please try again later';
		}
	}//end if REQUEST_METHOD IS POST. no else to allow user to enter values
?>