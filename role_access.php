<?php

function checkAccess($requiredType) {
    if (!isset($_SESSION['role'])) {
        echo '<script> alert("You must be logged in to view this page"); 
                   window.location="/lanmar/index.php"; 
        </script>';
        exit();
    }

    $sessionType = $_SESSION['role'];

    if ($sessionType !== $requiredType) {
        switch ($sessionType) {
            case 'admin':
                header("Location: /lanmar/admin_home_chat.php");
                break;
            case 'user':
                header("Location: /lanmar/index1.php");
                break;
            default:
                header("Location: /lanmar/index.php");
                break;
        }
        exit();
    }
}
?>
