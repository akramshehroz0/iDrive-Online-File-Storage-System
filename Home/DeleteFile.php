<?php

// Check If File Upload Request is Valid or Not
if(isset($_POST['ID'])){

    // Reterive Our User Data
    $ID = $_POST['ID'];
    $Email = $_POST['Email'];
    $Pass = $_POST['Pass'];

    // First We Check If Our User is Valid Or Not
    // Connects to Our Server
    $servername = "localhost";
    $username = "";
    $password = "";
    $database = "";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $database);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check if Username or Email Already Exists
    $query = $conn->prepare("SELECT * FROM `users` WHERE Email = ? AND Pass = ?");
    $query->bind_param('ss' , $Email, $Pass);
    $query->execute();

    // Find Results
    $results = $query->get_result();

    // User Does Not Exists
    if($results->num_rows == 0){
        echo "invalid user";
        exit(0);
    }

    

    // Owner ID
    $row = $results->fetch_assoc();
    $OwnerID = $row['ID'];


    // Find the File
    // Get the File Location
    $query = $conn->prepare("SELECT * FROM `Files` WHERE ID = ? and Owner = ?");
    $query->bind_param('ii' , $ID , $OwnerID);
    $query->execute();
    $results = $query->get_result();
    $path = "";

    if($results->num_rows > 0){

        $row = $results->fetch_assoc();
        $path = $row['Location'];
        
    }
    else{

        echo "File Cannot be Deleted!";
        exit;

    }

    // Now We Delete File From Sql Server
    $query = $conn->prepare("DELETE FROM `Files` WHERE ID = ? and Owner = ?");
    $query->bind_param('ii' , $ID , $OwnerID);
    $query->execute();
    
    if(unlink($path)) echo "uploaded";


}

// The Upload Request is Not Valid
else{
    echo "invalid request";
}
?>