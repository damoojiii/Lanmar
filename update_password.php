<?php
session_start();
include("connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the POST variables exist before accessing them
    if (isset($_POST['password']) && isset($_POST['confirm_password'])) {
        // Retrieve form data
        $new_password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Verify passwords match
        if ($new_password === $confirm_password) {
            // Hash the password for security
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Assuming you have a database connection established
            $user_id = $_SESSION['user_id']; // Make sure you have the user's ID

            // Prepare and execute the update query
            $sql = "UPDATE users SET password = ? WHERE user_id = ?";
            $stmt = $conn->prepare($sql);

            if ($stmt === false) {
                die("Error preparing statement: " . $conn->error);
            }

            $stmt->bind_param("si", $hashed_password, $user_id);

            if ($stmt->execute()) {
                echo "<script>alert('Password updated successfully!');</script>";
                echo '<script>window.location.href="account_settings.php";</script>';
            } else {
                echo "<script>alert('Error updating password: " . $conn->error . "');</script>";
            }

            $stmt->close();
        } else {
            echo "<script>alert('Passwords do not match!');</script>";
            echo '<script>window.location.href="account_settings.php";</script>';
        }
    } else {
        echo "<script>alert('Please fill in all required fields!');</script>";
    }
}
?>
