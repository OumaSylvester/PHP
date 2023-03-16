<?php
	session_start();
	if(!isset($_SESSION['user_level']) || $_SESSION['user_level'] != 1)
	{//user not logged in or user not admin
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
							//process the tables
							require 'process-admin-view-users.php';
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