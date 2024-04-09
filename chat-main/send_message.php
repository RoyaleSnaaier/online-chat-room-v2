<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if message data is present
    if (isset($_POST["message"])) {
        // Process the message data
        $message = $_POST["message"];
        $imagePath = '';

        // Check if an image is uploaded
        if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/'; // Directory where images will be stored
            $imagePath = $uploadDir . basename($_FILES["image"]["name"]);

            // Move the uploaded image to the designated directory
            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)) {
                // Failed to move the uploaded image
                http_response_code(500);
                echo json_encode(array("success" => false, "message" => "Failed to upload image"));
                exit;
            }
        }

        // Save the message and image path to the database
        // Replace this with your database insertion logic

        // Debug statements
        echo json_encode(array("success" => true, "message" => "Message sent successfully"));
        exit; // Make sure to exit after sending response
    } else {
        // Message data is missing
        http_response_code(400);
        echo json_encode(array("success" => false, "message" => "Message data is missing"));
    }
} else {
    // Invalid request method
    http_response_code(405);
    echo json_encode(array("success" => false, "message" => "Invalid request method"));
}
?>
