<?php
include("connection.php");

if (isset($_GET['type'])) {
    $type = $_GET['type'];

    if ($type == 'yearly') {
        $stmt = $pdo->prepare("
            SELECT YEAR(booking_tbl.dateIn) as year, 
                   SUM(bill_tbl.total_bill) as overallEarnings,
                   SUM(CASE WHEN booking_tbl.dateIn = booking_tbl.dateOut THEN bill_tbl.total_bill ELSE 0 END) as daytimeEarnings,
                   SUM(CASE WHEN booking_tbl.dateIn != booking_tbl.dateOut THEN bill_tbl.total_bill ELSE 0 END) as overnightEarnings
            FROM bill_tbl
            JOIN booking_tbl ON bill_tbl.bill_id = booking_tbl.bill_id
            WHERE booking_tbl.status NOT IN ('Pending','Cancelled','Rejected')
            GROUP BY year
        ");
    } else {
        $stmt = $pdo->prepare("
            SELECT MONTHNAME(booking_tbl.dateIn) as month, 
                   SUM(bill_tbl.total_bill) as overallEarnings,
                   SUM(CASE WHEN booking_tbl.dateIn = booking_tbl.dateOut THEN bill_tbl.total_bill ELSE 0 END) as daytimeEarnings,
                   SUM(CASE WHEN booking_tbl.dateIn != booking_tbl.dateOut THEN bill_tbl.total_bill ELSE 0 END) as overnightEarnings
            FROM bill_tbl
            JOIN booking_tbl ON bill_tbl.bill_id = booking_tbl.bill_id
            WHERE booking_tbl.status NOT IN ('Pending','Cancelled','Rejected')
            GROUP BY month
            ORDER BY MONTH(booking_tbl.dateIn)
        ");
    }

    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);
}
?>
