<?php                                                                       
session_start();
if (!isset($_SESSION['user_level']) || ($_SESSION['user_level'] != 1))
{ header("Location: login.php");
  exit();
}
?>
 <!DOCTYPE html>
<html lang="en">
<head>
  <title>Edit Record</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- Bootstrap CSS File -->
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
</head>
<body>
<div class="container" style="margin-top:30px">
<!-- Header Section -->
<header class="jumbotron text-center row"
style="margin-bottom:2px; background:linear-gradient(white, #0073e6); padding:20px;"> 
  <?php include('includes/admin-header.php'); ?>
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
<?php 
require ("process-edit-address.php");
?>
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
