<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include_once 'config.php';

// Check if database connection is successful
if ($conn->connect_error) {
    header("Location: error.php?error=Failed to connect to the database");
    exit();
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate form inputs
    $fname = $_POST['fname'] ?? '';
    $lname = $_POST['lname'] ?? '';
    $email = $_POST['email'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $location = $_POST['location'] ?? '';
    $interests = $_POST['interests'] ?? '';

    // Prepare and bind SQL statement
    $stmt = $conn->prepare("UPDATE users SET fname=?, lname=?, email=?, dob=?, gender=?, location=?, interests=? WHERE user_id=?");
    $stmt->bind_param("sssssssi", $fname, $lname, $email, $dob, $gender, $location, $interests, $_SESSION['user_id']);

    // Execute SQL statement
    if ($stmt->execute()) {
        // Redirect to profile page with success message
        header("Location: profile.php?status=Profile updated successfully");
        exit();
    } else {
        // Redirect to profile page with error message
        header("Location: profile.php?error=Failed to update profile");
        exit();
    }
} else {
    // If form is not submitted, redirect to profile page
    header("Location: profile.php");
    exit();
}
?>
