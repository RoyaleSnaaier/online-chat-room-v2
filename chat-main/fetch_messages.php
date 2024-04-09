<?php
// Include the database connection and functions file
include_once "config.php";
include_once "functions.php";

try {
    // Fetch messages from the database
    $stmt = $pdo->query("SELECT message, timestamp, image FROM messages ORDER BY timestamp DESC");
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output the messages as JSON
    header('Content-Type: application/json');
    echo json_encode($messages);
} catch (PDOException $e) {
    // Handle database connection errors
    http_response_code(500);
    echo json_encode(array("error" => "Database error: " . $e->getMessage()));
}
?>

