<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['unique_id'])) {
    // If not logged in, redirect to login page
    header("Location: login.php");
    exit(); // Stop further execution
}

// Include the database connection and configuration file
include_once "config.php";

// Fetch user information from the database based on the unique_id stored in the session
$sql = "SELECT * FROM users WHERE unique_id = '{$_SESSION['unique_id']}'";
$result = mysqli_query($conn, $sql);
if ($result && mysqli_num_rows($result) > 0) {
    $userInfo = mysqli_fetch_assoc($result);
} else {
    // Handle error if user information is not found
    echo "Error: Unable to fetch user information.";
    exit();
}

// Update user profile if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Change password
    if (isset($_POST['change_password'])) {
        // Collect form data
        $currentPassword = mysqli_real_escape_string($conn, $_POST['current_password']);
        $newPassword = mysqli_real_escape_string($conn, $_POST['new_password']);
        $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirm_password']);

        // Check if the current password matches the password stored in the database
        $passwordCheckSql = "SELECT * FROM users WHERE unique_id = '{$_SESSION['unique_id']}'";
        $passwordCheckResult = mysqli_query($conn, $passwordCheckSql);
        if ($passwordCheckResult && mysqli_num_rows($passwordCheckResult) > 0) {
            $user = mysqli_fetch_assoc($passwordCheckResult);
            $hashedPassword = $user['password'];
            if (password_verify($currentPassword, $hashedPassword)) {
                // Current password matches, now update the password
                if ($newPassword === $confirmPassword) {
                    // New password and confirm password match
                    $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $updatePasswordSql = "UPDATE users SET password = '$hashedNewPassword' WHERE unique_id = '{$_SESSION['unique_id']}'";
                    $updatePasswordResult = mysqli_query($conn, $updatePasswordSql);
                    if ($updatePasswordResult) {
                        // Password updated successfully
                        $successMessage = "Password updated successfully.";
                    } else {
                        // Handle error if password update fails
                        $errorMessage = "Error: Unable to update password. Please try again.";
                    }
                } else {
                    // New password and confirm password do not match
                    $errorMessage = "Error: New password and confirm password do not match.";
                }
            } else {
                // Current password does not match
                $errorMessage = "Error: Current password is incorrect.";
            }
        } else {
            // Handle error if user information is not found
            $errorMessage = "Error: Unable to fetch user information.";
        }
    }

    // Update profile
    if (isset($_POST['update_profile'])) {
        // Collect form data
        $dob = mysqli_real_escape_string($conn, $_POST['dob']);
        $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        $location = mysqli_real_escape_string($conn, $_POST['location']);
        $interests = mysqli_real_escape_string($conn, $_POST['interests']);

        // Update user profile in the database
        $updateSql = "UPDATE users SET dob = '$dob', gender = '$gender', location = '$location', interests = '$interests' WHERE unique_id = '{$_SESSION['unique_id']}'";
        $updateResult = mysqli_query($conn, $updateSql);
        if ($updateResult) {
            // Profile updated successfully
            header("Location: profile.php");
            exit();
        } else {
            // Handle error if profile update fails
            $errorMessage = "Error: Unable to update profile. Please try again.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="profile-3.css">
    <!-- Add any CSS styles or external stylesheets here -->
</head>
<body>
<div class="cursor"></div>
<div class="cursor2"></div>


<div id="bubble" class="bubble-1"></div>
<div class="bubble-2"></div>
<div class="bubble-3"></div>
<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="#">Users</a></li>
        <li><a href="#">Messages</a></li>
    </ul>
    <ul>
    <li class="user-menu">
        <div class="menu">
            <ul>
            <li><a href="profile.php">Profile</a></li> <!-- Link to profile page -->
                <li><a href="logout.php">Logout</a></li>
                <img class="avatar" src="img/<?php echo $userInfo['img']; ?>" alt="Profile Picture" class="profile-pic">
            </ul>
        </div>
        
    </li>
</ul>
</nav>
    <div class="">
    <h1>User Profile</h1>
        <!-- <p>Welcome, <?php echo $userInfo['fname']; ?>!</p> -->
        <div class="parent">
        
        <div class="wrapper-1">
        <h2>User Details</h2>
        <ul>
            <li><strong>First and last name:</strong> <?php echo $userInfo['fname'] . ' ' . $userInfo['lname']; ?></li>
            <li><strong>Email:</strong> <?php echo $userInfo['email']; ?></li>
            <li><strong>Status:</strong> <?php echo $userInfo['status']; ?></li>
            <li><strong>Date of Birth:</strong> <?php echo $userInfo['dob']; ?></li>
            <li><strong>Gender:</strong> <?php echo $userInfo['gender']; ?></li>
            <li><strong>Location:</strong> <?php echo $userInfo['location']; ?></li>
            <li><strong>Interests:</strong> <?php echo $userInfo['interests']; ?></li>
            <li><strong>Password:</strong> <?php echo $userInfo['password']; ?></li>
            
        </ul>   
        </div>

        <!-- Change Password Form -->
        <div class="wrapper-3">
                <h2>Change Password</h2>
                <form action="" method="post">
                    <div class="form-group">
                        <label for="current_password">Current Password:</label>
                        <input type="password" id="current_password" name="current_password" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password">New Password:</label>
                        <input type="password" id="new_password" name="new_password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password:</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    <input type="submit" name="change_password" value="Change Password">
                </form>
                <?php
                // Display success or error message for change password
                if (isset($successMessage)) {
                    echo "<p class='success-message'>$successMessage</p>";
                } elseif (isset($errorMessage)) {
                    echo "<p class='error-message'>$errorMessage</p>";
                }
                ?>
            </div>

        <!-- Edit Profile Form -->
        <div class="wrapper-2">
        <h2>Edit Profile</h2>
        <form action="" method="post">
            <div class="form-group">
                <label for="fname">First Name:</label>
                <input type="text" id="fname" name="fname" value="<?php echo $userInfo['fname']; ?>">
            </div>
            <div class="form-group">
                <label for="lname">Last Name:</label>
                <input type="text" id="lname" name="lname" value="<?php echo $userInfo['lname']; ?>">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $userInfo['email']; ?>">
            </div>
            <div class="form-group">
                <label for="dob">Date of Birth:</label>
                <input type="date" id="dob" name="dob" value="<?php echo $userInfo['dob']; ?>">
            </div>
            <div class="form-group">
                <label for="gender">Gender:</label>
                <select id="gender" name="gender">
                    <option value="Male" <?php if($userInfo['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                    <option value="Female" <?php if($userInfo['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                    <option value="Other" <?php if($userInfo['gender'] == 'Other') echo 'selected'; ?>>Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="location">Location:</label>
                <input type="text" id="location" name="location" value="<?php echo $userInfo['location']; ?>">
            </div>
            <div class="form-group">
                <label for="interests">Interests:</label>
                <input type="text" id="interests" name="interests" value="<?php echo $userInfo['interests']; ?>">
            </div>
            <input type="submit" name="update_profile" value="Save Changes">
        </form>
        </div>
    </div>
    </div>
    <script src="mous.js"></script>
    <script src="profile.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/TextPlugin.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/CSSRulePlugin.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/Draggable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/EaselPlugin.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/MotionPathPlugin.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/PixiPlugin.min.js"></script>
    </body>
</html>
