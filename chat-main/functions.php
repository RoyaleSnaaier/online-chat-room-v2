<?php
// Function to upload files
function uploadFile($file, $type) {
    if ($file && $file['error'] === UPLOAD_ERR_OK) {
        $targetDir = 'uploads/';
        $targetFile = $targetDir . basename($file['name']);
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check file type and size (add more validations as needed)
        if (($type === 'image' && getimagesize($file['tmp_name']) !== false) ||
            ($type === 'audio' && in_array($fileType, array('mp3', 'wav', 'ogg'))) ||
            ($type === 'video' && in_array($fileType, array('mp4', 'avi', 'mov'))) &&
            $file['size'] <= 10485760) { // Max file size: 10MB (10 * 1024 * 1024)
            if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                return $targetFile;
            }
        }
    }
    return null;
}
function deleteAllMessages($conn) {
    // SQL to delete all messages
    $sql = "DELETE FROM chat_messages";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        echo "All messages deleted successfully";
    } else {
        echo "Error deleting messages: " . $conn->error;
    }
}
// Function to insert message into database
function insertMessage($conn, $user_id, $message, $timestamp, $imagePath, $videoPath) {
    $user_id = mysqli_real_escape_string($conn, $user_id);
    $message = mysqli_real_escape_string($conn, $message);
    $timestamp = mysqli_real_escape_string($conn, $timestamp);
    $imagePath = mysqli_real_escape_string($conn, $imagePath);
    $videoPath = mysqli_real_escape_string($conn, $videoPath); // Add video path
    
    $sql = "INSERT INTO chat_messages (user_id, message, timestamp, image, video) 
            VALUES ('$user_id', '$message', '$timestamp', '$imagePath', '$videoPath')"; // Update query
    
    return mysqli_query($conn, $sql);
}


// Function to retrieve messages from the database along with user details
function getMessages($conn) {
    $messages = array();
    $sql = "SELECT chat_messages.*, users.fname, users.lname 
            FROM chat_messages 
            JOIN users ON chat_messages.user_id = users.unique_id 
            ORDER BY chat_messages.timestamp ASC";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $messages[] = $row;
        }
    }
    return $messages;
}

// Function to update user status and is_banned in the database
function updateUserStatus($conn, $user_id, $status, $is_banned) {
    $status = mysqli_real_escape_string($conn, $status);
    $is_banned = mysqli_real_escape_string($conn, $is_banned);
    
    $sql = "UPDATE users SET status = '$status', is_banned = '$is_banned' WHERE unique_id = '$user_id'";
    $result = mysqli_query($conn, $sql);
    return $result;
}

// Function to get the status of a user
function getUserStatus($conn, $user_id) {
    $sql = "SELECT status FROM users WHERE unique_id = '$user_id'";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        return $user['status'];
    }
    return null;
}

// Ban a user
function banUser($conn, $user_id) {
    return updateUserStatus($conn, $user_id, 'banned', 1); // Assuming 1 represents true for is_banned
}

// Unban a user
function unbanUser($conn, $user_id) {
    return updateUserStatus($conn, $user_id, 'active', 0); // Assuming 0 represents false for is_banned
}

// Function to delete a message from the database
function deleteMessage($conn, $message_id) {
    $sql = "DELETE FROM chat_messages WHERE message_id = '$message_id'";
    $result = mysqli_query($conn, $sql);
    return $result;
}

// Function to delete an image from the server and database
function deleteImage($conn, $image_id) {
    // Retrieve image path from the database
    $sql = "SELECT image FROM chat_messages WHERE id = '$image_id'";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $imagePath = $row['image'];
        
        // Delete image file from the server
        if (unlink($imagePath)) {
            // Delete image record from the database
            $deleteSql = "UPDATE chat_messages SET image = NULL WHERE id = '$image_id'";
            $deleteResult = mysqli_query($conn, $deleteSql);
            return $deleteResult;
        }
    }
    return false;
}

// Function to retrieve all users from the database
function getAllUsers($conn) {
    $users = array();
    $sql = "SELECT * FROM users";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $users[] = $row;
        }
    }
    return $users;
}

// Function to delete a user from the database
function deleteUser($conn, $user_id) {
    $sql = "DELETE FROM users WHERE unique_id = '$user_id'";
    $result = mysqli_query($conn, $sql);
    return $result;
}

?>
