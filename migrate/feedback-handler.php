<?php
    //Feedback form handler
    //Set the error and thank you pages
    $formurl = "feedback-form.php";
    $errorurl = "feedback/error.php";
    $thankyouurl = "feedback/thankyou.php";
    $emailerrurl = "feedback/emailerr.php";
    $errorcommenturl = "feedback/commenterr.php";
    
    //set to the email address of the recipicient
    $mailto = "oumasylvester9235@gmail.com";

    //Trim the first name
    $first_name = filter_var($_POST["first_name"], FILTER_SANITIZE_STRING);
    if((!empty($first_name)) && (preg_match('/[a-z\s]/i', $first_name)) && (strlen($first_name) <= 30)){
        //Sanitize the trimmed first name
        $first_nametrim = $first_name;
    }else{
        $errors = "yes";
    }

    //Is the last name present? If it is, sanitize it
    $last_name = filter_var($_POST["last_name"], FILTER_SANITIZE_STRING);
    if((!empty($last_name)) && (preg_match('/[a-z\-\s\']/i', $last_name)) && (strlen($last_name) <= 40)){
        //Sanitize the trimmed last name
        $last_nametrim = $last_name;
    }else{
        $errors = "yes";
    }

    //Check that an email address hass been entered
    $emailtrim = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    if((empty($emailtrim)) || (!filter_var($emailtrim, FILTER_VALIDATE_EMAIL)) || (strlen($emailtrim) > 60)){
        $errors = "yes";
    }
    //Is the 1st address present? If it is, sanitize it
    $address1 = filter_var($_POST["address1"], FILTER_SANITIZE_STRING);
    if((!empty($address1)) && (preg_match('/[a-z0-9\.\s\,\-]/i', $address1)) && (strlen($address1) <=30)){
        //Sanitize the trimmed 1st address
        $address1trim = $address1;
    }else{
        $errors = "yes";
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
        $errors = "yes";
    }

    //Is the state or country present? If it is, sanitize it
    $state_country = filter_var($_POST["state_country"], FILTER_SANITIZE_STRING);
    if((!empty($state_country)) && (preg_match('/[a-z\.\s]/i', $state_country)) && (strlen($state_country) <= 30)){
        //Sanitize trimmed state_country
        $state_countrytrim = $state_country;
    }else{
        $errors = "yes";
    }

    //Is zip code or post code present? If it is, sanitize it
    $zcode_pcode = filter_var($_POST["zcode_pcode"], FILTER_SANITIZE_STRING);
    $string_length = strlen($zcode_pcode);
    if((!empty($zcode_pcode)) && (preg_match('/[a-z0-9\s]/i', $zcode_pcode)) && ($string_length <= 30) && ($string_length >= 5)){
        $zcode_pcodetrim = $zcode_pcode;
    }else{
        $errors = "yes";
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

    $brochure = filter_var($_POST['brochure'], FILTER_SANITIZE_STRING);
    if($brochure != "yes"){$brochure = "no";} //if not yes, then no
    $letter =  filter_var($_POST['letter'], FILTER_SANITIZE_STRING);
    if($letter != "yes"){$letter = "no";} //if not yes, then noo

    $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
    if((!empty($comment)) && (strlen($comment) <= 480)){
        //remove ability to reduce to create link in email
        $patterns = array("/http/","/https/", "/\:/", "/\/\//", "/www./");
        $commenttrim = preg_replace($patterns, " ", $comment);
    }else{
        //if comment not valid display error page
        header("Location: $errorcommenturl");
        exit();
    }

    //echo $errors;
    if(!empty($errors)){
        //If errors display error page
        header("Location: $errorurl");
        exit();    
    }
    //everything OK send e-mail
    $subject = "Message from customer " . $first_nametrim . " " . $last_nametrim;

    $messageproper = "-----------------------------------------------------------\n";
    "Name of sender: $first_nametrim $last_nametrim\n".
    "Email of sender: $emailtrim\n".
    "Telephone: $phonetrim\n".
    "brochure?: $brochure\n".
    "Address: $address1trim\n".
    "Address: $address2trim\n".
    "City: $citytrim\n".
    "Newsletter?: $letter\n".
    "-------------------------MESSAGE-----------------------------\n\n".
    $commenttrim .
    "\n\n----------------------------------------------------------\n";
    mail($mailto, $subject, $messageproper, "From: \"$first_nametrim $last_nametrim\"<$emailtrim> ");
    //header("Location: $thankyouurl");


?>