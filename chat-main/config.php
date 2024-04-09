<?php
// Establish a database connection
$servername = "sql11.freesqldatabase.com";
$username = "sql11697773";
$password = "uDQdPEPKSR";
$dbname = "sql11697773";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // Modify the error message to display the correct information
    die("Connection failed: " . $conn->connect_error);
}
?>
