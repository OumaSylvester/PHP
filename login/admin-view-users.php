<?php
	session_start();
	if(!isset($_SESSION['user_level']) || $_SESSION['user_level'] != 1)
	{
		header("Location: login.php");
		exit();
	} 
?>


<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Registerd Users</title>
		<!--Bootstrap CSS file -->
		<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css">
	</head>
	<body>
		<div class="container" style="margin-top: 30px;">
			<!--Header Section -->
			<header class="jumbotron text-center row" style="margin-bottom: 2px; background: linear-gradient(white, #0073e6); padding: 20px;">
				<?php
					include 'admin-header.php';
				?>
			</header> <!--End of header -->

			<!--Body Section-->
			<div class="row" style="padding-left: 0px;">
				<nav class="col-sm-2">
					<ul class="nav nav-pills flex-column">
					<?php 
						include 'nav.php';
					?>
				</nav><!--End of body navigation -->

				<!--Center Column Content Section-->
				<div class="col-sm-8">
					<h2 class="text-center">These are the registered users</h2>
					<p>
						<?php 
							try{
								//This script retrieves all the rcords from the users table
								require('mysqli_connect.php'); //Connect to the database .
								//Make the query:
								//Nothing passed from the user..safe query
								$query = "SELECT CONCAT(last_name, ', ', first_name) AS name, ";
								$query .= "DATE_FORMAT(registration_date, '%M %d, %Y') AS ";
								$query .= "regdat FROM users ORDER BY registration_date ASC";
								$result = mysqli_query($dbcon, $query); //Run the query.
								if($result){//If it ran OK, display the records
									//Table header.
									echo '<table class="table table-striped"><tr><th scope="col">Name</th><th scope="col">Date Registered</th></tr>';
									//Fetch and print all the records:
									while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
										echo '<tr><td>' . $row['name'] .'</td><td>' . $row['regdat'] . '</td></tr>';
									}
									echo '</table>'; //close table
									mysqli_free_result($result); //Free up the resources .

								}else{//If it did not run OK.
									//Error message:
									echo '<p class="error">The current users could not be retrieved. We apologize for any inconvience.';
									//Dubug message:
									echo '<p>' . mysqli_error($dbcon) . '<br><br>Query: ' . $q  . '</p>';
									exit();
								}//End of if  ($result)
								mysqli_close($dbcon); //Close the database connection.
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
				</div> <!-- End of body users section The middle-->

				<!--Right -side Column Content Section-->
				<aside class="col-sm-2">
					<?php
						include 'info-col.php'; 
					?>					
				</aside> <!--End of right column section-->
			</div><!--End of body main-->
			
			<!--Footer Content Section-->
			<footer class="jumbotron text-center row" style="padding-bottom: 1px; padding-top: 8px;">
				<?php include 'footer.php'; ?>
				
			</footer>
		</div> <!--End of  body container -->

	</body>
</html>