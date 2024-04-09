<?php
// Include the database connection and functions file
include_once "config.php";
include_once "functions.php";

// Check if form is submitted to delete image
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_image'])) {
    $image_id = $_POST['image_id'];
    
    // Call function to delete image from database
    $deleteResult = deleteImage($conn, $image_id);
    
    if ($deleteResult) {
        // Image deleted successfully
        echo "Image deleted successfully.";
        // Redirect or display success message
    } else {
        // Error deleting image
        echo "Error deleting image.";
        // Redirect or display error message
    }
}
?>
