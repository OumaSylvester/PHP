<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Template for an interactive web page</title>
		<!--Bootstrap CSS file -->
		<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css">
	</head>
	<body>
		<div class="container" style="margin-top: 30px;">
			<!--Header Section -->
			<header class="jumbotron text-center row" style="margin-bottom: 2px; background: linear-gradient(white, #0073e6); padding: 20px;"> <?php include 'index-header.php' ; ?>
				
			</header>

			<!-- Body Section -->
			<div class="row" style="padding-left: 0px;">
				<!--Left-side Column Menu Section -->
				<nav class="col-sm-2">
					<ul class="nav nav-pills flex-column">
						<?php include 'nav.php' ; ?>
					</ul>
				</nav>

				<!-- Center Column Content Section -->
				<div class="col-sm-8">
					<h2 class="text-center">This is the Home Page</h2>
					<p>The home page content. The home page content. This is repetetive. This is repetetive. Very very repetetive<br>
					The home page content. The home page content. The home page content
					</p>
				</div>

				<!-- Right-side Column Content Section -->
				<aside class="col-sm-2">
					<?php include 'info-col.php' ;?>
				</aside>
			</div>

			<!-- Footer Content Section -->
			<footer class="jumbotron text-center row" style="padding-left:1px; padding-top: 8 px; background: linear-gradient(white, #0073e6);"><?php include 'footer.php'; ?>
				
			</footer>
		</div>

	</body>
</html>