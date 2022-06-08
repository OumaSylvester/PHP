<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Home</title>
		<!--Bootstrap CSS file -->
		<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css">
	</head>
	<body>
		<div class="container" style="margin-top: 30px;">
			<!--Header Section -->
			<header class="jumbotron text-center row" style="margin-bottom: 2px; background: linear-gradient(white, #0073e6); padding: 20px;"> <?php include 'includes/index-header.php' ; ?>
				
			</header>

			<!-- Body Section -->
			<div class="row" style="padding-left: 0px;">
				<!--Left-side Column Menu Section 
				<nav class="col-sm-2">
					<ul class="nav nav-pills flex-column">
						<?php //include 'includes/nav.php' ; ?>
					</ul>
				</nav> -->
			
				<!-- Center Column Content Section -->
				<div class="col-sm-10">
					<h2 class="text-center">Ambira High Alumni</h2>
					<p>Ambira High School was started in 1962. It is know to have natured   the most brilliant brains
						in the country.<br>Alumin Association consists of the old boys of the School.
					</p>
				</div>

				<!-- Right-side Column Content Section -->
				<aside class="col-sm-2">
					<?php include 'includes/info-col.php' ;?>
				</aside>
			</div>

			<!-- Footer Content Section -->
			<footer class="jumbotron text-center row" style="padding-left:1px; padding-top: 8 px; background: linear-gradient(white, #0073e6);"><?php include 'includes/footer.php'; ?>
				
			</footer>
		</div>

	</body>
</html>