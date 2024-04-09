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

// Check if a session is not already active before starting a new one
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Initialize $imagePath with an empty string to prevent "Undefined variable" warning
$imagePath = '';

if (!isset($user_id)) {
    $user_id = ''; // You can initialize it with an appropriate default value
}
// Initialize $message with a default value if it's not already initialized
if (!isset($message)) {
    $message = ''; // You can initialize it with an appropriate default value
}
// Initialize $timestamp with the current timestamp
$timestamp = date('Y-m-d H:i:s');

// Initialize $audioPath with a default value (e.g., empty string)
$audioPath = '';

// Initialize $videoPath with a default value (e.g., empty string)
$videoPath = '';

// Prepare the SQL statement
$sql = "INSERT INTO chat_messages (user_id, message, timestamp, image_path, audio_path, video_path) 
        VALUES ('$user_id', '$message', '$timestamp', '$imagePath', '$audioPath', '$videoPath')";

// Function to sanitize input data
function sanitizeInput($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = mysqli_real_escape_string($conn, htmlspecialchars($data, ENT_QUOTES, 'UTF-8'));
    return $data;
}

// Function to validate email format
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Function to validate username format
function validateUsername($username) {
    // Implement your own validation logic for usernames
    // Example: Only allow alphanumeric characters and underscores
    return preg_match("/^[a-zA-Z0-9_]*$/", $username);
}

// You can add more validation functions as per your requirements
?>
