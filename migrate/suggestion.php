<?php
    
    require("includes/mysqli_connect.php");
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if($conn->connect_error) die("Fatal Error");

    $query = "SELECT * FROM users";
    
    $result = $conn->query($query);

    if(!$result) die("Fatal Error");
    
    $author_names = Array(); //create empty array

    /*Populate $author_names with the values from the author field of 
    classics table. At the end of this operation all the author names in
    the table will be in the array $author_names.
     */

     while($row=mysqli_fetch_array($result, MYSQLI_ASSOC))
     {
         $first_names[] = $row['first_name'];
         $last_names[] = $row['last_name'];

     }

     $result->close();
     $conn->close();

     //get the q parameter from URL

     $q = $_REQUEST['q']; //get the query
     $field = $_REQUEST['field']; //collect the field

     
     //lookup all hints from the array if  $q is different from ""

     function findMatch($names, $q)
     {
        $hint = ""; //initalize $hint variable as asn empty string

        if($q !== "")
        {
            $q = strtolower($q);
            $len=strlen($q);
            foreach($names as $name){
                if(stristr($q, substr($name, 0, $len))){
                    if($hint == ""){
                        $hint = $name;
                    }else{
                        $hint .= ", $name";
                    }
                }
            }
        }

        return $hint;
     }

     
     if($field == 'f')
     {
        $hint = findMatch($first_names, $q);
     }else{
         $hint = findMatch($last_names, $q);
     }
     

    

     //Output "no suggestion" if no hint was found or  ouput corect values
     echo $hint === "" ? "no suggestion" : $hint;

?>