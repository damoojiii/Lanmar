<?php
session_start();
include "connection.php";

if (isset($_POST['update_personal_info'])) {
    $user_id = $_SESSION['user_id'];
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];

    // Update user information
    $update_query = "UPDATE users SET contact_number = ?, email = ?, firstname = ?, lastname = ? WHERE user_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("ssssi", $contact_number,$email,$firstname,$lastname, $user_id);
    
    if ($update_stmt->execute()) {
        $_SESSION['success_message'] = "Your information has been updated successfully.";
    } else {
        $_SESSION['error_message'] = "Error updating your information. Please try again.";
    }

    // Check role and redirect accordingly
    if (isset($_SESSION['role'])) {
        if ($_SESSION['role'] === 'admin') {
            header("Location: account_settings.php");
            exit();
        } else {
            header("Location: index.php");
            exit();
        }
    } else {
        // Fallback to previous page if role is not set
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }
    exit();
}
?>