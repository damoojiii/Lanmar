<?php
// Start the session at the very beginning of the file
session_start();

include("connection.php");

$success_message = "";
$error_message = "";
$gallery_success_message = "";
$gallery_error_message = "";

// Define the target directory for uploads
$targetDir = "uploads/"; 

// Error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle background image upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['background_image'])) {
    $image = $_FILES['background_image'];
    $imageName = basename($image['name']);
    $targetFilePath = $targetDir . $imageName;
    $imageType = $image['type'];

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    // Clear existing settings
    $conn->query("DELETE FROM settings_admin");

    if (move_uploaded_file($image['tmp_name'], $targetFilePath)) {
        $stmt = $conn->prepare("INSERT INTO settings_admin (image, image_type) VALUES (?, ?)");
        $stmt->bind_param("ss", $targetFilePath, $imageType); 

        if ($stmt->execute()) {
            $success_message = "Background image updated successfully.";
        } else {
            $error_message = "Error updating background image in the database: " . $stmt->error;
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
            $gallery_error_message = "Error uploading gallery image in the database: " . $galleryStmt->error;
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
    <title>homepage settings</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/main.css">
    
    <style>
        @font-face {
            font-family: 'nautigal';
            src: url(font/TheNautigal-Regular.ttf);
        }

        #sidebar span {
            font-family: 'nautigal';
            font-size: 50px !important;
        }

        #sidebar {
            width: 250px;
            position: fixed;
            top: 0; 
            height: 100vh;
            overflow-y: auto; 
            background: #001A3E;
            transition: transform 0.3s ease;
        }

        #sidebar.collapsed {
            transform: translateX(-100%); /* Hide sidebar */
        }

        .navbar {
            margin-left: 250px; 
            z-index: 1; 
            width: calc(100% - 250px);
            height: 50px;
            transition: margin-left 0.3s ease; 
        }

        #main-content {
            transition: margin-left 0.3s ease;
            margin-left: 250px; 
        }

        #hamburger {
            border: none;
            background: none;
            cursor: pointer;
        }

        hr {
            background-color: #ffff;
            height: 1.5px;
        }

        #sidebar .nav-link {
            color: #fff;
            padding: 10px;
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
            margin-bottom: 2px;
        }

        #sidebar .nav-link:hover, #sidebar .nav-link.active {
            background-color: #fff !important;
            color: #000 !important;
        }

        .dropdown-menu {
            width: 100%;
        }

        .dropdown-item {
            color: #000 !important;
        }

        @media (max-width: 768px) {
            #sidebar {
                position: absolute;
                transform: translateX(-100%); /* Hide sidebar off-screen */
            }
            #sidebar.show {
                transform: translateX(0); /* Show sidebar */
            }

            .navbar {
                margin-left: 0;
                width: 100%; 
            }

            #main-content {
                margin-left: 0;
            }
        }

        .flex-container {
            display: flex;
            gap: 20px;
        }
        .settings-form-container {
            margin-bottom: 20px;
        }
        .alert {
            padding: 10px;
            margin: 10px 0;
        }
        .alert-success {
            color: green;
        }
        .alert-danger {
            color: red;
        }
        .button-container {
            display: flex;
            justify-content: end;
        }
        button {
            border-radius: 50px;
            padding: 13px 30px;
            background-color: #03045e;
            border: none;
            cursor: pointer;
            color: white;
        }

        .flex-container {
        display: flex;
        gap: 20px;
    }

    .sidebar-settings {
        display: flex;
        flex-direction: column;
        width: 230px;
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 35px 15px 15px 15px;
        align-items: center;
        justify-content: center;
    }

    .settings-links {
        width: 100%
    }

    .settings-links ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .settings-links li {
        margin-bottom: 10px;
        text-align: center;
    }

    .settings-links a {
        text-decoration: none;
        color: #333;
        padding: 10px 15px;
        border-radius: 2px;
        transition: 0.3s;
    }

    .settings-links a:hover {
        background-color: #ddd;
    }

    .settings-links .links {
        margin-bottom: 30px;
    }

    .main-content {
        flex: 1;
        padding: 25px;
        background-color: #ffff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .form-group input {
        margin-bottom: 10px;
    }

    .settings-form .form-group label {
        display: block;
        margin-bottom: 10px;
        font-weight: bold;
        font-size: 17px;
    }

    .settings-form .form-group input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 0px;
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        float: right;
        margin-left: 16%;
        margin-bottom: 15px;
    }

    .four-box-container {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        margin: 5px 0 0 0;
    }

    .links {
        border-bottom: 1px solid #ccc;
    }

    .links:last-child {
        border-bottom: none;
    }

    .links i {
        font-size: 12px;
    }

    .links li a {
        display: flex;
        align-items: center;
        gap: 20px;
        font-size: 15px;
        font-weight: 600;
        padding: 15px 20px;
        transition: all 0.3s;
        justify-content: space-between;
    }

    .links .active a {
        background-color: #1c2531;
        color: white;
        border-radius: 10px 10px 10px 10px;
    }

    .button-container {
        display: flex;
        justify-content: end;
    }

    .settings-form button, 
        .save-btn {
            border-radius: 10px !important;  /* Added !important to override Bootstrap */
            padding: 13px 30px;
            background-color: #03045e;
            border: none;
            cursor: pointer;
            color: white;
        }
    </style>
</head>
<body>
    <?php include 'sidebar_admin.php'; ?>

    <div id="main-content" class="p-3">
        <div class="flex-container">
            <div class="main-content">
                <h1 class="text-center mb-5 mt-4">Homepage Settings</h1>
                <div class="row"> <!-- Add this row container -->
                    <div class="col-md-6"> <!-- First column -->
                        <div class="settings-form-container">
                            <h2 class="text-center mb-4">Change Background Image</h2>
                            <?php if ($success_message): ?>
                                <div class="alert alert-success text-center"><?php echo $success_message; ?></div>
                            <?php endif; ?>
                            <?php if ($error_message): ?>
                                <div class="alert alert-danger text-center"><?php echo $error_message; ?></div>
                            <?php endif; ?>

                            <form class="settings-form" method="POST" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="background_image" class="mb-2">Upload New Background Image:</label>
                                    <input type="file" name="background_image" id="background_image" accept="image/*" required class="form-control-file mx-auto d-block" aria-label="Upload New Background Image">
                                </div>
                                <div class="button-container">
                                    <button type="submit" class="update-button" aria-label="Update Background">Update Background</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-6"> <!-- Second column -->
                        <div class="settings-form-container">
                            <h2 class="text-center mb-4">Upload Gallery Image</h2>
                            <?php if ($gallery_success_message): ?>
                                <div class="alert alert-success text-center"><?php echo $gallery_success_message; ?></div>
                            <?php endif; ?>
                            <?php if ($gallery_error_message): ?>
                                <div class="alert alert-danger text-center"><?php echo $gallery_error_message; ?></div>
                            <?php endif; ?>

                            <form class="settings-form" method="POST" enctype="multipart/form-data">
                                <div class="form-group text-center">
                                    <label for="gallery_image" class="mb-2">Upload New Gallery Image:</label>
                                    <input type="file" name="gallery_image" id="gallery_image" accept="image/*" required class="form-control-file mx-auto d-block" aria-label="Upload New Gallery Image">
                                </div>
                                <div class="form-group text-center">
                                    <label for="gallery_caption" class="mb-2">Caption:</label>
                                    <input type="text" name="gallery_caption" id="gallery_caption" class="form-control" placeholder="Enter caption for gallery image">
                                </div>
                                <div class="button-container">
                                    <button type="submit" class="update-button" aria-label="Upload Gallery Image">Upload Gallery Image</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
