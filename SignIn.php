<?php

// We Have to Destroy Our Previous Session
session_start();
session_destroy();

// Check If Request is Valid Or Not
if (isset($_POST['email']) && isset($_POST['pass'])) {

    // Gets User Data
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
    $query = $conn->prepare("SELECT * FROM `users` WHERE Email = ? AND Pass = ?");
    $query->bind_param('ss' , $email, $pass);
    $query->execute();

    // Find Results
    $results = $query->get_result();

    // User already Exists
    if($results->num_rows == 0){
        echo "<h1>User Does Not Exists! </h1>";
        exit(0);
    }

    // Now We are Sure that This User Exists Now We move to Our HomePage
    // But Before We Have to Store Our Session

    session_start();
    $UserData = $results->fetch_assoc();
    $_SESSION['isLogin'] = true;
    $_SESSION['ID'] = $UserData['ID'];
    $_SESSION['User'] = $UserData['User'];
    $_SESSION['Email'] = $email;
    $_SESSION['Pass'] = $pass;

    // The Data is Saved Now Its Time to Move to Login Page
    header("Location: Home/index.php");
}

// Request is Not Valid
else {
    echo "<h1>Invalid Request</h1>";
}
?>