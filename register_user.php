<?php
session_start(); // Start the session

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

    // Database connection
    $conn = mysqli_connect("localhost", "root", "", "lanmartest");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    if ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } else {
        $check_query = "SELECT * FROM users WHERE email = ?";
        $check_stmt = $conn->prepare($check_query);
        if (!$check_stmt) {
            die("Prepare failed: " . $conn->error);
        }
        
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $error = "Email already exists";
        } else {
            $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
            $encrypted_password = password_hash($password, PASSWORD_DEFAULT);

            $insert_query = "INSERT INTO users (firstname, lastname, contact_number, email, password, role, verification_code, email_verify, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_query);
            if (!$insert_stmt) {
                die("Prepare failed: " . $conn->error);
            }
            
            $email_verify = NULL;
            $insert_stmt->bind_param("ssssssisi", $firstname, $lastname, $contact_number, $email, $encrypted_password, $role, $verification_code, $email_verify, $status);

            try {
                $mail->SMTPDebug = 2; // Set to 2 for detailed debug output
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'bonkristian.devera@cvsu.edu.ph';
        $mail->Password = 'hmic oqcf wpxk dntb';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                $mail->setFrom('your_email@gmail.com', 'Lanmar Resort');
                $mail->addAddress($email, $firstname);
                $mail->isHTML(true);

                $mail->Subject = 'Email verification';
                $mail->Body = '<p>Your verification code is: <b style="font-size:30px;">'.$verification_code.'</b></p>';

                if (!$mail->send()) {
                    echo "Mailer Error: " . $mail->ErrorInfo; 
                } else {
                    // Now insert into the database
                    if ($insert_stmt->execute()) {
                        header("Location: email_verification.php?email=" . urlencode($email));
                        exit();
                    } else {
                        echo "Error inserting data: " . $insert_stmt->error; // Output error if insert fails
                    }
                }
            } catch (Exception $e) {
                // Handle exceptions
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
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
            <button type="submit" name="register" id="submitButton">Register</button>
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </form>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        var pass = document.getElementById("password"); // Corrected ID
        var msg = document.getElementById("message");
        var strength = document.getElementById("strength");
        var submitButton = document.querySelector('button[type="submit"]'); // Reference to the submit button

        // Initially disable the submit button
        submitButton.disabled = true;

        pass.addEventListener("input", () => {
            if (pass.value.length > 0) {
                msg.style.display = "block"; // Show the message
            } else {
                msg.style.display = "none"; // Hide the message
                submitButton.disabled = true; // Disable button if password is empty
                return; // Exit the function
            }

            if (pass.value.length < 5) {
                strength.innerHTML = "Password is Weak";
                pass.style.borderColor = "#ff5925"; 
                msg.style.color = "#ff5925"; 
                strength.style.color = "#ff5925";
                submitButton.disabled = true; // Disable button for weak password
            } else if (pass.value.length >= 5 && pass.value.length < 8) {
                strength.innerHTML = "Password is Medium";
                pass.style.borderColor = "#FFA500"; 
                msg.style.color = "#FFA500";
                strength.style.color = "#FFA500"; 
                submitButton.disabled = true; // Disable button for medium password
            } else if (pass.value.length >= 8) {
                strength.innerHTML = "Password is Strong";
                pass.style.borderColor = "#26d730"; 
                msg.style.color = "#26d730"; 
                strength.style.color = "#26d730"; 
                submitButton.disabled = false; // Enable button for strong password
            }
        });
    });
</script>

</body>
</html>
