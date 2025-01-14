<?php 
    session_start();
    include 'connection.php';
    $user_id = $_SESSION['user_id'];
    function refresh(){
        echo "<script>window.location.href = 'account_settings.php';</script>";
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
    
        // Fetch the current password from the database
        $sql = "SELECT password FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($db_password);
        $stmt->fetch();
        $stmt->close();
    
        if (password_verify($current_password, $db_password)) {
            if (!empty($new_password)) {
                if ($new_password === $confirm_password) {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $sql = "UPDATE users SET password = ? WHERE user_id = ?";
    
                    if ($stmt = $conn->prepare($sql)) {
                        $stmt->bind_param("si", $hashed_password, $user_id);
    
                        if ($stmt->execute()) {
                            $_SESSION['success_message1'] = "Password changed successfully.";
                            refresh();
                            exit();
                        } else {
                            $_SESSION['error_message1'] = "Error updating password. Please try again.";
                            refresh();
                            exit();
                        }
                        $stmt->close();
                    } else {
                        $_SESSION['error_message1'] = "Error preparing query.";
                        refresh();
                        exit();
                    }
                } else {
                    $_SESSION['error_message1'] = "New password and confirm password do not match.";
                    refresh();
                    exit();
                }
            } else {
                $_SESSION['error_message1'] = "Please input a new password.";
                refresh();
                exit();
            }
        } else {
            $_SESSION['error_message1'] = "Invalid current password.";
            refresh();
            exit();
        }
    }
    
?>