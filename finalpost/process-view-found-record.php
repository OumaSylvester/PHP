<?php
	try {
		//This script retrievs records from the users table
		require 'includes/mysqli_connect.php'; //connect to the database
		echo '<p class="text-center">If no record is shown, this is because you had an incorrect or missing entry in the search form.';
		echo '<br>Click the back button on the  browser and try again.</p>';

		//
		$first_name = htmlspecialchars($_POST['first_name'], ENT_QUOTES);
		$last_name = htmlspecialchars($_POST['last_name'], ENT_QUOTES);
		$query = "SELECT last_name, first_name, email, DATE_FORMAT(registration_date, '%M %d, %Y') AS regdat, class, paid, user_id FROM users WHERE last_name=? AND first_name=? ORDER BY regdat ASC";

		$q = mysqli_stmt_init($dbcon);
		mysqli_stmt_prepare($q, $query);

		//bind values to SQL statements
		mysqli_stmt_bind_param($q, 'ss', $last_name, $first_name);

		//execute query
		mysqli_stmt_execute($q);

		$result = mysqli_stmt_get_result($q);
		if($result){//if it ran display the records
			//Table header
			echo '<table class="table table-striped">
			<tr>
			<th scope="col">Edit</th>
			<th scope="col">Delete</th>
			<th scope="col">Last Name</th>
			<th scope="col">First Name</th>
			<th scope="col">Email</th>
			<th scope="col">Registration Date</th>
			<th scope="col">Class</th>
			<th scope="col">Paid</th>
			</tr>';

			//Fetch and display the results
			while($row=mysqli_fetch_array($result)){
				//reduce chances of XSS exploits
				$user_id = htmlspecialchars($row['user_id'], ENT_QUOTES);
				$last_name = htmlspecialchars($row['last_name'], ENT_QUOTES);
				$first_name = htmlspecialchars($row['first_name'], ENT_QUOTES);
				$email = htmlspecialchars($row['email'], ENT_QUOTES);
				$registration_date = htmlspecialchars($row['regdat'], ENT_QUOTES);
				$class = htmlspecialchars($row['class'], ENT_QUOTES);
				$paid = htmlspecialchars($row['paid'], ENT_QUOTES);

				echo '<tr>

				<td><a href="edit-record.php?id=' . $user_id . '">Edit</a></td>
				<td><a href="delete-record.php?id=' . $user_id . '">Delete</a></td>
				<td>' . $last_name . '</td>
				<td>' . $first_name . '</td>
				<td>' . $email . '</td>
				<td>' . $registration_date . '</td>
				<td>' . $class . '</td>
				<td>' . $paid . '<td>

				</tr>';

			}
			echo '</table>';

		}else{
			//If it did not run okay
			//Public message
			echo '<p class="error">The current users could not be retrieved. We apologie for any inconvinience.</p>';

			//Debugging message
			echo '<p>' . mysqli_error($dbcon) . '<br><br>Query: ' . $q . '</p>';
			//Show $q in  debug mode only
		}//End of if $result. Now display the total number oof records/members
		mysqli_close($dbcon); //close database connection
		
	} catch (Exception $e) {
		print 'The system is currently busy. Please try again later.<br>';

		//Debug message
		//print 'An exception occured. Message: ' . $e->getMessage() . '<br>';
	}
	catch(Error $e){
		print 'The system is currently busy. Please try again later.<br>';

		//Debug message
		//print 'An errot occured. Message: ' . $e->getMessage() . '<br>';
	}
?>