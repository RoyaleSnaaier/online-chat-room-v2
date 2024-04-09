<?php
session_start();

// Check if the user is already logged in, if yes, redirect them to the chat room
if (isset($_SESSION['unique_id'])) {
    header('Location: index.php');
    exit();
}

// Include the database connection and configuration file
include_once "config.php";

require_once 'mysql.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitizeInput($_POST['username']);
    $password = sanitizeInput($_POST['password']);
    
    // Perform login authentication
    // Use sanitized $username and $password for database query
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get email and password from the form
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Validate input fields
    if (!empty($email) && !empty($password)) {
        // Fetch user details from the database
        $sql = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
        if (mysqli_num_rows($sql) > 0) {
            $row = mysqli_fetch_assoc($sql);
            $user_pass = md5($password);
            $enc_pass = $row['password'];

            // Verify the password
            if ($user_pass === $enc_pass) {
                // Check if the user is banned
                if ($row['is_banned']) {
                    // Redirect banned users to banned.php
                    header('Location: banned.php');
                    exit();
                }

                $status = "Active now";
                $sql2 = mysqli_query($conn, "UPDATE users SET status = '{$status}' WHERE unique_id = {$row['unique_id']}");
                if ($sql2) {
                    // Set session variables for unique_id, fname, and lname
                    $_SESSION['unique_id'] = $row['unique_id'];
                    $_SESSION['fname'] = $row['fname'];
                    $_SESSION['lname'] = $row['lname'];
                    $_SESSION['is_admin'] = $row['is_admin']; // Add admin flag to session

                    // Check if user is admin
                    if ($_SESSION['is_admin']) {
                        // Redirect to admin panel
                        header('Location: admin_panel.php');
                        exit(); 
                    } else {
                        // Redirect to index.php for non-admin users
                        header('Location: index.php');
                        exit();
                    }
                } else {
                    $error = "Something went wrong. Please try again!";
                }
            } else {
                $error = "Email or Password is Incorrect!";
            }
        } else {
            $error = "$email - This email does not exist!";
        }
    } else {
        $error = "All input fields are required!";
    }
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style-log-in-1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
</head>
<body>
<div class="cursor"></div>
<div class="cursor2"></div>


<div id="bubble" class="bubble-1"></div>
<div class="bubble-2"></div>
<div class="bubble-3"></div>

<div class="wrapper">
    <section class="form login">
      <header>Realtime Chat App</header>
      <form action="#" method="POST" enctype="multipart/form-data" autocomplete="off">
        <div class="error-text">This is a error message!</div>
        <div class="field input">
          <label>Email Address</label>
          <input type="text" name="email" placeholder="Enter your email" required>
        </div>
        <div class="field input">
          <label>Password</label>
          <input type="password" name="password" placeholder="Enter your password" required>
          <i class="fas fa-eye"></i>
        </div>
        <div class="field button">
          <input type="submit" name="submit" value="Continue to Chat">
        </div>
      </form>
      <div class="link">Not yet signed up? <a href="sign-up.php">Signup now</a></div>
    </section>
  </div>
<script src="log-in-1.js"></script>
<script src="pass-show-hide.js"></script>
</body>
</html>