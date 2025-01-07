<?php
session_start();
include('connection.php');  // Make sure to include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($new_password !== $confirm_password) {
        header("Location: account_settings.php?error=Passwords do not match");
        exit();
    }

    // Check password length
    if (strlen($new_password) < 8) {
        header("Location: account_settings.php?error=Password must be at least 8 characters long");
        exit();
    }

    // The error is likely here - remove any unexpected "!" tokens
    if (!preg_match("/[A-Z]/", $new_password) || 
        !preg_match("/[a-z]/", $new_password) || 
        !preg_match("/[0-9]/", $new_password)) {
        header("Location: account_settings.php?error=Password must contain at least one uppercase letter, one lowercase letter, and one number");
        exit();
    }

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Prepare SQL query to update password in the database
    $sql = "UPDATE users SET password = ? WHERE email = ?";

    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters to the query
        $stmt->bind_param("ss", $hashed_password, $email);

        // Execute the query
        if ($stmt->execute()) {
            echo "<script>alert('Password updated successfully!'); window.location.href = 'account_settings.php';</script>";
        } else {
            echo "<script>alert('Error updating password. Please try again.'); window.history.back();</script>";
        }

        // Close statement
        $stmt->close();
    } else {
        echo "<script>alert('Error preparing query.'); window.history.back();</script>";
    }

    // Close the database connection
    $conn->close();
}
?>