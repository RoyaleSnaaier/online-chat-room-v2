<?php
session_start();
include_once "config.php";
require_once 'mysql.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format!";
        exit(); // Stop further execution
    }

    // Perform signup process
    if (!empty($fname) && !empty($lname) && !empty($email) && !empty($password)) {
        $sql = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
        if (mysqli_num_rows($sql) > 0) {
            echo "$email - This email already exists!";
        } else {
            // Handle image upload
            $image_uploaded = false;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $img_name = $_FILES['image']['name'];
                $tmp_name = $_FILES['image']['tmp_name'];
                $img_size = $_FILES['image']['size'];
                
                // Check file size (max 5MB)
                if ($img_size > 5 * 1024 * 1024) {
                    echo "File size exceeds the limit (5MB)!";
                    exit(); // Stop further execution
                }
                
                $extensions = ["jpeg", "png", "jpg"];
                $img_ext = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
                
                // Check if the file extension is valid
                if (!in_array($img_ext, $extensions)) {
                    echo "Please upload an image file - jpeg, png, jpg";
                    exit(); // Stop further execution
                }
                
                $new_img_name = time() . '_' . $img_name;
                if (move_uploaded_file($tmp_name, "img/" . $new_img_name)) {
                    $image_uploaded = true;
                } else {
                    echo "Error uploading image!";
                    exit(); // Stop further execution
                }
            }
            // Generate unique ID
            $unique_id = uniqid();

            // Insert user data into database
            $status = "Active now";
            $encrypt_pass = md5($password);
            $insert_query = mysqli_query($conn, "INSERT INTO users (fname, lname, email, password, img, status, unique_id)
                                VALUES ('{$fname}', '{$lname}', '{$email}', '{$encrypt_pass}', '{$new_img_name}', '{$status}', '{$unique_id}')");
            if ($insert_query) {
                $select_sql2 = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
                if (mysqli_num_rows($select_sql2) > 0) {
                    $result = mysqli_fetch_assoc($select_sql2);
                    $_SESSION['unique_id'] = $result['unique_id'];
                    echo "success";
                } else {
                    echo "This email address does not exist!";
                }
            } else {
                echo "Something went wrong. Please try again!";
            }
        }
    } else {
        echo "All input fields are required!";
    }
}
?>
