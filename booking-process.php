<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "lanmar_db";

    $con = new mysqli($servername, $username, $password, $dbname);

    if($con->connect_error){
        die("Connection Failed".$con->connect_error);
    }

    /*echo "Date: " . $dateIn . "<br>";
    echo "Date: " . $dateOut . "<br>";
    echo "Check-in: ".$checkin. " <br>";
    echo "Check-out: ".$checkout. " <br>";
    echo "Number of Hours: " . $numhours . "<br>";

    $sql = "INSERT INTO `booking_tbl`(`dateIn`,`dateOut`, `checkin`, `checkout`, `hours`) VALUES ('$dateIn','$dateOut','$checkin','$checkout','$numhours')";
    
    if($con->query($sql) === TRUE){
        echo '<script>alert("Booking successful!");</script>';
        echo '<script>window.location="index.php";</script>';
    }
    else{
        echo '<script>alert("Error: ' . $con->error . '");</script>';
        echo '<script>window.location="index.php";</script>';     
    }
    */
?>