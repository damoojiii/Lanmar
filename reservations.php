<?php
// Include the database connection
include("connection.php");

// Fetch reservations from the database
$query = "SELECT * FROM reservations ORDER BY check_in_date DESC";
$result = $conn->query($query);

// Check if there are any reservations
if ($result->num_rows > 0) {
    echo "<h2>Reservations</h2>";
    echo "<table class='table table-striped'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Reservation ID</th>";
    echo "<th>Guest Name</th>";
    echo "<th>Check-in Date</th>";
    echo "<th>Check-out Date</th>";
    echo "<th>Room Type</th>";
    echo "<th>Status</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['reservation_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['guest_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['check_in_date']) . "</td>";
        echo "<td>" . htmlspecialchars($row['check_out_date']) . "</td>";
        echo "<td>" . htmlspecialchars($row['room_type']) . "</td>";
        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";
} else {
    echo "<p>No reservations found.</p>";
}

// Close the database connection
$conn->close();
?>
