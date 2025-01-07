<?php
session_start();
include "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $contact_number = $_POST['contact_number'];

    // Update user information
    $update_query = "UPDATE users SET contact_number = ? WHERE user_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("si", $contact_number, $user_id);
    
    if ($update_stmt->execute()) {
        $_SESSION['success_message'] = "Your information has been updated successfully.";
    } else {
        $_SESSION['error_message'] = "Error updating your information. Please try again.";
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