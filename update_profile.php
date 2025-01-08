<?php
session_start();
include "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["profile_picture"])) {
    $user_id = $_SESSION['user_id'];
    $file = $_FILES["profile_picture"];

    // Check for upload errors
    if ($file["error"] !== UPLOAD_ERR_OK) {
        $_SESSION['error_message'] = "Upload failed with error code: " . $file["error"];
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Create upload directory if it doesn't exist
    $upload_dir = "profile/";
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Generate unique filename
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $new_filename = "profile_" . $user_id . "_" . time() . "." . $file_extension;
    $target_file = $upload_dir . $new_filename;

    // Validate file type
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($file_extension, $allowed_types)) {
        $_SESSION['error_message'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Check if image file is valid
    $check = getimagesize($file["tmp_name"]);
    if ($check !== false) {
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            // Update database with new profile picture path
            $query = "UPDATE users SET profile = ? WHERE user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $target_file, $user_id);
            
            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Profile picture updated successfully!";
            } else {
                $_SESSION['error_message'] = "Error updating database: " . $conn->error;
            }
        } else {
            $_SESSION['error_message'] = "Sorry, there was an error uploading your file.";
        }
    } else {
        $_SESSION['error_message'] = "File is not a valid image.";
    }

    // Check role and redirect accordingly
    if (isset($_SESSION['role'])) {
        if ($_SESSION['role'] === 'admin') {
            header("Location: account_settings.php");
        } else {
            header("Location: settings_user.php");
        }
    } else {
        // Fallback to previous page if role is not set
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }
    exit();
}
?>