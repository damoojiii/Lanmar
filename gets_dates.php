<?php
header('Content-Type: application/json');

include("connection.php");

$sql = "SELECT date FROM booking_tbl";
$result = $conn->query($sql);

$dates = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $dates[] = $row['date'];
    }
}

$conn->close();

echo json_encode($dates);
?>
