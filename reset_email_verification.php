<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['verify_email'])) {
    $email = htmlspecialchars($_POST['email']);
    $verification_code = htmlspecialchars($_POST['verification_code']);
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND forgot_code = ?");
    $stmt->bind_param("ss", $email, $verification_code);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) { 
            echo "<script>alert('Verification successful!');</script>";
            echo "<script>window.location.href='new_password.php?email=" . urlencode($email) . "';</script>";
        } else {
            echo "<script>alert('Invalid email or verification code.');</script>";
        }
    } else {
        echo "<script>alert('Error executing query!');</script>";
        echo "<script>window.location.href='login.php';</script>";
    }

    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(190deg, #6592F3, #3B558D);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
        }
        .login-container {
            color: white;
            background: linear-gradient(45deg,rgb(29, 69, 104),#19315D);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: auto;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 class="text-center mb-4">Enter Verification Code</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email']); ?>" required>
            </div>
            <div class="mb-3">
                <input type="text" class="form-control" name="verification_code" placeholder="Enter verification code" required />
            </div>
            <input type="submit" class="btn btn-primary w-100" name="verify_email" value="Verify Email">
        </form>
    </div>
</body>
</html>

