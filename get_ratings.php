<?php
include 'connection.php';

$year = $_GET['year'] ?? date('Y'); 
// SQL query to count ratings by year
$sql = "SELECT rating, COUNT(*) AS rating_count
        FROM feedback_tbl
        WHERE YEAR(created_at) = :year
        GROUP BY rating";

$stmt = $pdo->prepare($sql);
$stmt->execute([':year' => $year]);
$ratingsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

$ratings = [];
$rating_counts = [];

foreach ($ratingsData as $row) {
    $ratings[] = $row['rating'];
    $rating_counts[] = $row['rating_count'];
}

// Return the data as JSON
echo json_encode([
    'ratings' => $ratings,
    'rating_counts' => $rating_counts
]);
?>
