<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Change Password</title>
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css">
	<script type="text/javascript" src="verify.js"></script>
</head>
<body>
	<div class="container">
		<!--Header section-->
		<header class="jumbotron text-center row" style="padding: 20px; background: linear-gradient(white, #0073e6); margin-bottom: 2px;">
			<?php
				include 'password-header.php';
			?>
		</header>

		<!--Main Section-->
		<div class="row">
			<!--Left Navigation Links-->
			<nav class="col-sm-2" style="padding-left: 0px;">
				<ul class="nav nav-pills flex-column">
					<?php include 'nav.php';?>
				</ul>
			</nav>

			<?php 
				if($_SERVER['REQUEST_METHOD'] == 'POST'){
					require 'process-change-password.php'; //The fil stops executing if an error occurs
				}//End of main Submit conditional
			?>

			<!--Change password section-->
			<div class="col-sm-8">
				<h2 class="h2 text-center">Change Password</h2>

				<form action="change-password.php" method="post" onsubmit="return checked();" name="changepasswordform" id="changepasswordform">
					
					<div class="form-group row">
						<label for="email" class="col-sm-4 col-form-label">E-mail:</label>
						<div class="col-sm-8">
							<input type="email" name="email" id="email" required placeholder="jsmith@example.com"  class= "form-control" value="<?php if(isset($_POST['email'])) echo $_POST['email'];?>">
						</div>
					</div>

					<div class="form-group row">
						<label for="password" class="col-sm-4 col-form-label">Current Password:</label>
						<div class="col-sm-8">
							<input type="password" name="password" id="password" required class="form-control" value="<?php if(isset($_POST['password'])) echo $_POST['password'];?>">
						</div>
					</div>

					<div class="form-group row">
						<label for="password1" class="col-sm-4 col-form-label">New Password:</label>
						<div class="col-sm-8">
							<input type="password" name="password1" id="password1" required minlength="8" class="form-control" value="<?php if(isset($_POST['password1'])) echo $_POST['password1'];?>">
						</div>
					</div>

					<div class="form-group row">
						<label for="password2" class="col-sm-4 col-form-label">Confirm Password:</label>
						<div class="col-sm-8">
							<input type="password" name="password2" id="password2" required minlength="8"  class="form-control" value="<?php if(isset($_POST['password2'])) echo $_POST['password2'];?>">
						</div>
					</div>

					<div class="form-group">
						<div class="col-sm-12">
							<input type="submit" name="submit" id="submit"  class="btn btn-primary" value="Submit">
						</div>
						
					</div>
				</form>
			</div><!--End of Middle Chnage Password Section-->

			<!--Right side Columun Section Last two columns-->
			
			<?php 
				if(!isset($errorstring)){ //if there are no errors
					echo '<aside class="col-sm-2">';
					include 'info-col.php';
					echo '</aside>';
					//I would change this design from here to place the code that follows out of if statement because it happens wether errorstring is set or not
					echo '</div>'; //Close the div of the row (row that contains the 3 sections)

					echo '<footer class="jumbotron col-sm-12" style="paddind-bottom: 1px; padding-top: 8px;">';
				
				}else{
					//echo '<p style="color: red;">' . $errorstring . '</p>'; // I would display any error that occured
					echo '</div>';
					echo '<footer class="jumbotron col-sm-12" style="paddind-bottom: 1px; padding-top: 8px;">';
					
				}
				include 'footer.php';
			?>
			
		</footer>


	</div><!--End of container-->

</body>
</html>