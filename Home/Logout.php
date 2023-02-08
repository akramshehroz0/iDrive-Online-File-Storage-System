<?php

// First We Destroy Our session
session_start();
session_destroy();

// Move to Login Page
header("Location: ../index.html")
?>