<?php
	session_start();
	if(!isset($_SESSION['user_level']) || $_SESSION['user_level'] != 1)
	{
		header("Location: login.php");
		exit();
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Search page</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!--Bootstrap CSS File -->
		<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css">
		<script type="text/javascript" src="verify.js"></script>
		
	</head>
	<body>
		<div class="container" style="margin-top: 30px;">
			<!--Header Section -->
			<header class="jumbotron text-center row" style="margin-bottom: 2px; background: linear-gradient(white, #0073e6); padding: 20px;"><?php include 'includes/search-header.php'; ?>
				
			</header>


			<!--Body Section -->
			<div class="row" style="padding-left:0px;">
				<!--Left-side Colunm Menu Section -->
				<nav class="col-sm-2">
					<ul class="nav nav-pills flex-column">
						<?php include 'includes/nav.php'; ?>
					</ul>
				</nav>

				<!-- Validate Input -->
				<?php 
					if($_SERVER['REQUEST_METHOD'] == 'POST'){
						require ('process-view-found-addresses.php');
					}// End of the main Submit conditional.
				?>
				<div class="col-sm-8">
					<h2 class="h2 text-center">Search for an Adress or Phone Number</h2>
					<h5 class="text-center" style="color: red;">Both names are required items</h6>

					<form action="view-found-address.php" method="post" onsubmit="return checked();" name="searchform" id="searchfrom">
						<div class="form-group row">
							<label for="first_name" class="col-sm-4 col-form-label">First Name:</label>
							<div class="col-sm-8">
								<input type="text" name="first_name" class="form-control" id="first_name" placeholder="First Name" maxlength="30" required value="<?php if(isset($_POST['first_name'])) echo htmlspecialchars($_POST['first_name'], ENT_QUOTES);?>">
							</div>
						</div>
						<div class="form-group row">
							<label for="last_name" class="col-sm-4 col-form-label">Last Name:</label>
							<div class="col-sm-8">
								<input type="text" name="last_name" class="form-control" id="last_name" placeholder="Last Name" maxlength="40" required value="<?php if(isset($_POST['last_name'])) echo htmlspecialchars($_POST['last_name'], ENT_QUOTES);?>">
							</div>
						</div>

						
						<div class="form-group row" style="padding-top:5px;">
                            <label  for="" class="col-sm-4 col-form-label"></label>
							<div class="col-sm-8">
								<input type="submit" name="submit" id="submit" class="btn btn-primary" value="Search">
							</div>
						</div>
						
					</form>
				</div> <!--End of div col-sm-8 for the form -->
				<!--Right-side Column Content Section-->
				<?php
					if(!isset($errorstring)){
						echo '<aside class="col-sm-2">';
						include 'includes/info-col.php';
						echo '</aside>';
						echo '</div>'; //close the div of body section(the middle section of the page containing the left nav links, the form and the rigt info)
						//include the footer
						echo '<footer class="jumbotron text-center row col-sm-12"
							style="padding-bottom:1px; padding-top:8px;">';
						include("includes/footer.php");
					}else{//we don't display the aside info-col if there are errors. We use the 2 small collumns to display the errors
						echo '<footer class="jumbotron text-center col-sm-12" style="padding-bottom:1px; padding-top:8px;">';
						include('includes/footer.php');
					}
					
				?>			
			</footer>
		</div>

	</body>
</html>