<?php
include 'connection.php';

$sql = "SELECT id, payment_name FROM prices_tbl";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<select id="price-selector" class="form-control">';
    while($row = $result->fetch_assoc()) {
        echo '<option value="' . $row['id'] . '">' . $row['payment_name'] . '</option>';
    }
    echo '</select>';
} else {
    echo 'No prices found';
}

$conn->close();
?>
