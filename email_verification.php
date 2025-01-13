<?php
session_start();
include("connection.php");

if (isset($_POST["verify_email"])) {
    $email = $_POST["email"];
    $verification_code = $_POST["verification_code"];

    $sql = "UPDATE users SET email_verify = NOW() WHERE email = '$email' AND verification_code = '$verification_code'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_affected_rows($conn)==0) 
    {
        $email = $_POST['email']; 

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
            background: linear-gradient(190deg, #6592F3, #3B558D);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
        }
        .modal-content {
            color: white;
            background: linear-gradient(45deg,rgb(29, 69, 104),#19315D);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: auto;
            margin-inline: auto;
            align-content: center;
        }
        .btn{
            background: #19315D;
            border-color: #3B558D;
        }
        h6{
            font-size: smaller;
        }
    </style>
</head>
<body>
    <div class="modal fade show" id="verificationModal" tabindex="-1" aria-labelledby="verificationModalLabel" data-bs-backdrop="static" style="display: block;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="verificationModalLabel"><strong>Email Verification</strong></h3>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <?php if (!empty($_SESSION['error'])): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error']); ?></div>
                        <?php endif; 
                            unset($_SESSION['error']);
                        ?>
                        <div class="mb-3">
                            <input type="hidden" class="form-control" name="email" value="<?php echo $_GET['email']?? $_SESSION['email']; ?>" required>
                        </div>
                        <h6>If not found, check your <strong>All mail</strong> or <strong>Spam</strong></h6>
                        <div class="mb-3">
                            <label for="verification_code" class="form-label"><strong>Verification Code</strong></label>
                            <input type="text" class="form-control" id="verification_code" name="verification_code" placeholder="Enter verification code" required />
                        </div>
                        <input type="submit" class="btn btn-primary w-100" name="verify_email" value="Verify Email">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

