<?php
include 'connection.php';

// Get the selected year from the query parameter
$year = $_GET['year'];

// Query to fetch room names and total revenues for the selected year
$sql = "SELECT room_tbl.room_name, 
            SUM(CASE 
                    WHEN booking_tbl.dateIn != booking_tbl.dateOut 
                            AND rooms.is_offered = 1 
                            AND offered_rooms.first_offered_room = 1 THEN 0
                    ELSE rooms.price 
                END) AS total_revenue
        FROM room_tbl
        JOIN rooms ON room_tbl.room_Id = rooms.room_id
        JOIN bill_tbl ON room_tbl.bill_id = bill_tbl.bill_id
        JOIN booking_tbl ON bill_tbl.bill_id = booking_tbl.bill_id
        LEFT JOIN (
            SELECT booking_tbl.booking_id, 
                room_tbl.room_name,
                rooms.is_offered,
                ROW_NUMBER() OVER(PARTITION BY booking_tbl.booking_id ORDER BY room_tbl.room_name) AS room_number,
                CASE 
                    WHEN rooms.is_offered = 1 THEN 1 
                    ELSE 0 
                END AS first_offered_room
            FROM room_tbl
            JOIN rooms ON room_tbl.room_Id = rooms.room_id
            JOIN booking_tbl ON room_tbl.bill_id = booking_tbl.bill_id
            WHERE rooms.is_offered = 1
        ) AS offered_rooms 
        ON booking_tbl.booking_id = offered_rooms.booking_id 
        WHERE booking_tbl.status NOT IN ('Pending', 'Rejected', 'Cancelled')
        AND YEAR(booking_tbl.created_at) = :year
        GROUP BY room_tbl.room_name";
        
$stmt = $pdo->prepare($sql);
$stmt->execute([':year' => $year]);

// Fetch the data
$roomRevenue = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare the data for the chart
$room_names = [];
$total_revenues = [];

foreach ($roomRevenue as $row) {
    $room_names[] = $row['room_name'];
    $total_revenues[] = $row['total_revenue'];
}

// Return the data as JSON
echo json_encode([
    'room_names' => $room_names,
    'total_revenues' => $total_revenues
]);
?>
