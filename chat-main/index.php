<?php
// In your chat page
session_start();
require_once 'mysql.php';



// Example usage: Sanitize input from chat message form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = isset($_POST['message']) ? sanitizeInput($_POST['message']) : '';
    
    // Perform chat message processing
    // Use sanitized $message for database insertion
}

// Establish a database connection
$servername = "sql11.freesqldatabase.com";
$username = "sql11697773";
$password = "uDQdPEPKSR";
$dbname = "sql11697773";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // Modify the error message to display the correct information
    die("Connection failed: " . $conn->connect_error);
}

// Include the functions file
include_once "functions.php";

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['unique_id'])) {
    header('Location: login.php');
    exit();
}

// Check if the user is banned, redirect to banned.php
$user = getUserData($conn, $_SESSION['unique_id']);
if ($user && $user['is_banned']) {
    header('Location: banned.php');
    exit();
}

function getUserData($conn, $user_id) {
    $user_id = mysqli_real_escape_string($conn, $user_id);
    
    $sql = "SELECT * FROM users WHERE unique_id = '$user_id'";
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    } else {
        return false;
    }
}

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


// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get message content
    $message = $_POST['message'] ?? '';

    // Get uploaded image file
    $image = $_FILES['image'] ?? null;

    // Get uploaded audio file
    $audio = $_FILES['audio'] ?? null;

    // Validate message content
    if (!empty($message) || !empty($image['name']) || !empty($audio['name'])) {
        // Prepare timestamp
        $timestamp = date('Y-m-d H:i:s');
        
        // Prepare user_id
        $user_id = $_SESSION['unique_id'];
        
        // Prepare image path
        $imagePath = null;

        // Prepare audio path
        $audioPath = null;

        // Handle image upload
        if (!empty($image['name'])) {
            $targetDir = 'uploads/';
            $targetFile = $targetDir . basename($image['name']);
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            // Check if file is a valid image
            $validExtensions = array('jpg', 'jpeg', 'png', 'gif');
            if (in_array($imageFileType, $validExtensions)) {
                // Save uploaded image
                if (move_uploaded_file($image['tmp_name'], $targetFile)) {
                    $imagePath = $targetFile;
                }
            }
        }

        // Handle audio upload
        if (!empty($audio['name'])) {
            $targetDir = 'uploads/';
            $targetFile = $targetDir . basename($audio['name']);
            $audioFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            // Check if file is a valid audio
            $validExtensions = array('mp3', 'wav', 'ogg');
            if (in_array($audioFileType, $validExtensions)) {
                // Save uploaded audio
                if (move_uploaded_file($audio['tmp_name'], $targetFile)) {
                    $audioPath = $targetFile;
                }
            }
        }

        // Insert message into the database
        $insertResult = insertMessage($conn, $user_id, $message, $timestamp, $imagePath, $audioPath, null); // Add $audioPath parameter
        
        if ($insertResult) {
            // Message inserted successfully
            echo "Message inserted successfully.";
            // Redirect to prevent form resubmission
            header("Location: index.php");
            exit();
        } else {
            // Error inserting message
            echo "Error inserting message.";
        }
    }
}

// Assuming you have already included your connection file (mysql.php)
include_once "mysql.php";

// Fetch user information from the database and set the variables
$fname = ""; // Initialize variables
$lname = ""; 
$userStatus = ""; 

// Fetch user information from the database
$user_id = $_SESSION['unique_id']; // Assuming you have the user's unique ID stored in session
$sql = "SELECT fname, lname, status FROM users WHERE unique_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$stmt->bind_result($fname, $lname, $userStatus);
$stmt->fetch();
$stmt->close();

// Retrieve chat messages from the database
$messages = getMessages($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Room</title>
    <link rel="stylesheet" href="style-index-8.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
</head>
<body>
    
<div class="cursor"></div>
<div class="cursor2"></div>


<div id="bubble" class="bubble-1"></div>
<div class="bubble-2"></div>
<div class="bubble-3"></div>
<nav>
    <ul>
        <li class="user-menu"><a href="index.php">Chat</a></li>
    </ul>
    <ul>
    <li class="user-menu">
        <div class="menu">
            <ul>
            <li><a href="profile.php">Profile</a></li> <!-- Link to profile page -->
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </li>
    <li class="user-menu">
        <img class="avatar" src="img/<?php echo $user['img']; ?>" alt="Profile Picture" class="profile-pic">
        
    </li>
</ul>
</nav>


<div class="wrapper">
    <h1 class="chat-title">Chat Room</h1>
        <div class="chat-box">
            <?php foreach ($messages as $message): ?>
                <div class="message">
                    <div class="user-info">
                        <span class="user"><?php echo $message['fname'] . ' ' . $message['lname']; ?></span>
                        <span class="status <?php echo $userStatus === 'Online' ? 'online' : ($userStatus === 'Away' ? 'away' : 'offline'); ?>"><?php echo $userStatus; ?></span>
                    </div>
                    <div class="message-content <?php echo $message['user_id'] === $user_id ? 'right' : 'left'; ?>">
                        <?php if (!empty($message['message'])): ?>
                            <span class="content"><?php echo htmlspecialchars($message['message'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <?php endif; ?>
                        <?php if (!empty($message['image'])): ?>
                            <!-- Display text above image -->
                            <div class="image-text"></div>
                            <img src="<?php echo $message['image']; ?>" alt="Image">
                        <?php endif; ?>
                        <?php if (!empty($message['audio'])): ?>
                            <!-- Adjust this line if necessary based on your database structure -->
                            <audio controls>
                                <source src="<?php echo $message['audio']; ?>" type="audio/mpeg">
                                Your browser does not support the audio element.
                            </audio>
                        <?php endif; ?>
                    </div>
                    <div class="date">
                        <!-- Assuming $message['timestamp'] contains the timestamp of the message -->
                        <span class="timestamp"><?php echo $message['timestamp']; ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <form  id="message-form" method="post" enctype="multipart/form-data">
            <input type="text" class="input-msg" name="message" placeholder="Type your message">
            <label for="image-input" class="custom-file-upload"><i class="fas fa-image"></i></label>
            <input type="file" id="image-input" name="image" accept="image/*" style="display: none;"> <!-- Image upload -->
            <label for="audio-input" class="custom-file-upload"><i class="fas fa-paperclip"></i></label>
            <input type="file" id="audio-input" name="audio" accept="audio/*" style="display: none;"> <!-- Audio upload -->
            <button class="send-button" type="submit"><i class="fas fa-paper-plane"></i></button>
        </form>
    </div>
</div>
<div class="about">
   <a class="bg_links social portfolio" href="http://584701.klas4s23.mid-ica.nl" target="_blank">
      <span class="icon"></span>
   </a>
   <a class="bg_links social dribbble" href="https://dribbble.com/snaaier" target="_blank">
      <span class="icon"></span>
   </a>
   <a class="bg_links social linkedin" href="https://www.linkedin.com/in/ingmar-van-rheenen-0a9392290/" target="_blank">
      <span class="icon"></span>
   </a>
   <a class="bg_links logo"></a>
</div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js"></script>
    <script src="index-1.js"></script>
    <script src="mous.js"></script>
</body>
</html>
