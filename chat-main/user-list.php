<?php
// Start the session to access session variables
session_start();

// Check if the user is logged in
if (!isset($_SESSION['unique_id'])) {
    // Redirect to the login page if not logged in
    header('Location: login.php');
    exit();
}

// Include the database connection file
require_once 'mysql.php';

// Fetch list of users from the database
$sql = "SELECT * FROM users";
$result = mysqli_query($conn, $sql);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <!-- Link the CSS file -->
    <link rel="stylesheet" type="text/css" href="style-user-list.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="user-list.php">Users</a></li>
                <li><a href="#">Messages</a></li>
            </ul>
            <ul>
                <li class="user-menu">
                    <div class="menu">
                        <ul>
                            <li><a href="profile.php">Profile</a></li>
                            <li><a href="logout.php">Logout</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="container">
        <h1 class="tittle">User List</h1>
        <div class="wrapper">
            <section class="user-list">
                <?php
                // PHP code to fetch and display user list goes here
                // Check if any users are found
                if (mysqli_num_rows($result) > 0) {
                    // Output the list of users
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<article class='user-card'>";
                        echo "<h2>User ID: " . $row['unique_id'] . "</h2>";
                        echo "<p><strong>Name:</strong> " . $row['fname'] . " " . $row['lname'] . "</p>";
                        echo "<p><strong>Email:</strong> " . $row['email'] . "</p>";
                        echo "</article>";
                    }
                } else {
                    // If no users are found, display a message
                    echo "<p>No users found.</p>";
                }
                ?>
            </section>
        </div>
        </div>
    </main>
</body>
</html>
