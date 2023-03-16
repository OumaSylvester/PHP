<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Template for an interactive web page</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!--Bootstrap CSS File -->
		<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css">
		<script type="text/javascript" src="verify.js"></script>
		
	</head>
	<body>
		<div class="container" style="margin-top: 30px;">
			<!--Header Section -->
			<header class="jumbotron text-center row" style="margin-bottom: 2px; background: linear-gradient(white, #0073e6); padding: 20px;"><?php include 'header.php'; ?>
				
			</header>


			<!--Body Section -->
			<div class="row" style="padding-left:0px;">
				<!--Left-side Colunm Menu Section -->
				<nav class="col-sm-2">
					<ul class="nav nav-pills flex-colunm">
						<?php include 'nav.php'; ?>
					</ul>
				</nav>

				<!-- Validate Input -->
				<?php 
					if($_SERVER['REQUEST_METHOD'] == 'POST'){
						require ('process-register-page.php');
					}// End of the main Submit conditional.
				?>
				<div class="col-sm-8">
					<h2 class="h2 text-center">Register</h2>

					<form action="register-page.php" method="post" onsubmit="return checked();" name="regform" id="regfrom">
						<div class="form-group row">
							<label for="first_name" class="col-sm-4 col-form-label">First Name:</label>
							<div class="col-sm-8">
								<input type="text" name="first_name" class="form-control" id="first_name" placeholder="First Name" maxlength="30" required value="<?php if(isset($_POST['first_name'])) echo $_POST['first_name'];?>">
							</div>
						</div>
						<div class="form-group row">
							<label for="last_name" class="col-sm-4 col-form-label">Last Name:</label>
							<div class="col-sm-8">
								<input type="text" name="last_name" class="form-control" id="last_name" placeholder="Last Name" maxlength="40" required value="<?php if(isset($_POST['last_name'])) echo $_POST['last_name'];?>">
							</div>
						</div>

						<div class="form-group row">
							<label for="email" class="col-sm-4 col-form-label">E-mail:</label>
							<div class="col-sm-8">
								<input type="email" name="email" class="form-control" id="email" placeholder="E-mail" maxlength="60" required value="<?php if(isset($_POST['email'])) echo $_POST['email'];?>">
							</div>
						</div>

						<div class="form-group row">
							<label for="password1" class="col-sm-4 col-form-label">Password:</label>
							<div class="col-sm-8">
								<input type="password" name="password1" class="form-control" id="password1" placeholder="Password" minlength="8" required value="<?php if(isset($_POST['password1'])) echo $_POST['password1'];?>">
								<span id="message">Between 8 and 12 characters.</span>
							</div>
						</div>

						<div class="form-group row">
							<label for="password2" class="col-sm-4 col-form-label">Confirm Password:</label>
							<div class="col-sm-8">
								<input type="password" name="password2" class="form-control" id="password2" placeholder="Confirm Password" minlength="8" required value="<?php if(isset($_POST['password2'])) echo $_POST['password2'];?>">
							</div>
						</div>

						<div class="form-group">
							<div class="col-sm-12">
								<input type="submit" name="submit" id="submit" class="btn btn-primary" value="Register">
							</div>
						</div>
						
					</form>
				</div> <!--End of div col-sm-8 for the form -->
				<!--Right-side Column Content Section-->
				<?php
					if(!isset($errorstring)){
						echo '<aside class="col-sm-2">';
						include 'info-col.php';
						echo '</aside>';
						echo '</div>'; //close the div of body section(the middle section of the page containing the left nav links, the form and the rigt info)
						//include the footer
						echo '<footer class="jumbotron text-center row col-sm-12"
							style="padding-bottom:1px; padding-top:8px;">';
						include("footer.php");
					}else{//we don't display the aside info-col if there are errors. We use the 2 small collumns to display the errors
						echo '<footer class="jumbotron text-center col-sm-12" style="padding-bottom:1px; padding-top:8px;">';
						include('footer.php');
					}
					
				?>			
			</footer>
		</div>

	</body>
</html>