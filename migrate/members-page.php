<?php
	session_start();
	if(!isset($_SESSION['user_level']) or ($_SESSION['user_level'] != 0))
	{
		header("Location: login.php");
		exit();
	} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Members</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- Bootstrap CSS File -->
  <link href="../bootstrap/css/bootstrap.css" rel="stylesheet">
</head>
<body>
<div class="container" style="margin-top:30px">
<!-- Header Section -->
<header class="jumbotron text-center row"
style="margin-bottom:2px; background:linear-gradient(white, #0073e6); padding:20px;"> 
  <?php include('includes/members-header.php'); ?>
</header>
<!-- Body Section -->
  <div class="row" style="padding-left: 0px;">
<!-- Left-side Column Menu Section -->
  <nav class="col-sm-2">
      <ul class="nav nav-pills flex-column">
		<?php include('includes/nav.php'); ?> 
      </ul>
  </nav>
<!-- Center Column Content Section -->
<div class="col-sm-8">
<h2 class="text-center">Members' Offer</h2>
<p>The department of skill and talent development started a project in clothing design and Texttile Manufucturing.
<br>Here is a one of the T-shirts designed by one of our students. It shows how talented our young stars are.
<br>
</p>
<p class="h3 text-center">Get the T-shirt.</p>
    <p class="text-center"><strong>T-Shirts Ksh 2000</strong></p>
<img class="mx-auto d-block" src="images/polo.png" alt="Polo Shirt"> 
<br>
</div>
<!-- Right-side Column Content Section -->
	<aside class="col-sm-2">
      <?php include('includes/info-col.php'); ?> 
	</aside>
  </div>
<!-- Footer Content Section -->
<footer class="jumbotron text-center row"
style="padding-bottom:1px; padding-top:8px;">
  <?php include('includes/footer.php'); ?>
</footer>
</div>
</body>
</html>

