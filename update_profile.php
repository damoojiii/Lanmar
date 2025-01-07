<?php
session_start();
include "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["profile_picture"])) {
    $user_id = $_SESSION['user_id'];
    $target_dir = "profile/";
    $file_extension = strtolower(pathinfo($_FILES["profile_picture"]["name"], PATHINFO_EXTENSION));
    $new_filename = "profile_" . $user_id . "_" . time() . "." . $file_extension;
    $target_file = $target_dir . $new_filename;

    // Check if image file is valid
    if (getimagesize($_FILES["profile_picture"]["tmp_name"]) !== false) {
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            // Update database with new profile picture path
            $query = "UPDATE users SET profile = ? WHERE user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $target_file, $user_id);
            $stmt->execute();
            
            // Set success message
            $_SESSION['success_message'] = "Profile picture updated successfully!";
        } else {
            $_SESSION['error_message'] = "Sorry, there was an error uploading your file.";
        }
    } else {
        $_SESSION['error_message'] = "File is not a valid image.";
    }

    // Check which page made the request and redirect back accordingly
    $referer = $_SERVER['HTTP_REFERER'] ?? '';
    if (strpos($referer, 'settings_user.php') !== false) {
        header("Location: settings_user.php");
    } else if (strpos($referer, 'account_settings.php') !== false) {
        header("Location: account_settings.php");
    } else {
        header("Location: settings_user.php"); // Default fallback
    }
    exit();
}
?>