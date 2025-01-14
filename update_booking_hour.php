<?php
include 'connection.php';

if (isset($_POST['id']) && isset($_POST['value'])) {
    $id = $_POST['id'];
    $value = $_POST['value'];
    $isValid = false;
    $errorMsg = '';

    // Validate that the value is a whole number or ends in .5
    if ($value % 1 == 0 || $value % 1 == 0.5) {
        if ($id == 1 || $id == 2) { 
            if ($value >= 6 && $value <= 23.5) {
                $isValid = true;
            } else {
                $errorMsg = "Hour for Starting Time and Closing Time must be between 6 and 23.5.";
            }
        } elseif ($id == 3) { 
            if ($value >= 1 && $value <= 5) {
                $isValid = true;
            } else {
                $errorMsg = "Hour for Cleanup Time must be between 1 and 5.";
            }
        }
    } else {
        $errorMsg = "Hour must be a whole number or end in .5 (e.g., 6, 6.5, 7, 7.5).";
    }

    if ($isValid) {
        $stmt = $conn->prepare("UPDATE bookingprocess_tbl SET value = ? WHERE id = ?");
        $stmt->bind_param("di", $value, $id);
        if ($stmt->execute()) {
            echo "Hour updated successfully.";
        } else {
            echo "Error updating hour.";
        }
        $stmt->close();
    } else {
        echo $errorMsg;
    }

    $conn->close();
}
?>
