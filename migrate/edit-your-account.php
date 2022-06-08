<?php
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
       // require("cap.php");
    }
    session_start();
    if(isset($_SESSION["user_id"]) && $_SESSION["user_level"] == 0)
    {
        $id = filter_var($_SESSION["user_id"], FILTER_SANITIZE_STRING);

        define("ERROR_LOG", "errors.log");
    }else{
        header("Location: login.php");
        exit();
    }
    
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Edit Your Account Page</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!--Bootstrap CSS File -->
        <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
        <script src="verify.js"></script>
        <script src="https://www.google.com/recaptcha/api.js"></script>
    </head>

    <body>
        <div class="container" style="margin-top:30px;">
            <!--Header Section -->
            <header class="jumbotron text-center row col-sm-14" style="margin-bottom:2px; background:linear-gradient(white, #0073e6); padding:20px;">
            <?php include("includes/header-members-account.php"); ?>
        
            </header>

            <!--Body Section --> 
            <div class="row" style="padding-left:0px;">
                <!--Left-side Column Menu Section -->
                <nav class="col-sm-2">
                    <ul class="nav nav-pills flex-column">
                        <?php include("includes/nav.php"); ?>
                </nav>

                <?php
                    try{
                        require('includes/mysqli_connect.php');
                        //Has the form been submitted?
                        if($_SERVER["REQUEST_METHOD"] === "POST"){
                            $errors = array();
                            //Is the title present? If it is, sanitize it
                            $title =  filter_var($_POST['title'], FILTER_SANITIZE_STRING);
                            if((!empty($title)) && (preg_match('/[a-z\.\s]/i', $title)) && (strlen($title) <= 12)){
                                //Sanitize the trimmed title
                                $titletrim = $title;
                            }else{
                                $titletrim = NULL;//Title is optional
                            }

                            //Trim the first name
                            $first_name = filter_var($_POST["first_name"], FILTER_SANITIZE_STRING);
                            if((!empty($first_name)) && (preg_match('/[a-z\s]/i', $first_name)) && (strlen($first_name) <= 30)){
                                //Sanitize the trimmed first name
                                $first_nametrim = $first_name;
                            }else{
                                $errors = "First name missing or not alphabetic and space characters. Max 30";
                            }

                            //Is the last name present? If it is, sanitize it
                            $last_name = filter_var($_POST["last_name"], FILTER_SANITIZE_STRING);
                            if((!empty($last_name)) && (preg_match('/[a-z\-\s\']/i', $last_name)) && (strlen($last_name) <= 40)){
                                //Sanitize the trimmed last name
                                $last_nametrim = $last_name;
                            }else{
                                $errors[] = "Last name missing or not alphabetic, dash, quote or space. Max 40.";
                            }

                            //Check that an email address hass been entered
                            $emailtrim = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
                            if((empty($emailtrim)) || (!filter_var($emailtrim, FILTER_VALIDATE_EMAIL)) || (strlen($emailtrim) > 60)){
                                $errors = "You forgot to enter your email address or the e-mail format is incorrect.";
                            }
                            //Is the 1st address present? If it is, sanitize it
                            $address1 = filter_var($_POST["address1"], FILTER_SANITIZE_STRING);
                            if((!empty($address1)) && (preg_match('/[a-z0-9\.\s\,\-]/i', $address1)) && (strlen($address1) <=30)){
                                //Sanitize the trimmed 1st address
                                $address1trim = $address1;
                            }else{
                                $errors[] = "Missing address. Numeric, alphabetic, period, comma, dash, space.Max 30.";
                            }

                            //Is the 2nd address present? If it is, sanitize it
                            $address2 = filter_var($_POST["address2"], FILTER_SANITIZE_STRING);
                            if((!empty($address2)) && (preg_match('/[a-z0-9\.\s\,\-]/i', $address2)) && (strlen($address2) <=30)){
                                //Sanitize the trimmed 1st address
                                $address2trim = $address2;
                            }else{
                                $address2trim = NULL;
                            }     
                            
                            //Is the city present? If it is, sanitize it
                            $city = filter_var($_POST["city"], FILTER_SANITIZE_STRING);
                            if((!empty($city)) && (preg_match('/[a-z\.\s]/i', $city)) && (strlen($city) <=30)){
                                //Sanitize the trimmed city
                                $citytrim = $city;
                            }else{
                                $errors[] = "Missing city. Only alphabetic, period and space. Max 30";
                            }

                            //Is the state or country present? If it is, sanitize it
                            $state_country = filter_var($_POST["state_country"], FILTER_SANITIZE_STRING);
                            if((!empty($state_country)) && (preg_match('/[a-z\.\s]/i', $state_country)) && (strlen($state_country) <= 30)){
                                //Sanitize trimmed state_country
                                $state_countrytrim = $state_country;
                            }else{
                                $errors[] = "Missing state/country. Only alphabetic, period and space. Max 30.";
                            }

                            //Is zip code or post code present? If it is, sanitize it
                            $zcode_pcode = filter_var($_POST["zcode_pcode"], FILTER_SANITIZE_STRING);
                            $string_length = strlen($zcode_pcode);
                            if((!empty($zcode_pcode)) && (preg_match('/[a-z0-9\s]/i', $zcode_pcode)) && ($string_length <= 30) && ($string_length >= 5)){
                                $zcode_pcodetrim = $zcode_pcode;
                            }else{
                                $errors = "Missing zip code or post code. Alphabetic, numeric, space only max 30 characters";
                            }
                            //Is the phone number present? If it is, sanitize it
                            $phone = filter_var($_POST["phone"], FILTER_SANITIZE_STRING);
                            if((!empty($phone)) && (strlen($phone) <= 30)){
                                //Sanitize the trimmed phone number
                                $phonetrim = (filter_var($phone, FILTER_SANITIZE_NUMBER_INT));
                                $phonetrim = preg_replace('/[^0-9]/', '', $phonetrim); //replace anything that it a not a number with ''
                            }else{
                                $phonetrim = NULL;
                            }

                            if(empty($errors)){
                                //if everything is OK.
                                //make the query
                                $q = mysqli_stmt_init($dbcon);
                                $query = "SELECT user_id FROM users WHERE email=? AND user_id !=?";
                                mysqli_stmt_prepare($q, $query);
                                //bind $id to SQL statement
                                mysqli_stmt_bind_param($q, "si", $emailtrim, $id);

                                //execute query
                                mysqli_stmt_execute($q);
                                $result = mysqli_stmt_get_result($q);
                                if(mysqli_num_rows($result) == 0){
                                    //e-mail does not exits in another record
                                    //Make the update query
                                    $query = "UPDATE users SET title=?, first_name=?, last_name=?, email=?, address1=?, address2=?, city=?, state_country=?, zcode_pcode=?, phone=? WHERE user_id=?";
                                    mysqli_stmt_init($dbcon);
                                    mysqli_stmt_prepare($q, $query);
                                    mysqli_stmt_bind_param($q,"ssssssssssi", $titletrim, $first_nametrim, $last_nametrim, $emailtrim, $address1trim, $address2trim, $citytrim, $state_countrytrim, $zcode_pcodetrim, $phonetrim, $id);

                                    mysqli_stmt_execute($q);
                                    if(mysqli_stmt_affected_rows($q) == 1){
                                        //Update OK
                                        //Echo a message if the edit was satified
                                        $errorstring = "The user has been edited.";
                                        echo '<p class="text-center col-sm-2" style="color: green;">' . $errorstring . '</p>';
                                    }else{
                                        //Echo a message if the query failed.
                                        $errorstring = "The user could not be edited. Did you change anything?";
                                        $errorstring .= " We appologize for any inconvenience."; //Public Message
                                        echo '<p class="text-center col-sm-2" style="color: red;">' . $errorstring . '</p>';

                                        //Debugging message
                                       // echo '<p>' . mysqli_error($dbcon) . '<br>Query: ' . $query . '</p>';
                                    }
                                }
                            }else{ //Incase of any errors on the user entry
                                //Display Errors
                                //------Process User Errors------
                                $errorstring = "Error! The following error(s) occured: ";
                                foreach($errors as $msg){
                                    //Build the error string
                                    $errorstring .= " - $msg<br>";
                                }
                                $errorstring .= "Please try again.";
                                //Print the errors
                                echo '<p class="text-center col-sm-2" style="color: red;">' . $errorstring . '</p>';
                            }//end is empty errors
                        }//end if POST

                        //Select the user's information:
                        $query = "SELECT title, first_name, last_name, email, address1, address2, city, state_country, zcode_pcode, phone FROM users WHERE user_id=?";
                        $q = mysqli_stmt_init($dbcon);
                        mysqli_stmt_prepare($q, $query);
                        mysqli_stmt_bind_param($q, "i", $id);
                        mysqli_stmt_execute($q);

                        $result = mysqli_stmt_get_result($q);
                        if(mysqli_num_rows($result) === 1){
                            //Get the user's information:
                            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                            //Create the form:
                            ?>
                            <!-- Validate Input -->
                            <div class="col-sm-8">
                                <h2 class="h2 text-center">Edit Your Account Details</h2>
                                <h3 class="text-center">For your own security please remember to log out!</h3>
                                <form action="edit-your-account.php" method="post" name="edit_form" id="edit_form">
                                    <div class="form-group row">
                                        <label for="title" class="col-sm-4 col-form-label text-right">Title:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="title" name="title" placeholder="Title" maxlength="12" pattern="[a-zA-Z][a-zA-Z\s\.]*" title="Aphabetic, period and space max 12 characters" value="<?php 
                                            if(isset($row['title'])) echo htmlspecialchars($row['title'], ENT_QUOTES);?>">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label for="first_name" class="col-sm-4 col-form-label text-right">First Name*:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name" maxlength="30" pattern="[a-zA-Z][a-zA-Z\s]*" title="Aphabetic and space max 30 characters" value="<?php 
                                            if(isset($row['first_name'])) echo htmlspecialchars($row['first_name'], ENT_QUOTES);?>">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="last_name" class="col-sm-4 col-form-label text-right">Last Name*:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name" maxlength="40" pattern="[a-zA-Z][a-zA-Z\s\-\']*" title="Aphabetic, dash, quote and space max 40  characters" value="<?php 
                                            if(isset($row['last_name'])) echo htmlspecialchars($row['last_name'], ENT_QUOTES);?>">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="email" class="col-sm-4 col-form-label text-right">Email*:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="email" name="email" placeholder="E-mail" maxlength="60"  value="<?php 
                                            if(isset($row['email'])) echo htmlspecialchars($row['email'], ENT_QUOTES);?>">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="address1" class="col-sm-4 col-form-label text-right">Address*:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="address1" name="address1" placeholder="Address" maxlength="30" pattern="[a-zA-Z0-9][a-zA-Z0-9\s\.\,\-]*" title="Aphabetic, numbers, period, comma, dash and space max 30 characters" value="<?php 
                                            if(isset($row['address1'])) echo htmlspecialchars($row['address1'], ENT_QUOTES);?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="address2" class="col-sm-4 col-form-label text-right">Address:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="address2" name="address2" placeholder="Address" maxlength="30" pattern="[a-zA-Z0-9][a-zA-Z0-9\s\.\,\-]*" title="Aphabetic, numbers, comma, period, dash and space max 30 characters" value="<?php 
                                            if(isset($row['address2'])) echo htmlspecialchars($row['address2'], ENT_QUOTES);?>">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="city" class="col-sm-4 col-form-label text-right">City*:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="city" name="city" placeholder="City" maxlength="30" pattern="[a-zA-Z][a-zA-Z\s\.]*" title="Aphabetic, period and space max 30 characters" value="<?php 
                                            if(isset($row['city'])) echo htmlspecialchars($row['city'], ENT_QUOTES);?>">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="state_country" class="col-sm-4 col-form-label text-right">Country/State*:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="state_country" name="state_country" placeholder="State or Country" maxlength="30" pattern="[a-zA-Z][a-zA-Z\s\.]*" title="Aphabetic, period and space max 30 characters" value="<?php 
                                            if(isset($row['state_country'])) echo htmlspecialchars($row['state_country'], ENT_QUOTES);?>">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="zcode_pcode" class="col-sm-4 col-form-label text-right">Zip/Postal Code*:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="zcode_pcode" name="zcode_pcode" placeholder="Zip or Postal Code" minlength="5" maxlength="30" pattern="[a-zA-Z0-9][a-zA-Z0-9\s]*" title="Aphabetic, numbers, and space max 30 characters" value="<?php 
                                            if(isset($row['zcode_pcode'])) echo htmlspecialchars($row['zcode_pcode'], ENT_QUOTES);?>">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="phone" class="col-sm-4 col-form-label text-right">Telephone:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone Number" maxlength="30"  value="<?php 
                                            if(isset($row['phone'])) echo htmlspecialchars($row['phone'], ENT_QUOTES);?>">
                                        </div>
                                    </div>
                                    <input type="hidden" name="id" value="' .$id .'">

                                    <div class="form-group row">
                                        <label for="col-sm-4 col-form-label"></label>
                                        <div class="col-sm-8">
                                            <div class="float-left g-recaptcha" style="padding-left: 80px;" data-sitekey="6LcrQ1wUAAAAAPxlrAkLuPdpY5qwS9rXF1j46fhq"></div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="" class="col-sm-4 col-form-label"></label>

                                        <div class="col-sm-8 text-center">
                                            <input type="submit" id="submit" name="submit" class="btn btn-primary" value="Edit Your Record">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        <?php
                        }//end if mysqli_num_rows
                        if(!isset($errorstring)){
                            echo '<aside class="col-sm-2">';
                            include("includes/info-col.php");
                            echo '</aside>';
                            echo '</div>'; //close the body section div
                            echo '<footer class="jumbotron text-center  col-sm-12" style="padding-bottom: 1px; padding-top:8px;">';
                        }else{
                            echo '<footer class="jumbotron text-center  col-sm-12" style="padding-bottom: 1px; padding-top:8px;">';
                        }
                        include("includes/footer.php");
                        echo '</footer>';
                        echo "</div>"; //close div container
                        
                    }//try
                    catch(Exception $e){
                        //Finally handle all problems
                        print "The system is busy please try later<br>";
                        //Error Message
                        //print "An Exception occured. Message: " . $e->getMessage() . "<br>";
                    }
                    catch(Error $e)
                    {
                        print "The system is busy please try later<br>";
                       // print "An Error occured. Message: " . $e->getMessage() . "<br>";
                    }
                ?>
            
        
    </body>
    
</html>