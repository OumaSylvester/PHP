<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Login</title>
		<!--Bootstrap css file-->
		<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css">
	</head>
	<body>
		<div class="container" style="margin-top: 30px;">
			<!--Headre Section-->
			<header class="jumbotron text-center row col-sm-12" style="margin-bottom: 2px; background: linear-gradient(white, #0073e6); padding: 20px;">
				<?php include 'login-header.php' ?>;
				
			</header>
			<!--Body Section-->
			<div class="row" style="padding: 0px;">
				<!--Left-side Colum Menu Section-->
				<nav class="col-sm-2">
					<ul class="nav nav-pills flex-column">
						<?php include 'nav.php'; ?>
					</ul>
				</nav>
				
				<!--Main content section-->
				<!--Validate Input-->
				<?php 
					if($_SERVER['REQUEST_METHOD'] == 'POST'){
						require 'process-login.php';
						//End of the main Submit conditional
					}
				?>
				<div class="col-sm-8">
					<h2 class="h2 text-center">Login</h2>
					<form action="login.php" method="post" name="loginform" id="loginform">
						<div class="form-group row">
							<label for="email" class="col-sm-4 col-form-label">E-mail:</label>
							<div class="col-sm-8">
								<input type="email" name="email" id="email" class="form-control" placeholder="Email" maxlength="30" required value="<?php if(isset($_POST['email'])) echo $_POST['email'] ?>">
							</div>
						</div>

						<div class="form-group row">
							<label for="password" class="col-sm-4 col-form-label">Password:</label>
							<div class="col-sm-8">
								<input type="password" name="password" id="password" class="form-control" required maxlength="40" placeholder="Password" value="<?php if(isset($_POST['password'])) echo $_POST['password'] ?>">
								<span>Between 8 and 12 characters.</span>
							</div>
						</div>

						<div class="form-group row">
							<div class="col-sm-12">
								<input type="submit" name="submit" id="submit" class="btn btn-primary" value="Login">
							</div>
						</div>
						
					</form>
				</div><!--End of Main Content Section-->

				<!--Right-side Column Content  Section-->
				<?php 
					if(!isset($errorstring)){ //if no error occures in processin of the login details
						echo '<aside class="col-sm-2">';
						include 'info-col.php';
						echo '</aside>';
						echo '</div>'; //End of Body section div
						echo '<footer class="jumbotron text-center row col-sm-12" style="padding-bottom: 1px; padding-top: 8px;">';
					}
					else{ //An error occured during the process of the login details
						echo '<footer class="jumbotron text-center col-sm-12" style="padding-bottom:1px; padding-top: 8px;">';
					}
					include 'footer.php';
				?>

			<!--/div> --End of body section row-->
			</footer>
		</div> <!--End fo Container-->

	</body>
</html>