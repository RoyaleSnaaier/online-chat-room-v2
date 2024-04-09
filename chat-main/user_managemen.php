<?php
session_start();

// Include the database connection and functions file
include_once "config.php";
include_once "functions.php";

// Check if the user is not logged in or not an admin, redirect to login page
if (!isset($_SESSION['unique_id']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit();
}

// Handle logout
if (isset($_POST['logout'])) {
    // Unset all of the session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to the login page
    header("Location: login.php");
    exit();
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle user management actions
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        $user_id = $_POST['user_id'];

        // Perform user action based on the action type
        if ($action === 'delete') {
            $deleteResult = deleteUser($conn, $user_id);
            if ($deleteResult) {
                // User deleted successfully
                echo "User deleted successfully.";
            } else {
                // Error deleting user
                echo "Error deleting user.";
            }
        } elseif ($action === 'timeout') {
            // Implement timeout logic here
        } elseif ($action === 'ban') {
            // Implement ban logic here
        } elseif ($action === 'edit') {
            // Implement edit user logic here
        }
    }
}

// Retrieve all users from the database
$users = getAllUsers($conn);
?>
