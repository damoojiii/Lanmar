<?php
include 'connection.php';

if (!isset($_GET['email']) || empty($_GET['email'])) {
    die("Invalid access. Email not provided.");
}

$email = htmlspecialchars($_GET['email']);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_password'])) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        echo "<p>Passwords do not match!</p>";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $hashed_password, $email);

        if ($stmt->execute()) {
            echo "<p>Password updated successfully!</p>";
            echo "<script>window.location.href='login.php';</script>"; 
        } else {
            echo "<p>Error updating password: " . $stmt->error . "</p>";
        }

        $stmt->close();
    }
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
    </style>
</head>
<body>
    <div class="login-container">
        <h2 class="text-center mb-4">New Password</h2>
        <form method="POST" action="">
            <div class="mb-3">
            <input type="text" class="form-control" name="email" value="<?php echo htmlspecialchars($email); ?>" readonly>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" id="new_password" name="new_password" placeholder="New Password" required />
            </div>
            <div class="mb-3">
            <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required />
            </div>
                <p id="message" style="display: none;">Password is <span id="strength"></span></p>
            <input type="submit" class="btn btn-primary w-100" name="update_password" value="Update Password">
        </form>
    </div>

    
<script>
        document.addEventListener("DOMContentLoaded", function() {
            var pass = document.getElementById("new_password");
            var msg = document.getElementById("message");
            var strength = document.getElementById("strength");
            var form = document.querySelector("form"); // Adjusted to select the form correctly
            var passwordStrength = ""; // Variable to track password strength

            pass.addEventListener("input", () => {
                if (pass.value.length > 0) {
                    msg.style.display = "block"; // Show message
                } else {
                    msg.style.display = "none"; // Hide message
                }

                if (pass.value.length < 4) {
                    strength.innerHTML = "Weak";
                    pass.style.borderColor = "#ff5925";
                    msg.style.color = "#ff5925";
                    passwordStrength = "Weak"; // Update password strength variable
                } else if (pass.value.length >= 6 && pass.value.length < 12) {
                    strength.innerHTML = "Medium";
                    pass.style.borderColor = "yellow";
                    msg.style.color = "yellow";
                    passwordStrength = "Medium"; // Update password strength variable
                } else if (pass.value.length >= 12) {
                    strength.innerHTML = "Strong";
                    pass.style.borderColor = "#26d730";
                    msg.style.color = "#26d730";
                    passwordStrength = "Strong"; // Update password strength variable
                }
            });

            form.addEventListener("submit", function(event) {
                if (passwordStrength !== "Strong") {
                    event.preventDefault(); // Prevent form submission
                    alert("Please choose a stronger password!"); // Alert the user
                }
            });
        });
    </script>

</body>
</html>
