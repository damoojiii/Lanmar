<?php

session_start();
include "role_access.php";
checkAccess('admin');

include("connection.php");

$success_message = "";
$error_message = "";
$gallery_success_message = "";
$gallery_error_message = "";

// Define the target directory for uploads
$targetDir = "uploads/"; 

// Handle background image upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['background_image'])) {
    $image = $_FILES['background_image'];
    $imageName = basename($image['name']);
    $targetFilePath = $targetDir . $imageName;
    $imageType = $image['type'];

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    $conn->query("DELETE FROM settings_admin");

    if (move_uploaded_file($image['tmp_name'], $targetFilePath)) {
        $stmt = $conn->prepare("INSERT INTO settings_admin (image, image_type) VALUES (?, ?)");
        $stmt->bind_param("ss", $targetFilePath, $imageType); 

        if ($stmt->execute()) {
            $success_message = "Background image updated successfully.";
        } else {
            $error_message = "Error updating background image in the database.";
        }

        $stmt->close();
    } else {
        $error_message = "Error uploading the file.";
    }
}

// Handle gallery image upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['gallery_image'])) {
    $galleryImage = $_FILES['gallery_image'];
    $galleryImageName = basename($galleryImage['name']);
    $galleryTargetFilePath = $targetDir . $galleryImageName; // Use the same $targetDir
    $galleryImageType = $galleryImage['type'];
    $galleryCaption = $_POST['gallery_caption']; // Get the caption from the form

    if (move_uploaded_file($galleryImage['tmp_name'], $galleryTargetFilePath)) {
        $galleryStmt = $conn->prepare("INSERT INTO gallery (image, image_type, caption) VALUES (?, ?, ?)");
        $galleryStmt->bind_param("sss", $galleryTargetFilePath, $galleryImageType, $galleryCaption); 

        if ($galleryStmt->execute()) {
            $gallery_success_message = "Gallery image uploaded successfully.";
        } else {
            $gallery_error_message = "Error uploading gallery image in the database.";
        }

        $galleryStmt->close();
    } else {
        $gallery_error_message = "Error uploading the gallery file.";
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
            flex-grow: 1; 
            padding: 20px;
            background-color: #ffffff;
        }
    </style>
</head>
<body>
    <?php include 'sidebar_admin.php'; ?>
    
    <div class="main-section" class="p-3">
        <div class="flex-container">
            <div class="main-content">
                <h1 class="text-center mb-5 mt-4">Account Settings</h1>
            </div>
            <div class="col-md-6">
                <h2 class="text-center mb-4">Change Background Image</h2>
                <?php if ($success_message): ?>
                <div class="alert alert-success text-center"><?php echo $success_message; ?></div>
                <?php endif; ?>
                <?php if ($error_message): ?>
                    <div class="alert alert-danger text-center"><?php echo $error_message; ?></div>
                <?php endif; ?>
                <form action="" method="POST" enctype="multipart/form-data" class="mb-4">
                    <div class="form-group">
                        <label for="background_image" class="mb-2">Upload New Background Image:</label>
                        <input type="file" name="background_image" id="background_image" accept="image/*" required class="form-control-file mx-auto d-block" aria-label="Upload New Background Image">
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary" aria-label="Update Background">Update Background</button>
                    </div>
                </form>
            </div>
            
            <div class="col-md-6">
                <h2 class="text-center mb-4">Upload Gallery Image</h2>
                <?php if ($gallery_success_message): ?>
                <div class="alert alert-success text-center"><?php echo $gallery_success_message; ?></div>
                <?php endif; ?>
                <?php if ($gallery_error_message): ?>
                    <div class="alert alert-danger text-center"><?php echo $gallery_error_message; ?></div>
                <?php endif; ?>
                <form action="" method="POST" enctype="multipart/form-data" class="mb-4">
                    <div class="form-group">
                        <label for="gallery_image" class="mb-2">Upload New Gallery Image:</label>
                        <input type="file" name="gallery_image" id="gallery_image" accept="image/*" required class="form-control-file mx-auto d-block" aria-label="Upload New Gallery Image">
                    </div>
                    <div class="form-group">
                        <label for="gallery_caption" class="mb-2">Caption:</label>
                        <input type="text" name="gallery_caption" id="gallery_caption" class="form-control" placeholder="Enter caption for gallery image">
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary" aria-label="Upload Gallery Image">Upload Gallery Image</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
