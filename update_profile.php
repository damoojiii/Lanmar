<?php
session_start();
include("connection.php");

$user_id = $_SESSION['user_id'] ?? null;

if ($user_id && isset($_FILES['profile_picture'])) {
    $target_dir = "profile/";
    $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
    }


    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            $query = "UPDATE users SET profile = ? WHERE user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $target_file, $user_id);
            $stmt->execute();
            echo "<script>
            alert('The file " . htmlspecialchars(basename($_FILES["profile_picture"]["name"])) . " has been uploaded.');
            window.location.href = 'settings_user.php'; // Redirect to the settings page
          </script>";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
} else {
    echo "User not found or no file uploaded.";
}

$conn->close();
?>