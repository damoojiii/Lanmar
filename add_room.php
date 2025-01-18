<?php
include("connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_name = $_POST['room_name'];
    $description = $_POST['description'];
    $minpax = $_POST['minpax'];
    $maxpax = $_POST['maxpax'];
    $price = $_POST['price'];
    $is_offered = $_POST['is_offered'];
    $inclusions = isset($_POST['inclusions']) ? $_POST['inclusions'] : [];

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image_path"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["image_path"]["tmp_name"], $target_file)) {
            $sql = "INSERT INTO rooms (room_name, image_path, description, minpax, maxpax, price, is_offered) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssiiss", $room_name, $target_file, $description, $minpax, $maxpax, $price, $is_offered);

            if ($stmt->execute()) {
                $room_id = $stmt->insert_id;

                $stmt->close();

                $inclusionSql = "INSERT INTO room_inclusions (room_id, inclusion_id) VALUES (?, ?)";
                $inclusionStmt = $conn->prepare($inclusionSql);
                
                foreach ($inclusions as $inclusion_id) {
                    $inclusionStmt->bind_param("ii", $room_id, $inclusion_id);
                    $inclusionStmt->execute();
                }
                $inclusionStmt->close();
            } else {
            }
        } else {
        }
    }

    $conn->close();
}
?>
