<?php
require_once "mysql.php";

// Example query to test database connection
$sql = "SELECT * FROM users LIMIT 1"; // Change 'your_table_name' to an actual table in your database

$result = $conn->query($sql);

if ($result) {
    // If the query was successful
    if ($result->num_rows > 0) {
        // Output data of the first row
        $row = $result->fetch_assoc();
        echo "Test query successful. Example data: " . $row["email"]; // Change 'column_name' to an actual column in your table
    } else {
        echo "No results found.";
    }
} else {
    // If there was an error with the query
    echo "Error: " . $conn->error;
}

// Close the database connection
$conn->close();
?>
