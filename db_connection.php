<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ousl_book_store";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

//new mysqli(...) is a constructor that takes the server name, username, password, and database name as arguments. 
//It returns a mysqli object, stored in $conn, which will be used for database interactions.

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);//die(...) stops further execution of the script and outputs an error message
}

?>