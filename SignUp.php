<?php

// We Have to Destroy Our Previous Session
session_start();
session_destroy();

// Check If Request is Valid Or Not
if (isset($_POST['user']) && isset($_POST['email']) && isset($_POST['pass'])) {

    // Gets User Data
    $user = $_POST['user'];
    $email = $_POST['email'];
    $pass = $_POST['pass'];

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
    $query = $conn->prepare("SELECT * FROM `users` WHERE Email = ? OR User = ?");
    $query->bind_param('ss' , $email, $user);
    $query->execute();

    // Find Results
    $results = $query->get_result();

    // User already Exists
    if($results->num_rows > 0){
        echo "<h1>User Already Exists! </h1>";
        exit(0);
    }

    // Create a New User And Save its Profile
    $query = $conn->prepare("INSERT INTO `users`(User, Email, Pass) VALUES(? , ? , ?)");
    $query->bind_param('sss' , $user, $email, $pass);
    $query->execute();

    // The Data is Saved Now Its Time to Move to Login Page
    header("Location: index.html");
}

// Request is Not Valid
else {
    echo "<h1>Invalid Request</h1>";
}
?>