<?php 
	try{
		//This script retireves all the records from the users table
		require ('mysqli_connect.php');
		//Set the number per dispaly page
		$pagerows = 4;
		//Has the total number of pages already been calculated?
		if(isset($_GET['p']) && is_numeric($_GET['p']))
		{
			//already been calculated
			//Make sure it is not executable XSS
			$pages = htmlspecialchars($_GET['p'], ENT_QUOTES);
		}
		else{
			//use the next block of code to calculate the number of pages
			//First check for the total number of records
			$q = "SELECT count(user_id) from users";
			$result = mysqli_query($dbcon, $q);
			$row = mysqli_fetch_array($result, MYSQLI_NUM);
			//Make sure it is not EXECUTABLE XSS
			$records = htmlspecialchars($row[0], ENT_QUOTES);

			//Now calculate the number of pages
			if($records > $pagerows)
			{
				//If number of records will feel more than one page
				//Calculate the number of pages and round up the number
				// to the nearest integer
				$pages = ceil($records/$pagerows);
			}
			else{
				$pages = 1;
			}

		}//page check finished
		//Declare which record to start with
		if(isset($_GET['s']) && is_numeric($_GET['s']))
		{
			//Make sure it is not executable XSS
			$start = htmlspecialchars($_GET['s'], ENT_QUOTES);
		}else{
			$start = 0;
		}

		
		$query = "SELECT last_name, first_name, email, ";
		$query .= "DATE_FORMAT(registration_date, '%M %d, %Y')";
		$query .=" AS regdat, user_id FROM users ORDER BY registration_date ASC";
		$query .=" LIMIT ?, ?";
		$q = mysqli_stmt_init($dbcon);
		mysqli_stmt_prepare($q, $query);
		mysqli_stmt_bind_param($q, 'ii', $start, $pagerows);
		//Execute query
		mysqli_stmt_execute($q);

		$result = mysqli_stmt_get_result($q);
		if($result){//If it ran OK, display the records
			//Table header.
			echo '<table class="table table-striped">
			<tr>
			<th scope="col">Edit</th>
			<th scope="col">Delete</th>
			<th scope="col">Last Name</th>
			<th scope="col">First Name</th>
			<th scope="col">Email</th>
			<th scope="col">Date Registered</th>
			</tr>';
			//Fetch and print all the records:
			while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
				//Remove special charcters that might already be in the table to reduce the chance of XSS exploits
				$user_id = htmlspecialchars($row['user_id'], ENT_QUOTES);
				$last_name = htmlspecialchars($row['last_name'], ENT_QUOTES);
				$first_name = htmlspecialchars($row['first_name'], ENT_QUOTES);
				$email = htmlspecialchars($row['email'], ENT_QUOTES);
				$registration_date = htmlspecialchars($row['regdat'], ENT_QUOTES);

				echo '<tr>

				<td><a href="edit-record.php?id='. $user_id .'">Edit</a></td>
				<td><a href="delete-record.php?id=' . $user_id . '">Delete</a></td>';

				echo '<td>' . $last_name .'</td>
				<td>' . $first_name . '</td>
				<td>' . $email . '</td>
				<td>' . $registration_date . '</td>

				</tr>';
			}
			echo '</table>'; //close table
			mysqli_free_result($result); //Free up the resources .

		}else{//If it did not run OK.
			//Error message:
			echo '<p class="error">The current users could not be retrieved. We apologize for any inconvience.';
			//Dubug message:
			echo '<p>' . mysqli_error($dbcon) . '<br><br>Query: ' . $query  . '</p>';
			exit();
		}//End of else ($result)
		//Now display the total number of records/members
		$q = "SELECT count(user_id) FROM users";
		$result = mysqli_query($dbcon, $q);
		$row = mysqli_fetch_array($result);
		$members = htmlspecialchars($row[0], ENT_QUOTES);
		mysqli_close($dbcon); //close the database connection
		$echostring = '<p class="text-center">Total Membership: ' . $members . '</p>';  
		//Display the next  the previous button
		$echostring .= '<p class="text-center">';
		if($pages > 1)
		{
			//What number is the current page
			$current_page = ($start/$pagerows) + 1;
			//If the page is not the first page
			if($current_page != 1)
			{
				$echostring .= '<a href="admin-view-users.php?s=' . ($start - $pagerows) . '&p=' . $pages . '">Previous &nbsp;</a>';
			}

			//Create a next link
			if($current_page != $pages)
			{
				$echostring .= '<a href="admin-view-users.php?s=' . ($start + $pagerows) . '&p=' . $pages . '">&nbsp;Next</a>';
			}
			$echostring .= '</p>';
			echo $echostring;
		}
	}
	catch(Exception $e)//We finally handle any problems
	{
		print 'An Exception occured. Message: ' .$e->getMessage() .'<br>';
		print 'The system is busy please try later';
	}
	catch(Error $e)
	{
		
		print 'An Error occured. Message: ' . $e->getMessage() . '<br>';
		print 'The system is busy try again later.';
		//Todo:Research>> How can handle warnings in php to stop them from being dipalyed on the browser
	}
?>