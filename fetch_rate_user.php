<?php
include("connection.php");

$dateIn = $_POST['dateIn'];
$dateOut = $_POST['dateOut'];
$checkOut = $_POST['checkOut'];
$totalPax = $_POST['totalPax'] ?? 0;

$rateType = ($dateIn === $dateOut) ? 1 : 2;
$baseRate = 0;
$additionalRate = 0;
$additionalRateCheckout = 0;
$rateCategory = 0;

$checkOutTime = new DateTime($checkOut);
$isDayRate = $checkOutTime->format('H:i') >= '6:00';

$dateInObj = new DateTime($dateIn);
$dateOutObj = new DateTime($dateOut);
$interval = $dateInObj->diff($dateOutObj);
$day = $interval->days;
$daysToLoop = $day - 1;


if ($rateType == 2 && (!$isDayRate || $daysToLoop > 2 )) {

    $baseOvernightQuery = $pdo->prepare("SELECT price FROM prices_tbl WHERE id = 2");
    $baseOvernightQuery->execute();
    $baseOvernightRate = $baseOvernightQuery->fetchColumn();

    $baseRate = $baseOvernightRate;

    $isOvernight = $checkOutTime->format('H:i') >= '18:00' && $checkOutTime->format('H:i') <= '23:30';

    for ($i = 0; $i < $daysToLoop; $i++) {
        if ($totalPax >= 20) {
            $rateCategory = 10;
        } elseif ($totalPax >= 16) {
            $rateCategory = 8;
        } elseif ($totalPax >= 1) {
            $rateCategory =  6;
        }

        $additionalRateQuery = $pdo->prepare("SELECT price FROM prices_tbl WHERE id = :rateCategory");
        $additionalRateQuery->bindValue(':rateCategory', $rateCategory, PDO::PARAM_INT);
        $additionalRateQuery->execute();
        $additionalRate += $additionalRateQuery->fetchColumn(); // Add the fetched value to $additionalRate
    }

    if ($checkOutTime->format('H:i') > '06:00') {
        if ($totalPax >= 20) {
            $rateCategory = $isOvernight ? 10 : 9;
        } elseif ($totalPax >= 16) {
            $rateCategory = $isOvernight ? 8 : 7;
        } elseif ($totalPax >= 10) {
            $rateCategory = $isOvernight ? 6 : 5;
        }

        $additionalRateQuery = $pdo->prepare("SELECT price FROM prices_tbl WHERE id = :rateCategory");
        $additionalRateQuery->bindValue(':rateCategory', $rateCategory, PDO::PARAM_INT);
        $additionalRateQuery->execute();
        $additionalRateCheckout = $additionalRateQuery->fetchColumn();

        $baseRate += $additionalRate + $additionalRateCheckout;
    } else {
        $baseRate += $additionalRate;
    }
} else {
    $rateQuery = $pdo->prepare("SELECT price FROM prices_tbl WHERE id = :rateType");
    $rateQuery->bindValue(':rateType', $rateType, PDO::PARAM_INT);
    $rateQuery->execute();
    $baseRate = $rateQuery->fetchColumn();
}

$extraRateQuery = $pdo->prepare("SELECT price FROM prices_tbl WHERE id = 3");
$extraRateQuery->execute();
$extraAdultRate = $extraRateQuery->fetchColumn();

$additional = $additionalRate + $additionalRateCheckout;

$response = ['baseRate' => $baseRate, 'extraAdultRate' => $extraAdultRate, 'additional' => $additional];
echo json_encode($response);

?>
