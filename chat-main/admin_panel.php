<?php
session_start();

// Check if the user is not logged in or not an admin, redirect to login page
if (!isset($_SESSION['unique_id']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit();
}

// Include the database connection and functions file
include_once "config.php";
include_once "functions.php";

// Handle logout
if (isset($_POST['logout'])) {
    // Unset all of the session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to the login page
    header("Location: login.php");
    exit();
}

// Include the database connection
include_once "config.php";

// Function to update the timeout status of a user
function updateUserTimeoutStatus($conn, $unique_id, $is_timed_out) {
    // Prepare the SQL statement
    $sql = "UPDATE users SET is_timed_out = ? WHERE unique_id = ?";
    $stmt = $conn->prepare($sql);
    
    // Bind the parameters
    $stmt->bind_param("ii", $is_timed_out, $unique_id);
    
    // Execute the statement
    $result = $stmt->execute();
    
    // Close the statement
    $stmt->close();
    
    // Return true if the update was successful, false otherwise
    return $result;
}



// Handle admin actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['target_id'])) {
        $action = $_POST['action'];
        $target_id = $_POST['target_id'];

        // Perform the selected action
        if ($action === 'delete_message') {
            deleteMessage($conn, $target_id);
        } elseif ($action === 'ban_user') {
            banUser($conn, $target_id);
        } elseif ($action === 'timeout_user') {
            // Set user timeout status to true
            updateUserTimeoutStatus($conn, $target_id, 1);
        } elseif ($action === 'delete_user') {
            deleteUser($conn, $target_id);
        } elseif ($action === 'delete_image') {
            deleteImage($conn, $target_id);
        } elseif ($action === 'unban_user') {
            unbanUser($conn, $target_id);
        } elseif ($action === 'untimeout_user') {
            // Set user timeout status to false
            updateUserTimeoutStatus($conn, $target_id, 0);
        }
    }
}
if (isset($_POST['delete_all_messages'])) {
    deleteAllMessages($conn);
}

// Retrieve chat messages from the database
$messages = getMessages($conn);

// Retrieve all users from the database
$users = getAllUsers($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="admin-panel-1.css">
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
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>

<div class="container">
    <div class="wrapper">
        <h2>Chat Messages</h2>
        <div class="admin-section" id="chat-messages">
            <table>
                <tr>
                    <th>User</th>
                    <th>Message</th>
                    <th>Image</th>
                    <th>Timestamp</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($messages as $message): ?>
                    <tr>
                        <td><?php echo $message['fname'] . ' ' . $message['lname']; ?></td>
                        <td><?php echo htmlspecialchars($message['message']); ?></td>
                        <td>
                            <?php if ($message['image']): ?>
                                <img src="<?php echo $message['image']; ?>" alt="Chat Image" class="chat-image"/>
                            <?php else: ?>
                                <span>No Image</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $message['timestamp']; ?></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="action" value="delete_message">
                                <input type="hidden" name="target_id" value="<?php echo $message['message_id']; ?>">
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <form method="post">
            <div class="input-container">
                <input type="hidden" class="admin-btn" name="delete_all_messages" value="true">
                <button type="submit">Delete All Messages</button>
            </div>
        </form>
    </div>

    <div class="wrapper">
        <h2>Users</h2>
        <div class="admin-section">
            <table>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['fname'] . ' ' . $user['lname']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td><?php echo $user['status']; ?></td>
                        <td><img src="img/<?php echo $user['img']; ?>" alt="User Image" class="profile-pic"></td>
                        <td>
                            <form method="post">
                                <div class="input-container">
                                    <select name="action">
                                        <option value="ban_user">Ban</option>
                                        <option value="unban_user">Unban</option>
                                        <option value="timeout_user">Timeout</option>
                                        <option value="untimeout_user">Untimeout</option>
                                        <option value="delete_user">Delete User</option>
                                    </select>
                                </div>
                                <div class="input-container">
                                    <input type="hidden" name="target_id" value="<?php echo $user['unique_id']; ?>">
                                    <button type="submit">Submit</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</div>

</body>
<script src="admin_panel.js"></script>
<script src="mous.js"></script>
<script src="index.js"></script>
</html>
