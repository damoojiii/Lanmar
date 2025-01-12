<?php
session_start();
include("connection.php");

$error = "";
if(isset($_GET['dateIn']) && isset($_GET['dateOut'])){
    $_SESSION['preDateIn'] = $_GET['dateIn'];
    $_SESSION['preDateOut'] = $_GET['dateOut'];
}

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            if ($user['status'] == 0) {
                $error = "Your account is blocked. Please contact support.";
            } else {
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = $user['role'];

                    if ($user['role'] === 'admin') {
                        header("Location: admin_dashboard.php");
                        exit();
                    } elseif ($user['role'] === 'user') {
                        if ($user['email_verify'] === NULL) {
                            $error = "Please verify your email before logging in.";
                        } else {
                            header("Location: index1.php");
                            exit();
                        }
                    } else {
                        header("Location: index.php");
                        exit();
                    }
                } else {
                    $error = "Invalid email or password";
                }
            }
        } else {
            $error = "Invalid email or password";
        }
    } else {
        $error = "Database query failed.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/all.min.css">
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/fontawesome.min.css">
    <style>
        @font-face {
            font-family: 'nautigal';
            src: url(font/TheNautigal-Regular.ttf);
        }
        h2{
            font-family: 'nautigal';
            font-size: 3rem;
        }
        body {
            background: linear-gradient(190deg, #6592F3, #3B558D);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
        }

        .btn{
            background: #19315D;
            border-color: #3B558D;
        }
        .login-container {
            color: white;
            background: linear-gradient(45deg,rgb(29, 69, 104),#19315D);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: auto;
        }

        .container {
            background-color: white;
            margin-top: 10px;
            padding: 0 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: auto;
        }
        .main{
            display: flex;
            flex-direction: column;
        }
        .forgot{
            margin-bottom: 5px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="main">
        <div class="login-container">
            <h2 class="text-center mb-4">Lanmar Resort</h2>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <label for="password" class="form-label">Password</label>
                        <p class="text-center list-unstyle forgot"><a href="forgot_password.php">Forgot Password</a></p>
                    </div>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
            </form>
        </div>

        <div class="container">
            <p class="mt-3 text-center">Don't have an account? <a href="register_user.php">Sign up here</a></p>
        </div>
    </div>
</body>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/bootstrap/js/all.min.js"></script>
<script src="assets/vendor/bootstrap/js/fontawesome.min.js"></script>
</html>
