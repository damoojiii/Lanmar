<?php
session_start();
include("connection.php");

$success_message = "";
$error_message = "";
$gallery_success_message = "";
$gallery_error_message = "";


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_personal_info'])) {
    $user_id = $_SESSION['user_id'];
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $contact_number = trim($_POST['contact_number']);

    // Prepare and execute the update statement
    $stmt = $conn->prepare("UPDATE users SET firstname = ?, lastname = ?, contact_number = ? WHERE user_id = ?");
    $stmt->bind_param("sssi", $firstname, $lastname, $contact_number, $user_id);
    
    if ($stmt->execute()) {
        $success_message = "Background image updated successfully.";
    } else {
        $error_message = "Error updating background image in the database: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
    
    // Redirect back to account settings page
    header("Location: account_settings.php");
    exit();
}
?>