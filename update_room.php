<?php
include "connection.php"; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_id = $_POST['room_id'];
    $room_name = $_POST['room_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $minpax = $_POST['minpax'];
    $maxpax = $_POST['maxpax'];
    $is_offered = isset($_POST['is_offered']) ? $_POST['is_offered'] : 0;
    $inclusions = isset($_POST['inclusions']) ? $_POST['inclusions'] : [];

    // Fetch the existing image_path
    $sql = "SELECT image_path FROM rooms WHERE room_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $room_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $existing_image_path = $row['image_path'];
    $stmt->close();

    // Handle file upload
    $photo = $existing_image_path; // Default to the existing image path
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $photo_name = $_FILES['photo']['name'];
        $target = "uploads/" . basename($photo_name);
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
            $photo = $photo_name;
        } else {
            echo "Error uploading photo.";
            exit;
        }
    } else if (isset($_FILES['photo']) && $_FILES['photo']['error'] != 4) { // 4 means no file was uploaded
        echo "Error uploading photo.";
        exit;
    }

    // Update the room information in the database
    $sql = "UPDATE rooms SET room_name=?, description=?, price=?, minpax=?, maxpax=?, image_path=?, is_offered=? WHERE room_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdiisii", $room_name, $description, $price, $minpax, $maxpax, $photo, $is_offered, $room_id);

    if ($stmt->execute()) {
        // Remove existing inclusions for the room
        $delete_inclusions_sql = "DELETE FROM room_inclusions WHERE room_id = ?";
        $delete_stmt = $conn->prepare($delete_inclusions_sql);
        $delete_stmt->bind_param("i", $room_id);
        $delete_stmt->execute();
        $delete_stmt->close();

        if (!is_array($inclusions)) {
            echo "Inclusions data is not an array.";
            exit;
        }
        
        // Insert new inclusions for the room
        foreach ($inclusions as $inclusion_id) {
            $insert_stmt = $conn->prepare("INSERT INTO room_inclusions (room_id, inclusion_id) VALUES (?, ?)");
            $insert_stmt->bind_param("ii", $room_id, $inclusion_id);
            $insert_stmt->execute();
            $insert_stmt->close();
        }
        

        echo "Room updated successfully!";
    } else {
        echo "Error updating room: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>