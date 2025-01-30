<?php
include 'connection.php';

$query = "SELECT * FROM faq_tbl ORDER BY id DESC";
$result = mysqli_query($conn, $query);

$faqs = [];
while ($row = mysqli_fetch_assoc($result)) {
    $faqs[] = $row;
}

// Get the current FAQ count
$faqCountQuery = "SELECT COUNT(*) AS total FROM faq_tbl";
$faqCountResult = mysqli_query($conn, $faqCountQuery);
$faqCountRow = mysqli_fetch_assoc($faqCountResult);
$totalFAQs = $faqCountRow['total'];

// Send both FAQs and the count
$response = [
    "faqs" => $faqs,
    "totalFAQs" => $totalFAQs
];

echo json_encode($response);
?>