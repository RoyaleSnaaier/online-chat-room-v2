<?php
// Include the database connection and functions file
include_once "config.php";
include_once "functions.php";

// Check if form is submitted to delete message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_message'])) {
    $message_id = $_POST['message_id'];
    
    // Call function to delete message from database
    $deleteResult = deleteMessage($conn, $message_id);
    
    if ($deleteResult) {
        // Message deleted successfully
        echo "Message deleted successfully.";
        // Redirect or display success message
    } else {
        // Error deleting message
        echo "Error deleting message.";
        // Redirect or display error message
    }
}
?>
