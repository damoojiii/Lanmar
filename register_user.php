<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';
require './vendor/autoload.php';

$error = "";
$success = "";

if (isset($_POST["register"])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];
    $status = 1;

    

    $mail = new PHPMailer(true);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '<script>alert("Invalid email address.");</script>';
        echo '<script>window.location.href = "register_user.php";</script>';
        exit; // Stop further execution
    }

    // Ensure email is not empty before sending
    if (empty($email)) {
        echo '<script>alert("Email cannot be empty.");</script>';
        echo '<script>window.location.href = "register_user.php";</script>';
        exit; // Stop further execution
    }

    try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'fridaythe012@gmail.com';
        $mail->Password = 'zaye hbft pwdh bqwo';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->setFrom('your_email@gmail.com', 'Lanmar_Resort');
        $mail->addAddress($email, $firstname);

        $mail->isHTML(true);
        $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
        $mail->Subject = 'Email verification';
        $mail->Body = '<p>Your verification code is: <b style="font-size:30px;">' . $verification_code . '</b></p>';

        // Send the email
        $mail->send();

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Database connection
        $conn = mysqli_connect("localhost", "root", "", "lanmartest");
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Check if email already exists
        $email_exist = "SELECT * FROM users WHERE email ='$email'";
        $email_result = $conn->query($email_exist);
        if ($email_result->num_rows > 0) {
            $error = "Email already exists";
        } else {
            if ($_FILES['photo']['name']) {
                $photo = $_FILES['photo']['name'];
                $photoPath = 'profile/' . basename($photo);
                move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath);
            } else {
                // Default photo
                $photoPath = 'profile/default_photo.jpg';
            }

            $insert_query = "INSERT INTO users (firstname, lastname, contact_number, email, password, role, verification_code, email_verify, status, profile) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $insert_stmt = $conn->prepare($insert_query);
            $email_verify = NULL; // Assuming email is not verified initially

            $insert_stmt->bind_param("ssissssiis", $firstname, $lastname, $contact_number, $email, $hashed_password, $role, $verification_code, $email_verify, $status, $photoPath);

            // Execute the insert statement
            if ($insert_stmt->execute()) {
                header("Location: email_verification.php?email=" . urlencode($email));
                exit();
            } else {
                echo "Error inserting data: " . $insert_stmt->error; // Output error if insert fails
            }
        }
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }


}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background: linear-gradient(to bottom right, #006994, #00FFFF);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            font-family: Arial, sans-serif;
            color: #333333;
        }
        .register-container {
            background-color: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        input {
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 100%;
            box-sizing: border-box;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
            width: 100%;
        }
        button:hover {
            background-color: #0056b3;
        }
        p {
            text-align: center;
        }
        a {
            text-decoration: none;
            color: #007bff;
        }
        a:hover {
            text-decoration: underline;
        }
        .error {
            color: red;
        }
        .success {
            color: green;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Lanmar Resort</h2>
        <p>Register Here</p>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <input type="text" name="firstname" placeholder="First Name" required>
            <input type="text" name="lastname" placeholder="Last Name" required>
            <input type="tel" name="contact_number" placeholder="Contact Number" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <p id="message" style="display: none;"><span id="strength"></span></p>
            <input type="hidden" name="role" value="user" required>
            <input type="hidden" name="photo" required>
            <button type="submit" name="register" id="submitButton">Register</button>
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </form>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        var pass = document.getElementById("password");
        var confirmPass = document.querySelector('input[name="confirm_password"]');
        var msg = document.getElementById("message");
        var strength = document.getElementById("strength");
        var submitButton = document.querySelector('button[type="submit"]');

        // Add new element for confirm password message
        var confirmMsg = document.createElement("p");
        confirmMsg.id = "confirmMessage";
        confirmMsg.style.display = "none";
        confirmPass.parentNode.insertBefore(confirmMsg, confirmPass.nextSibling);

        // Add event listener for confirm password
        confirmPass.addEventListener("input", () => {
            confirmMsg.style.display = "block";
            if (pass.value !== confirmPass.value) {
                confirmPass.style.borderColor = "#ff5925";
                confirmMsg.innerHTML = "Passwords do not match!";
                confirmMsg.style.color = "#ff5925";
                submitButton.disabled = true;
            } else {
                confirmPass.style.borderColor = "#26d730";
                confirmMsg.innerHTML = "Passwords match!";
                confirmMsg.style.color = "#26d730";
                // Check if password is also strong enough
                checkPasswordStrength();
            }
        });

        // Password strength checker
        function checkPasswordStrength() {
            const hasUpperCase = /[A-Z]/.test(pass.value);
            const hasLowerCase = /[a-z]/.test(pass.value);
            const hasNumbers = /\d/.test(pass.value);
            const hasSymbols = /[!@#$%^&*(),.?":{}|<>]/.test(pass.value);
            
            if (pass.value.length > 0) {
                msg.style.display = "block";
            } else {
                msg.style.display = "none";
                submitButton.disabled = true;
                return;
            }

            if (pass.value.length < 5 || !hasUpperCase || !hasLowerCase || !hasNumbers || !hasSymbols) {
                strength.innerHTML = "Password is Weak (Requires uppercase, lowercase, number, and symbol)";
                pass.style.borderColor = "#ff5925";
                msg.style.color = "#ff5925";
                strength.style.color = "#ff5925";
                submitButton.disabled = true;
            } else if (pass.value.length >= 5 && pass.value.length < 8) {
                strength.innerHTML = "Password is Medium (Consider using a longer password)";
                pass.style.borderColor = "#FFA500";
                msg.style.color = "#FFA500";
                strength.style.color = "#FFA500";
                submitButton.disabled = true;
            } else if (pass.value.length >= 8 && hasUpperCase && hasLowerCase && hasNumbers && hasSymbols) {
                strength.innerHTML = "Password is Strong";
                pass.style.borderColor = "#26d730";
                msg.style.color = "#26d730";
                strength.style.color = "#26d730";
                // Only enable submit if passwords also match
                submitButton.disabled = !(pass.value === confirmPass.value);
            }
        }

        // Add event listener for password
        pass.addEventListener("input", checkPasswordStrength);
    });
</script>

</body>

</html>