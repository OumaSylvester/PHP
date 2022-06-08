<?php
	try {
		//This script retrievs records from the users table
		require 'includes/mysqli_connect.php'; //connect to the database
		echo '<p class="text-center">If no record is shown, this is because you had an incorrect or missing entry in the search form.';
		echo '<br>Click the back button on the  browser and try again.</p>';

		//
		$first_name = htmlspecialchars($_POST['first_name'], ENT_QUOTES);
		$last_name = htmlspecialchars($_POST['last_name'], ENT_QUOTES);
		$query = "SELECT user_id,last_name,title, first_name, address1,address2,city,state_country,zcode_pcode,phone FROM users WHERE last_name=? AND first_name=?";

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
			<th scope="col">Title</th>
			<th scope="col">Last Name</th>
			<th scope="col">First Name</th>
			<th scope="col">Adress1</th>
            <th scope="col">Adress2</th>
			<th scope="col">City</th>
			<th scope="col">State or Country</th>
			<th scope="col">Zip or Postal Code</th>
            <th scope="col">Phone</th>
			</tr>';

			//Fetch and display the results
			while($row=mysqli_fetch_array($result)){
				//reduce chances of XSS exploits
				$user_id = htmlspecialchars($row['user_id'], ENT_QUOTES);
                $title = htmlspecialchars($row['title'], ENT_QUOTES);
				$last_name = htmlspecialchars($row['last_name'], ENT_QUOTES);
				$first_name = htmlspecialchars($row['first_name'], ENT_QUOTES);
                $address1 = htmlspecialchars($row['address1'], ENT_QUOTES);
                $address2 = htmlspecialchars($row['address2'], ENT_QUOTES);
				$city = htmlspecialchars($row['city'], ENT_QUOTES);
				$state_country = htmlspecialchars($row['state_country'], ENT_QUOTES);
				$zcode_pcode = htmlspecialchars($row['zcode_pcode'], ENT_QUOTES);
				$phone = htmlspecialchars($row['phone'], ENT_QUOTES);

				echo '<tr>

				<td><a href="edit-address.php?id=' . $user_id . '">Edit</a></td>
                <td scope="row">' . $title . '</td>
                <td scope="row">' . $last_name . '</td>
				<td scope="row">' . $first_name . '</td>
				<td scope="row">' . $address1 . '</td>
				<td scope="row">' . $address2 . '</td>
				<td scope="row">' . $city . '</td>
				<td scope="row">' . $state_country. '</td>
                <td scope="row">' . $zcode_pcode . '</td>
                <td scope="row">' . $phone . '</td>

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