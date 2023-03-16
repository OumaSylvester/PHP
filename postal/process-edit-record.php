<?php
	try{
		//After clicking the link in the found-record.php/admin-view-users.php
		//this code is executed
		//The code looks for a valid user ID through GET or POST
		if(isset($_GET['id']) && is_numeric($_GET['id']))
		{
			//From view-users.php/found-record.php
			$id  = htmlspecialchars($_GET['id'], ENT_QUOTES);

		}elseif (isset($_POST['id']) && is_numeric($_POST['id'])) {
			//Form submmission
			$id = htmlspecialchars($_POST['id'], ENT_QUOTES);
		}else{
			//No Valid id kill the script
			echo '<p class="text-center">This page has been accessed in error.</p>';
			include 'footer.php';
			exit();
		}

		require('mysqli_connect.php');
		//Has the form been submitted?
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$errors = array();
			//Look for the first name
			$first_name = filter_var($_POST['first_name'], FILTER_SANITIZE_STRING);
			if(empty($first_name))
			{
				$errors[] = "You forgot to enter your first name";
			}

			$last_name = filter_var($_POST['last_name'], FILTER_SANITIZE_STRING);
			if(empty($last_name))
			{
				$errors[] = "You forgot to enter your last name";
			}

			$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
			if(empty($email) || (!filter_var($email, FILTER_VALIDATE_EMAIL))){
				$errors[] = "You forgot to enter your email address or the email format is invalid";
			}

			if(empty($errors)){//All data entered by the admin was valid
				$query = "SELECT user_id FROM users WHERE email=? AND user_id!=?";
				$q = mysqli_stmt_init($dbcon);
				mysqli_stmt_prepare($q, $query);
				mysqli_stmt_bind_param($q, 'si', $email, $id);
				mysqli_stmt_execute($q);

				$result = mysqli_stmt_get_result($q);
				if(mysqli_num_rows($result) == 0){
					//email does not exists in a another record

					//The update query. Make changes to the database
					$query = "UPDATE  users SET first_name=?, last_name=?, email=?  WHERE user_id=? LIMIT 1";
					//bind values to sql statements
					$q = mysqli_stmt_init($dbcon);
					mysqli_stmt_prepare($q, $query);
					mysqli_stmt_bind_param($q, 'sssi', $first_name, $last_name, $email, $id);
					mysqli_stmt_execute($q);

					if(mysqli_stmt_affected_rows($q) == 1){
						//update OK. echo a message if the edit was satisfactory
						echo '<p class="text-center">The user has been edited</p>';
						//add a 5 seconds wait before redirection

						header("Location: admin-view-users.php");
					}else{
						//echo a message if the query failed
						echo '<p class="text-center">The user could not be edited due to a system error. We apologize for any inconvinience.</p>';
						//There is a bug if a the admin clicks the edit link and changes nothing.
						//Debug message

						//echo '<br><p>' . mysqli_error($dbcon) . '<br>Query: ' . $query . '</p>';
					}
				}else{
					//Already registered email
					echo '<p class="text-center">Email address has already been registered</p>';
				}
			}else{
				//An error occured in the data entered. Display the errors
				echo '<p class="text-center">The following error(s) occured: <br>';
				foreach($errors as $msg){
					//echo each error
					echo " - $msg <br>";
				}
				echo 'Please try again';
			}//end if empty($errors)

		}//end if POST
		else{
		//Select the user's information to display in the text boxes
		$query = "SELECT first_name, last_name,email FROM users WHERE user_id=?";
		$q = mysqli_stmt_init($dbcon);
		mysqli_stmt_prepare($q, $query);
		mysqli_stmt_bind_param($q, "i", $id);
		mysqli_stmt_execute($q);

		$result = mysqli_stmt_get_result($q);
		$row = mysqli_fetch_array($result, MYSQLI_NUM);

		if(mysqli_num_rows($result) == 1){
			//valid user ID display the form
			//Get the users information
			//Create the form
		
?>
			<h2 class="h2">Edit Record</h2>
			<form action="edit-record.php" method="post" name="edit_form" id="edit_form">
				<div class="form-group row">
					<label for="first_name" class="col-sm-4 col-form-label">First Name:</label>
					<div class="col-sm-8">
						<input class= "form-control" type="text" name="first_name" id="first_name" required placeholder="First Name" maxlength="30" value="<?php echo htmlspecialchars($row[0], ENT_QUOTES); ?>">
					</div>
				</div>

				<div class="form-group row">
					<label for="last_name" class="col-sm-4 col-form-label">Last Name:</label>
					<div class="col-sm-8">
						<input type="text" name="last_name" id="last_name"class="form-control" required placeholder="Last Name" maxlength="40" value="<?php echo htmlspecialchars($row[1], ENT_QUOTES); ?>">
					</div>			
				</div>

				<div class="form-group row">
					<label for="email" class="col-sm-4 col-form-label">Email:</label>
					<div class="col-sm-8">
						<input type="text" name="email" id="email"class="form-control" required placeholder="E-mail" maxlength="60" value="<?php echo htmlspecialchars($row[2], ENT_QUOTES); ?>">
					</div>			
				</div>
				<input type="hidden" name="id" value="<?php echo $id ?>">

				<div class="form-group row">
					<label for="" class="col-sm-8 col-form-label"></label>
					<div class="col-sm-4">
						<input type="submit" name="submit" value="Register" class="btn btn-primary">
					</div>
					
				</div>
			</form>
<?php 
		}else{//The user could not be validated
			echo '<p class="text-center">The page has been accessed in error.</p>';
		}
	}//get GET request 
		//Free results and close the database connection
		mysqli_stmt_free_result($q);
		mysqli_close($dbcon);
	}//end try
	catch(Exception $e){
		print 'The system is busy please try again later<br>';
		//print 'An Exception occured. Message: ' . $e->getMessage() '<br>';
	}
	catch(Error $e){
		print 'The system is busy please try again later<br>';
		print 'An error occured. Message: ' . $e->getMessage() . '<br>';
	}
?>