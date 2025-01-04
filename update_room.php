<?php
include "connection.php"; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_id = $_POST['room_id'];
    $room_name = $_POST['room_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $minpax = $_POST['minpax'];
    $maxpax = $_POST['maxpax'];

    // Handle file upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $photo = $_FILES['photo']['name'];
        $target = "uploads/" . basename($photo);
        move_uploaded_file($_FILES['photo']['tmp_name'], $target);
    } else {
        // If no new photo is uploaded, keep the existing one
        $photo = ""; // You may want to fetch the existing photo from the database if needed
    }

    // Update the room information in the database
    $sql = "UPDATE rooms SET room_name=?, description=?, price=?, minpax=?, maxpax=?, image_path=? WHERE room_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdiisi", $room_name, $description, $price, $minpax, $maxpax, $photo, $room_id);

    if ($stmt->execute()) {
        echo "Room updated successfully!";
    } else {
        echo "Error updating room: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>