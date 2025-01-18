<?php
include 'connection.php';

$query = "SELECT inclusion_id, inclusion_name FROM inclusion_tbl";
$result = mysqli_query($conn, $query);

$inclusions = [];
while ($row = mysqli_fetch_assoc($result)) {
    $inclusions[] = $row;
}

echo json_encode($inclusions);
?>