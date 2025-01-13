<?php
session_start(); // Start the session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Store POST data directly into session without htmlspecialchars
    $_SESSION['adult'] = $_POST['adults'];
    $_SESSION['child'] = !empty($_POST['children']) ? $_POST['children'] : 0;
    $_SESSION['pwd'] = !empty($_POST['pwd']) ? $_POST['pwd'] : 0;
    $_SESSION['reservationType'] = $_POST['reservationType'];
    $_SESSION['roomIds'] = 0;

    // Now, assign session data to variables for use
    $adults = $_SESSION['adults'];
    $children = $_SESSION['child'];
    $pwd = $_SESSION['pwd'];
    $reservationType = $_SESSION['reservationType'];
    $totalpax = (int)$_SESSION['adult'] + (int)$_SESSION['child'] + (int)$_SESSION['pwd'];
    $_SESSION['totalpax'] = $totalpax;

    // Display the sanitized data (using htmlspecialchars when rendering in HTML)
    echo "Number of Adults: " . htmlspecialchars($adults) . "<br>";
    echo "Number of Children: " . htmlspecialchars($children) . "<br>";
    echo "Number of PWD: " . htmlspecialchars($pwd) . "<br>";
    echo "Reservation Type: " . htmlspecialchars($reservationType) . "<br>";
} else {
    echo "Invalid request method.";
}
?>