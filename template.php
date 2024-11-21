<?php
// Start the session at the very beginning of the file
session_start();

include("connection.php");

$success_message = "";
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['background_image'])) {
    $image = $_FILES['background_image'];
    $imageName = basename($image['name']);
    $targetDir = "uploads/"; 
    $targetFilePath = $targetDir . $imageName;
    $imageType = $image['type'];

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    $conn->query("DELETE FROM settings");

    if (move_uploaded_file($image['tmp_name'], $targetFilePath)) {
        $stmt = $conn->prepare("INSERT INTO settings (image, image_type) VALUES (?, ?)");
        $stmt->bind_param("ss", $targetFilePath, $imageType); 

        if ($stmt->execute()) {
            $success_message = "Background image updated successfully.";
        } else {
            $error_message = "Error updating background image in the database.";
        }

        $stmt->close();
    } else {
        echo "Error uploading the file.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0; /* Remove default margin */
            padding: 0;
            display: flex;
        }
        .sidebar {
            width: 250px; /* Adjust the width as needed */
            background-color: #f4f4f4;
        }
        .main-section {
            flex-grow: 1; /* This will take the remaining space */
            padding: 20px;
            background-color: #ffffff;
        }
    </style>
</head>
<body>
        <div class="sidebar">
            <?php include 'sidebar_admin.php'; ?>
        </div>
        <div class="main-section">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <h2 class="text-center mb-4">Change Background Image</h2>

                    <?php if ($success_message): ?>
                    <div class="alert alert-success text-center"><?php echo $success_message; ?></div>
                    <?php endif; ?>

                    <?php if ($error_message): ?>
                        <div class="alert alert-danger text-center"><?php echo $error_message; ?></div>
                    <?php endif; ?>

                    <form action="" method="POST" enctype="multipart/form-data" class="mb-4">
                        <div class="form-group text-center">
                            <label for="background_image" class="mb-2">Upload New Background Image:</label>
                            <input type="file" name="background_image" id="background_image" accept="image/*" required class="form-control-file mx-auto d-block" aria-label="Upload New Background Image">
                        </div>
                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-primary" aria-label="Update Background">Update Background</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
</body>
</html>
