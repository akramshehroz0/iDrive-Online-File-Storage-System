<?php

// Check If File Upload Request is Valid or Not
if(isset($_POST['ID'])){

    // Reterive Our User Data
    $ID = $_POST['ID'];
    $Email = $_POST['Email'];
    $Pass = $_POST['Pass'];
    $File = $_FILES['File'];

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

    // User already Exists
    if($results->num_rows == 0){
        echo "invalid user";
        exit(0);
    }

    


    // Now We know our User is Valid 
    // Now We proceed to our File Upload Part

    $fileNameWithExtension = pathinfo($File['name'], PATHINFO_BASENAME);
    $fileSize = filesize($File['tmp_name']);

    // Check if User is Allowed To Save File
    $query = $conn->prepare("SELECT SUM(Size) as SUM FROM `Files` WHERE Owner = ?");
    $query->bind_param('i', $ID);
    $query->execute();

    $results = $query->get_result();

    $TotalData = 0;
    if($results->num_rows > 0){
        $row = $results->fetch_assoc();
        $TotalData = $row['SUM'];
    }

    if($TotalData + $fileSize >= 104857600){
        echo "Not Enough Storage!";
        exit;
    }


    $extension = pathinfo($File['name'], PATHINFO_EXTENSION);
    $uniqueName = uniqid() . '.' . $extension;
    $path = 'Uploads/' . $uniqueName;
    move_uploaded_file($File['tmp_name'], $path);

    // As Our File is Uploaded Now We Store File Data in Data Base
    $query = $conn->prepare("INSERT INTO `Files`(FileName, Location, Size, Owner, isshared) VALUES(? , ? , ? , ? , 0)");
    $query->bind_param('ssii' , $fileNameWithExtension, $path, $fileSize, $ID);
    $query->execute();


    // The File is Now Uploaded
    echo "uploaded";
    exit;
}

// The Upload Request is Not Valid
else{
    echo "invalid request";
}
?>