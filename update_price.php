<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $price = $_POST['price'];

    // Validate inputs
    if (!empty($id) && !empty($price)) {
        $stmt = $conn->prepare("UPDATE prices_tbl SET price = ? WHERE id = ?");
        $stmt->bind_param("di", $price, $id);

        if ($stmt->execute()) {
            echo "Price updated successfully.";
        } else {
            echo "Error updating price.";
        }
        $stmt->close();
    } else {
        echo "Invalid input.";
    }
}
?>
