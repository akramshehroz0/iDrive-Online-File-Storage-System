<?php

// This Funtion will Convert Bytes into MB, GB or Kb
function formatSizeUnits($bytes)
{
    if ($bytes >= 1073741824)
    {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    }
    elseif ($bytes >= 1048576)
    {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    }
    elseif ($bytes >= 1024)
    {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    }
    elseif ($bytes > 1)
    {
        $bytes = $bytes . ' bytes';
    }
    elseif ($bytes == 1)
    {
        $bytes = $bytes . ' byte';
    }
    else
    {
        $bytes = '0 bytes';
    }

    return $bytes;
}


// Get Our Store Session
session_start();

// Means User is Already Loged in 
if (isset($_SESSION['isLogin'])) {

    // Retreive Our User Information
    $ID = $_SESSION['ID'];
    $Email = $_SESSION['Email'];
    $Pass = $_SESSION['Pass'];


    // First We Check If Our User is Valid Or Not
    // Connects to Our Server
    $servername = "localhost";
    $username = "id20140498_root";
    $password = "T&/TKTg8xNrDpKyo";
    $database = "id20140498_web";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $database);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check if Username or Email Already Exists
    $query = $conn->prepare("SELECT * FROM `users` WHERE Email = ? AND Pass = ?");
    $query->bind_param('ss', $Email, $Pass);
    $query->execute();

    // Find Results
    $results = $query->get_result();

    // User already Exists
    if ($results->num_rows == 0) {
        echo "invalid user";
        exit(0);
    }

    // Check User Total Storage Location
    $query = $conn->prepare("SELECT SUM(Size) as SUM FROM `Files` WHERE Owner = ?");
    $query->bind_param('i', $ID);
    $query->execute();

    $results = $query->get_result();

    $TotalData = 0;
    if($results->num_rows > 0){
        $row = $results->fetch_assoc();
        $TotalData = $row['SUM'];
    }


    // Link Our Css File
    echo '  <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>iDrive - Home</title>
                <link rel="shortcut icon" href="Images/siteLogo.png" type="image/x-icon">
                <link rel="stylesheet" href="style.css">
            </head>
    
            <body>';

    // Display Our Navigation Bar
    // Display Our Navigation Bar
    echo '  
            <div class="loading" id="loading"> <div id="loader" class="center"></div> </div>
            <header>
            <nav class="navbar">
                <img src="Images/siteLogo.png" alt="Company Logo">
                <h1>Welcome to iDrive</h1>
                <div class="navbar-right">
                    <a href="Logout.php">Logout</a>
                </div>
            </nav>
            </header>';


    // Display Our Navigation Bar
    echo '  <header>
            <nav class="userInfoBar">
                <h3>Storage Usage: ' . formatSizeUnits($TotalData) . '  of 100 MB</h3>
                <div class="navbar-right">
                    <button onClick="UpLoadFile(' . $_SESSION['ID'] . ",'" . $Email . "'," . $Pass;

    if($TotalData >= 104857600){
        echo "disabled";
    }

    echo ')">Upload File</button>
                </div>
            </nav>
            </header>';


    echo '
    <div class="filesDisplay">
    <table class="styled-table">
    <thead>
        <tr>
            <th>File Name</th>
            <th>Size</th>
            <th>Total Usage</th>
            <th class="centerClass">Operations</th>
        </tr>
    </thead>
    <tbody>';

    // get All Files of User
    $query = $conn->prepare("SELECT * FROM `Files` WHERE Owner = ?");
    $query->bind_param('i', $ID);
    $query->execute();

    // Find Results
    $results = $query->get_result();

    // get All Rows
    if ($results->num_rows > 0) {
        // output data of each row
        while ($row = $results->fetch_assoc()) {
            echo '
            <tr>
                    <td>'.$row['FileName'].'</td>
                    <td>'. formatSizeUnits($row['Size']) .'</td>
                    <td>' . round(($row['Size'] / $TotalData) * 100) . ' %</td>
                    <td class="centerClass">
                        <span class="btn" onclick="downloadURI(' ."'".$row['Location']. "' , '". $row['FileName']."'". ')"> <img class="oppIcon" src="Images/downloadIcon.png"></span>
                        <span class="btn" onclick="copyLink(' . "'https://sherozakram.000webhostapp.com/Home/" . $row['Location']. "'" . ')"><img class="oppIcon"  src="Images/shareIcon.png"></span>
                        <span class="btn" onClick="DeleteFile(' . $row['ID'] . ",'" . $Email . "'," . $Pass . ')"><img class="oppIcon"  src="Images/deleteIcon.png"></span>
                    </td>
            </tr>';
        }
    }
    echo '</tbody>
    </table>
    </div>';


    echo '          <script src="script.js"></script>
                </body>
            </html>';
}

// Redirect User to Login Page
else {
    session_destroy();
    header("Location: ../index.html");
    exit;
}

?>