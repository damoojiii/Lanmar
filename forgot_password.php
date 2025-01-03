<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        require './PHPMailer/src/Exception.php';
        require './PHPMailer/src/PHPMailer.php';
        require './PHPMailer/src/SMTP.php';
        require './vendor/autoload.php';

        if (isset($_POST["submit"])) {
            $mail = new PHPMailer(true);
            try {
                $mail->SMTPDebug = 0;
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
<<<<<<< HEAD
                $mail->Username = 'fridaythe012@gmail.com';
=======
                $mail->Username = 'fridaythe012gmail.com';
>>>>>>> 1c551381ce41ccde0d9103a26e4879c5d91f3245
                $mail->Password = 'zaye hbft pwdh bqwo';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                $mail->setFrom('your_email@gmail.com', 'Lanmar_Resort');
                $mail->addAddress($email, $fullname);

                $mail->isHTML(true);
                $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
                $mail->Subject = 'Email verification';
                $mail->Body = '<p>Your verification code to reset the password is: <b style="font-size:30px;">' . $verification_code . '</b></p>';

                $mail->send();

                $sql = "UPDATE users SET forgot_code = '$verification_code' WHERE email = '$email'";
                $conn->query($sql);
                
                header("Location: reset_email_verification.php?email=" . urlencode($email));
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
    } else {
        echo "Email does not exist.";
    }

    $stmt->close();
    $conn->close();
}

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
        <h2 class="text-center mb-4">Enter Email to Reset the Password</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" placeholder="Enter your email" required>
            </div>
            <input type="submit" class="btn btn-primary w-100" name="submit" value="submit">
        </form>
    </div>
</body>
</html>

