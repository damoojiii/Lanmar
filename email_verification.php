<?php
session_start();
include("connection.php");

if (isset($_POST["verify_email"])) {
    $email = $_POST["email"];
    $verification_code = $_POST["verification_code"];

    $conn = mysqli_connect("localhost", "root", "", "lanmartest");

    $sql = "UPDATE users SET email_verify = NOW() WHERE email = '$email' AND verification_code = '$verification_code'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_affected_rows($conn)==0) 
    {
        $email = $_POST['email']; // or however you are getting the email

        $delete_query = "DELETE FROM users WHERE email = ?";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bind_param("s", $email);

        if ($delete_stmt->execute()) {
            echo "<script>alert('Verification code failed, please register again'); window.location.href='login.php';</script>";
        } else {
            echo "<script>alert('Failed to delete user data. Please contact support.'); window.location.href='login.php';</script>";
        }
        $delete_stmt->close();
        exit;
    } 
    echo "<script>alert('You can log in now');</script>";
    echo "<script>window.location.href='login.php';</script>";
    exit;
    
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to bottom right, #006994, #00FFFF);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-container {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 class="text-center mb-4">Email Verification</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <input type="hidden" class="form-control" name="email" value="<?php echo $_GET['email']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Verification Code</label>
                <input type="text" class="form-control" name="verification_code" placeholder="Enter verification code" required />
            </div>
            <input type="submit" class="btn btn-primary w-100" name="verify_email" value="Verify Email">
        </form>
    </div>
</body>
</html>

