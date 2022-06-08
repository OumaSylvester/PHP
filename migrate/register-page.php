<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Register</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!--Bootstrap CSS File -->
		<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css">
		<script type="text/javascript" src="verify.js"></script>
		<script src='https://www.google.com/recaptcha/api.js'></script>   
		
	</head>
	<body>
		<div class="container" style="margin-top: 30px;">
			<!--Header Section -->
			<header class="jumbotron text-center row" style="margin-bottom: 2px; background: linear-gradient(white, #0073e6); padding: 20px;"><?php include 'includes/register-header.php'; ?>
				
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
						require ('process-register-page2.php');
					}// End of the main Submit conditional.
				?>
				<div class="col-sm-8">
					<h2 class="h2 text-center">Register</h2>
					<h3>Items marked with an asterik * are required</h3>
					<?php 
						try{
							require_once("includes/mysqli_connect.php");
							$query = "SELECT * FROM prices";
							$result = mysqli_query($dbcon, $query);
							if($result){//If it ran OK, display  the records.
								$row = mysqli_fetch_array($result, MYSQLI_NUM);
								$yearsarray = array(
									"Standard one year:", "Standard five year:",
									"Military one year:", "Under 21 One Year:",
									"Other - Give what you can. Maybe: "
								);
								echo '<h6 class="text-center text-danger">Membership classes:</h6>';
								echo '<h6 class="text-center text-danger small"> ';
								//j loops the yearsarray and i loops the row array
								for($j=0, $i=0; $j<5; $j++, $i=$i+2){
									//i incremented by 2 to skip to the next category of payment. There is a category for GB  and US
									echo $yearsarray[$j] . ' &pound; ' . htmlspecialchars($row[$i], ENT_QUOTES) . ' GB, &dollar; ' . htmlspecialchars($row[$i + 1], ENT_QUOTES) . ' US';
									if($j != 4){
										if($j % 2 == 0){
											echo '</h6><h6 class="text-center text-danger small"> ';
										}else{
											echo ', ';
										}
									}
								}
								echo '</h6>'; //close the last h6 >> the fifth one
							}
						
						?>

					<form action="register-page.php" method="post" onsubmit="return checked();" name="regform" id="regfrom">

					<div class="form-group row">
    					<label for="title" class="col-sm-4 col-form-label text-right">Title:</label>
    					<div class="col-sm-8">
    						<input type="text" class="form-control" id="title" name="title" placeholder="Title" maxlength="12" pattern='[a-zA-Z][a-zA-Z\s\.]*'  title="Alphabetic, period and space max 12 characters"
	  						 value="<?php if (isset($_POST['title'])) 
							echo htmlspecialchars($_POST['title'], ENT_QUOTES); ?>" >
   						</div>
 					</div>
						<div class="form-group row">
							<label for="first_name" class="col-sm-4 col-form-label">*First Name:</label>
							<div class="col-sm-8">
								<input type="text" name="first_name" class="form-control" id="first_name" title="Alphabetic and space only max of 30 characters" 
								pattern="[a-zA-Z][a-zA-Z\s]*" placeholder="First Name" maxlength="30" required value="<?php if(isset($_POST['first_name'])) echo htmlspecialchars($_POST['first_name'], ENT_QUOTES);?>">
							</div>
						</div>
						<div class="form-group row">
							<label for="last_name" class="col-sm-4 col-form-label">*Last Name:</label>
							<div class="col-sm-8">
								<input type="text" name="last_name" class="form-control" id="last_name" pattern="[a-zA-Z][a-zA-Z\s]*" title="Alphabetic and space only max of 40 characters"
								 placeholder="Last Name" maxlength="40" required value="<?php if(isset($_POST['last_name'])) echo htmlspecialchars($_POST['last_name'], ENT_QUOTES);?>">
							</div>
						</div>

						<div class="form-group row">
							<label for="email" class="col-sm-4 col-form-label">*E-mail:</label>
							<div class="col-sm-8">
								<input type="email" name="email" class="form-control" id="email" placeholder="E-mail" maxlength="60" required value="<?php if(isset($_POST['email'])) echo htmlspecialchars($_POST['email'], ENT_QUOTES);?>">
							</div>
						</div>

						<div class="form-group row">
							<label for="password1" class="col-sm-4 col-form-label">*Password:</label>
							<div class="col-sm-8">
								<input type="password" name="password1" class="form-control"
								 id="password1" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8, 12}"
								 title="One number, one upper, one lower, one special with 8 to 12 characters" placeholder="Password" minlength="8" required value="<?php if(isset($_POST['password1'])) echo htmlspecialchars($_POST['password1'], ENT_QUOTES);?>">
								<span id="message">Between 8 and 12 characters.</span>
							</div>
						</div>

						<div class="form-group row">
							<label for="password2" class="col-sm-4 col-form-label text-right">Confirm Password*:</label>
							<div class="col-sm-8">
								<input type="password" class="form-control" id="password2" name="password2"
								pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,12}" 
								title="One number, one uppercase, one lowercase letter, with 8 to 12 characters"
								placeholder="Confirm Password" minlength="8" maxlength="12" required
								value=
									"<?php if (isset($_POST['password2'])) 
									echo htmlspecialchars($_POST['password2'], ENT_QUOTES); ?>" >
							</div>
 						</div>

						<div class="form-group row">
							<label for="level" class="col-sm-4 col-form-label">*Membership Class</label>
							<div class="col-sm-8">
								<select id="level" name="level" class="form-control" required>
									<option value="0">--Select--</option>
								<?php 
									for($j=0, $i=0; $j<5; $j++, $i+=2){
										echo '<option value="'. htmlspecialchars($row[$i], ENT_QUOTES) . '" ';
										if(isset($_POST['level']) && $_POST['level'] == $row[$i]){
											?>
											selected
											<?php 
										}
										echo '>' . $yearsarray[$j] . ' '. htmlspecialchars($row[$i], ENT_QUOTES) . " &pound; GB, " . htmlspecialchars($row[$i + 1], ENT_QUOTES) . " &dollar; US</option>";
									}
								?>
								</select>
							</div>
							
						</div>
						<div class="form-group row">
							<label for="address1" class="col-sm-4 col-form-label text-right">Address*:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="address1" name="address1" 
									pattern="[a-zA-Z0-9][a-zA-Z0-9\s\.\,\-]*" 
									title="Alphabetic, numbers, period, comma, dash and space only max of 30 characters" 
									placeholder="Address" maxlength="30" required
									value="<?php if (isset($_POST['address1'])) 
									echo htmlspecialchars($_POST['address1'], ENT_QUOTES); ?>" >
							</div>
 						</div>
						 <div class="form-group row">
							<label for="address2" class="col-sm-4 col-form-label text-right">Address:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="address2" name="address2" 
									pattern="[a-zA-Z0-9][a-zA-Z0-9\s\.\,\-]*" 
									title="Alphabetic, numbers, period, comma, dash and space only max of 30 characters" 
									placeholder="Address" maxlength="30"
									value=
										"<?php if (isset($_POST['address2'])) 
										echo htmlspecialchars($_POST['address2'], ENT_QUOTES); ?>" >
							</div>
  						</div>
						
						<div class="form-group row">
							<label for="city" class="col-sm-4 col-form-label text-right">City*:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="city" name="city" 
								pattern="[a-zA-Z][a-zA-Z\s\.]*" 
								title="Alphabetic, period and space only max of 30 characters" 
								placeholder="City" maxlength="30" required
								value=
									"<?php if (isset($_POST['city'])) 
									echo htmlspecialchars($_POST['city'], ENT_QUOTES); ?>" >
							</div>
 						</div>
						 <div class="form-group row">
							<label for="state_country" class="col-sm-4 col-form-label text-right">Country/state*:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="state_country" name="state_country"
								pattern="[a-zA-Z][a-zA-Z\s\.]*" 
								title="Alphabetic, period and space only max of 30 characters" 
								placeholder="State or Country" maxlength="30" required
								value=
									"<?php if (isset($_POST['state_country'])) 
									echo htmlspecialchars($_POST['state_country'], ENT_QUOTES); ?>" >
							</div>
  						</div>

						<div class="form-group row">
							<label for="zcode_pcode" class="col-sm-4 col-form-label text-right">Zip/Postal Code*:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="zcode_pcode" name="zcode_pcode" 
								pattern="[a-zA-Z0-9][a-zA-Z0-9\s]*" 
								title="Alphabetic, period and space only max of 30 characters" 
								placeholder="Zip or Postal Code" minlength="5" maxlength="30" required
								value=
									"<?php if (isset($_POST['zcode_pcode'])) 
									echo htmlspecialchars($_POST['zcode_pcode'], ENT_QUOTES); ?>" >
    						</div>
  						</div>

						<div class="form-group row">
							<label for="phone" class="col-sm-4 col-form-label text-right">Telephone:</label>
							<div class="col-sm-8">
								<input type="tel" class="form-control" id="phone" name="phone" 
								placeholder="Phone Number" maxlength="30"
								value=
									"<?php if (isset($_POST['phone'])) 
									echo htmlspecialchars($_POST['phone'], ENT_QUOTES); ?>" >
							</div>
  						</div>

						<div class="form-group row">
							<label for="question" class="col-sm-4 col-form-label text-right">Secret Question*:</label>
							<div class="col-sm-8">
								<select id="question" name="question" class="form-control">
									<option selected value="">- Select -</option>
									<option value="Maiden">Mother's Name</option>
									<option value="Pet">Pet's Name</option>
									<option value="School">High School</option>
									<option value="Vacation">Favorite Vocation Spot</option>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label for="secret" class="col-sm-4 col-form-label text right">Answer*:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="secret" name="secret"
									pattern="[a-z-A-Z][a-zA-Z\s\.\,\-]*" title="Alphabetic, period, comma, dash and space only max of 30 characters" placeholder="Secret Answer"
									maxlength="30" required value="<?php if(isset($_POST['secret']))
									echo htmlspecialchars($_POST['secret'], ENT_QUOTES);?>" >
							</div>
						</div>
						
						<div class="form-group row">
							<label class="col-sm-4 col-form-label"></label>
							<div class="col-sm-8">
								<div class="float-left g-recaptcha" data-sitekey="6LcrQ1wUAAAAAPxlrAkLuPdpY5qwS9rXF1j46fhq"></div>
							</div>

						</div>
						<div class="form-group row">
							<label for="" class="col-sm-4 col-form-label"></label>
							<div class="col-sm-8">
								<input type="submit" name="submit" id="submit" class="btn btn-primary" value="Register">
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
						
					}else{//we don't display the aside info-col if there are errors. We use the 2 small collumns to display the errors
						echo '<footer class="jumbotron text-center col-sm-12" style="padding-bottom:1px; padding-top:8px;">';
						
					}
					include 'includes/footer.php';
					echo '</footer>';
					echo '</div>';
					
					
			}
			catch(Exception $e) //We finally handle any problems here
			{
				//print "An Exception occurred. Message: " . $e->getMessage() . '<br>';
				print 'The system is busy pleae try later';
			}
			catch(Error $e){
				//print 'An Error occures. Message: ' . $e->getMessage() . '<br>';
				print 'The system is busy pleae try later';
			}
			?>
	</body>
</html>