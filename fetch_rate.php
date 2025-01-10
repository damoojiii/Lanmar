<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=lanmartest", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

$dateIn = $_POST['dateIn'];
$dateOut = $_POST['dateOut'];

$rateType = ($dateIn === $dateOut) ? 1 : 2;
$rateQuery = $pdo->prepare("SELECT price FROM prices_tbl WHERE id = :rateType");
$rateQuery->bindValue(':rateType', $rateType, PDO::PARAM_INT);
$rateQuery->execute();
$baseRate = $rateQuery->fetchColumn();

$extraRateQuery = $pdo->prepare("SELECT price FROM prices_tbl WHERE id = 3");
$extraRateQuery->execute();
$extraAdultRate = $extraRateQuery->fetchColumn();

echo json_encode(['baseRate' => $baseRate, 'extraAdultRate' => $extraAdultRate]);
?>
