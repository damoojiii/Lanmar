<?php
include "connection.php";

// Query to fetch booking process values
$sql = "SELECT label, value FROM bookingprocess_tbl";
$result = $conn->query($sql);

$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[$row['label']] = $row['value'];
    }
}

header('Content-Type: application/json');
echo json_encode($data);

$conn->close();
?>
