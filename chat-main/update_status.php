<?php
// Include the database connection
include_once "config.php";

// Check if the status is provided in the POST request
if (isset($_POST['status'])) {
    // Get the user ID from the session or any other method you use to identify the user
    session_start();
    if (isset($_SESSION['unique_id'])) {
        $user_id = $_SESSION['unique_id'];

        // Sanitize and validate the status value
        $allowed_statuses = ['Online', 'Away', 'Offline']; // Define allowed statuses
        $status = mysqli_real_escape_string($conn, $_POST['status']);
        if (!in_array($status, $allowed_statuses)) {
            // Invalid status value
            echo "Invalid status value";
            exit();
        }

        // Update the user status in the database
        $sql = "UPDATE users SET status = '$status' WHERE unique_id = '$user_id'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            // Status updated successfully
            echo "Status updated successfully";
        } else {
            // Failed to update status
            echo "Failed to update status";
        }
    } else {
        // User ID not available in session
        echo "User ID not available";
    }
} else {
    // Status not provided in the request
    echo "Status not provided";
}
?>
