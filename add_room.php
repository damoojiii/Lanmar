<?php
// Include database connection
include("connection.php");

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $room_name = $_POST['room_name'];
    $description = $_POST['description'];
    $minpax = $_POST['minpax'];
    $maxpax = $_POST['maxpax'];
    $price = $_POST['price'];
    $is_featured = $_POST['is_featured'];
    $is_offered = $_POST['is_offered'];

    // Handle file upload
    $target_dir = "uploads/"; // Directory to save uploaded files
    $target_file = $target_dir . basename($_FILES["image_path"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // If everything is ok, try to upload file
        if (move_uploaded_file($_FILES["image_path"]["tmp_name"], $target_file)) {
            // Prepare the SQL statement
            $sql = "INSERT INTO rooms (room_name, image_path, description, minpax, maxpax, price, is_featured, is_offered) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            // Prepare and bind
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssiisss", $room_name, $target_file, $description, $minpax, $maxpax, $price, $is_featured, $is_offered);

            // Execute the statement
            if ($stmt->execute()) {
                echo "New room added successfully";
            } else {
                echo "Error: " . $stmt->error;
            }

            // Close the statement and connection
            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    // Close the database connection
    $conn->close();
}
?>