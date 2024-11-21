<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lanmartest";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
