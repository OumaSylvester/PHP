<?php
	try{
		//After clicking the link in the found-record.php/admin-view-users.php
		//this code is executed
		//The code looks for a valid user ID through GET or POST
		if(isset($_GET['id']) && is_numeric($_GET['id']))
		{
			//From view-users.php/found-record.php
			$id  = htmlspecialchars($_GET['id'], ENT_QUOTES);

		}elseif (isset($_POST['id']) && is_numeric($_POST['id'])) {
			//Form submmission
			$id = htmlspecialchars($_POST['id'], ENT_QUOTES);
		}else{
			//No Valid id kill the script
			echo '<p class="text-center">This page has been accessed in error.</p>';
			include 'footer.php';
			exit();
		}

		require('includes/mysqli_connect.php');
		//Has the form been submitted?
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$errors = array();

            $title = filter_var($_POST['title'],FILTER_SANITIZE_STRING);

            if((!empty($title)) &&(preg_match('/[a-z\.\s]/i' , $title)) && (strlen($title) <= 12))
            {
                //sanitize the trimmed title
                $titletrim = $title;
            }
            else{
                $titletrim = NULL;
            }

			//Look for the first name
			$first_name = filter_var($_POST['first_name'], FILTER_SANITIZE_STRING);
            if((!empty($first_name)) &&(preg_match('/[a-z\-\s\']/i' , $first_name)) && (strlen($first_name) <= 30))
			
            {
            //sanitize trimmed first name
            $first_nametrim = $first_name;
            }
            else

			{
				$errors[] = "first name missing or not alphabetic and space characters.";
			}

			$last_name = filter_var($_POST['last_name'], FILTER_SANITIZE_STRING);
			if((!empty($last_name)) &&(preg_match('/[a-z\.\s]/i' , $last_name)) && (strlen($last_name) <= 40))
			{
                //sanitize last name
                $last_nametrim = $last_name;
            }
            else
            {
				$errors[] = "Last name missing or not alphabetic ,dash,quote or space.max30";
			}

			$address1 = filter_var($_POST['address1'], FILTER_SANITIZE_STRING);
			if((!empty($address1)) && (preg_match('/[a-z0-9\.\s\,\-]/i',$address1))&& (strlen($address1) <= 30 )) 
            {
                //sanitize trimmed address
                $address1trim = $address1;
            }
            else
            {
				$address1trim = NULL;
			}

			$address2 = filter_var($_POST['address2'], FILTER_SANITIZE_STRING);
			if((!empty($address2)) && (preg_match('/[a-z0-9\.\s\,\-]/i',$address2))&& (strlen($address2) <= 30 )) 
			{
                $address2trim =$address2;
			}
            else
            {
                $address2trim = NULL;
            }

			$city = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
			if((!empty($city)) && (preg_match('/[a-z\.\s]/i',$city))&& (strlen($city) <= 30 )) 
			{
				$citytrim = $city;
			}
            else
            {
                $errors[] = 'missing city.only alphabets,period and space.max30';
            }

			$state_country = filter_var($_POST['state_country'], FILTER_SANITIZE_STRING);
			if((!empty($state_country)) && (preg_match('/[a-z\.\s]/i',$state_country))&& (strlen($state_country) <= 30 )) 
			{
				$state_countrytrim = $state_country;
			}
            else
            {
                $errors[] = 'missing state/country.only alphabets,period and space.max30';
            }

            //is zip code present?
            $zcode_pcode = filter_var($_POST['zcode_pcode'], FILTER_SANITIZE_STRING);
			if((!empty($zcode_pcode)) && (preg_match('/[a-z0-9\s]/i',$zcode_pcode))&& (strlen($zcode_pcode) <= 30)&&(strlen($zcode_pcode) >=5)) 
			{
				$zcode_pcodetrim = $zcode_pcode;
			}
            else
            {
                $errors[] = 'missing zip-post code.only Allphabetic, numeric, space.Max 30 characters';
            }
            //Is the phone number present? If it is, sanitize it
            $phone = filter_var( $_POST['phone'], FILTER_SANITIZE_STRING);
            if ((!empty($phone)) && (strlen($phone) <= 30)) {
                //Sanitize the trimmed phone number
                $phonetrim = (filter_var($phone, FILTER_SANITIZE_NUMBER_INT));
                $phonetrim = preg_replace('/[^0-9]/', '', $phonetrim);
            }else{
                $phonetrim = NULL;
            }

			if(empty($errors)){//All data entered by the admin was valid
				
            //email does not exists in a another record

                //The update query. Make changes to the database
                $query = "UPDATE  users SET title=?, first_name=?, last_name=?, address1=?, address2=?, city=?, state_country=?, zcode_pcode=?, phone=? WHERE user_id=? LIMIT 1";
                //bind values to sql statements
                $q = mysqli_stmt_init($dbcon);
                mysqli_stmt_prepare($q, $query);
                mysqli_stmt_bind_param($q, 'sssssssssi', $titletrim, $first_nametrim, $last_nametrim, $address1trim, $address2trim, $citytrim, $state_countrytrim,$zcode_pcodetrim, $phonetrim, $id);
                //execute query
                mysqli_stmt_execute($q);

                if(mysqli_stmt_affected_rows($q) == 1){
                    //update OK. echo a message if the edit was satisfactory
                    echo '<p class="text-center">The user has been edited</p>';
                    //add a 5 seconds wait before redirection to the admin-view-users.php

                    header("Location: admin-view-users.php");
                }else{
                    //echo a message if the query failed
                    echo '<p class="text-center">The user was not edited.</p>';
                    //There is a bug if  the admin clicks the edit link and changes nothing. No row is affected hence this error message is displayed. 
                    //The error could also be due to sql query errors
                    //Debug message

                    //echo '<br><p>' . mysqli_error($dbcon) . '<br>Query: ' . $query . '</p>';
                }
            
			}else{
				//An error occured in the data entered. Display the errors
				echo '<p class="text-center">The following error(s) occured: <br>';
				foreach($errors as $msg){
					//echo each error
					echo " - $msg <br>";
				}
				echo 'Please try again';
			}//end if empty($errors)

		}//end if POST
		
		//Select the user's information to display in the text boxes
		$query = "SELECT * FROM users WHERE user_id=?";
		$q = mysqli_stmt_init($dbcon);
		mysqli_stmt_prepare($q, $query);
		mysqli_stmt_bind_param($q, "i", $id);
		mysqli_stmt_execute($q);

		$result = mysqli_stmt_get_result($q);
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

		if(mysqli_num_rows($result) == 1){
			//valid user ID display the form
			//Get the users information
			//Create the form
		
?>
			<h2 class="h2">Edit User</h2>
            <h3>Items marked with an asterik * are required</h3>
            
                <form action="edit-address.php" method="post"
                name="editform" id="editform">
                <div class="form-group row">
                    <label for="title" class="col-sm-4 col-form-label text-right">Title:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="title" name="title" 
                    placeholder="Title" maxlength="12"
                    pattern='[a-zA-Z][a-zA-Z\s\.]*' 
                    title="Alphabetic, period and space max 12 characters"
                    value=
                        "<?php if (isset($row['title'])) 
                        echo htmlspecialchars($row['title'], ENT_QUOTES); ?>" >
                    </div>
                </div>
                <div class="form-group row">
                    <label for="first_name" class="col-sm-4 col-form-label text-right">First Name*:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="first_name" name="first_name" 
                    pattern="[a-zA-Z][a-zA-Z\s]*" title="Alphabetic and space only max of 30 characters"
                    placeholder="First Name" maxlength="30" required
                    value=
                        "<?php if (isset($row['first_name'])) 
                        echo htmlspecialchars($row['first_name'], ENT_QUOTES); ?>" >
                    </div>
                </div>
                <div class="form-group row">
                    <label for="last_name" class="col-sm-4 col-form-label text-right">Last Name*:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="last_name" name="last_name" 
                    pattern="[a-zA-Z][a-zA-Z\s\-\']*" 
                    title="Alphabetic, dash, quote and space only max of 40 characters"
                    placeholder="Last Name" maxlength="40" required
                    value=
                        "<?php if (isset($row['last_name'])) 
                        echo htmlspecialchars($row['last_name'], ENT_QUOTES); ?>" >
                    </div>
                </div>
                <div class="form-group row">
                    <label for="address1" class="col-sm-4 col-form-label text-right">Address*:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="address1" name="address1" 
                    pattern="[a-zA-Z0-9][a-zA-Z0-9\s\.\,\-]*" 
                    title="Alphabetic, numbers, period, comma, dash and space only max of 30 characters" 
                    placeholder="Address" maxlength="30" required
                    value=
                        "<?php if (isset($row['address1'])) 
                        echo htmlspecialchars($row['address1'], ENT_QUOTES); ?>" >
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
                        "<?php if (isset($row['address2'])) 
                        echo htmlspecialchars($row['address2'], ENT_QUOTES); ?>" >
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
                        "<?php if (isset($row['city'])) 
                        echo htmlspecialchars($row['city'], ENT_QUOTES); ?>" >
                    </div>
                </div>
                <div class="form-group row">
                    <label for="state_country" class="col-sm-4 col-form-label text-right">
                    Country/state*:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="state_country" name="state_country"
                    pattern="[a-zA-Z][a-zA-Z\s\.]*" 
                    title="Alphabetic, period and space only max of 30 characters" 
                    placeholder="State or Country" maxlength="30" required
                    value=
                        "<?php if (isset($row['state_country'])) 
                        echo htmlspecialchars($row['state_country'], ENT_QUOTES); ?>" >
                    </div>
                </div>	
                <div class="form-group row">
                    <label for="zcode_pcode" class="col-sm-4 col-form-label text-right">
                    Zip/Postal Code*:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="zcode_pcode" name="zcode_pcode" 
                    pattern="[a-zA-Z0-9][a-zA-Z0-9\s]*" 
                    title="Alphabetic, period and space only max of 30 characters" 
                    placeholder="Zip or Postal Code" minlength="5" maxlength="30" required
                    value=
                        "<?php if (isset($row['zcode_pcode'])) 
                        echo htmlspecialchars($row['zcode_pcode'], ENT_QUOTES); ?>" >
                    </div>
                </div>		
                <div class="form-group row">
                    <label for="phone" class="col-sm-4 col-form-label text-right">Telephone:</label>
                    <div class="col-sm-8">
                    <input type="tel" class="form-control" id="phone" name="phone" 
                    placeholder="Phone Number" maxlength="30"
                    value=
                        "<?php if (isset($row['phone'])) 
                        echo htmlspecialchars($row['phone'], ENT_QUOTES); ?>" >
                    </div>
                </div>  
                <input type="hidden" name="id" value="<?php echo $id ?>" />
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label"></label>
                    <div class="col-sm-8">
                        <div class="float-left g-recaptcha" data-sitekey="6LcrQ1wUAAAAAPxlrAkLuPdpY5qwS9rXF1j46fhq"></div>
                    </div>
                </div>

				<div class="form-group row">
					<label for="" class="col-sm-8 col-form-label"></label>
					<div class="col-sm-4">
						<input type="submit" name="submit" id="submit" value="Confirm" class="btn btn-primary">
					</div>
					
				</div>
				<!--add space btwn submit button and last input field -->
			</form>
<?php 
		}else{//The user could not be validated
			echo '<p class="text-center">This page has been accessed in error.</p>';
		}
		//Free results and close the database connection
		mysqli_stmt_free_result($q);
		mysqli_close($dbcon);
	//}// GET request 

	}//end try
	catch(Exception $e){
		print 'The system is busy please try again later<br>';
		//print 'An Exception occured. Message: ' . $e->getMessage() '<br>';
	}
	catch(Error $e){
		print 'The system is busy please try again later<br>';
		print 'An error occured. Message: ' . $e->getMessage() . '<br>';
	}
?>