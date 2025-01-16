<?php 
include 'connection.php';

if (isset($_GET['year'])) {
    $year = $_GET['year'];

    // Query to fetch bookings count per month for the given year
    $sql = "SELECT MONTH(created_at) AS month, COUNT(*) AS bookings_count
            FROM booking_tbl
            WHERE YEAR(created_at) = :year
            GROUP BY MONTH(created_at)
            ORDER BY month";

    // Prepare the query
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':year' => $year]);

    // Fetch the data
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $bookings_count = [];

    foreach ($result as $row) {
        $bookings_count[] = $row['bookings_count'];
    }

    echo json_encode(['bookings_count' => $bookings_count]);
}
?>