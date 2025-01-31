<?php 
    session_start();
    include("connection.php");
    include "role_access.php";
    checkAccess('user');
    $userId = $_SESSION['user_id']; 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lanmar Resort</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/all.min.css">
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/fontawesome.min.css">
    <?php include "sidebar-design.php"; ?>
</head>
<style>
    .container {
        max-width: 80%;
    }

    body {
        background-color: #f8f9fa;
    }

    .chat-container {
        max-width: 100%;
        margin: 0 auto;
        padding: 15px;
    }

    .chat-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 15px;
        background-color: #001A3E;
        color: #fff;
        border-radius: 8px;
    }

    .chat-header h3 {
        margin: 0;
    }

    .chat-area {
        margin-top: 20px;
        height: 65vh;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 15px;
        overflow-y: auto;
    }

    .message {
        display: flex;
        align-items: flex-end;
        margin-bottom: 15px;
        padding-bottom: 10px;
    }

    .message.sent {
        justify-content: flex-end;
    }

    .message img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin: 0 10px;
    }

    .message-content {
        max-width: 70%;
        padding: 10px 15px;
        border-radius: 15px;
        word-wrap: break-word;
        position: relative;
    }

    .message.sent .message-content {
        background-color: #001A3E;
        color: #fff;
    }
    .message.received .message-content {
        background-color: #e9ecef;
        color: #000;
    }

    .message-timestamp {
        font-size: 0.8rem;
        color: #6c757d;
        position: absolute;
        bottom: -18px;
        right: 10px;
    }

    .date-stamp {
        text-align: center;
        margin: 15px 0;
        font-size: 0.9rem;
        color: #6c757d;
        font-weight: bold;
        position: relative;
    }

    .date-stamp span {
        background: #f8f9fa;
        padding: 5px 10px;
        border-radius: 15px;
        display: inline-block;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    .chat-footer {
        display: flex;
        align-items: center;
        margin-top: 15px;
        gap: 10px;
    }

    .chat-footer textarea {
        resize: none;
        flex: 1;
        padding: 10px;
        border-radius: 8px;
        border: 1px solid #ced4da;
        min-height: 40px;
        max-height: 120px;
    }

    .chat-footer button {
        background-color: #001A3E;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 8px 16px;
    }

    .form-container {
        background: #fff;
        border-radius: 8px;
        padding: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        max-width: 500px;
        margin: 2rem auto;
    }
    .form-header {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 1rem;
    }

    @media (max-width: 768px) {
        #main-content{
            padding: 0;
        }
        .container {
            max-width: 100%;
        }
        .message.sent {
            justify-content: flex-end;
        }
        .chat-header h3 {
            font-size: 18px;
        }

        .message-content {
            max-width: 85%;
        }

        .chat-footer textarea {
            font-size: 14px;
        }
    }
</style>


<body>
<!-- Sidebar -->
<div id="sidebar" class="d-flex flex-column p-3 text-white position-fixed vh-100">
    <a href="#" class="mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <span class="fs-4">Lanmar Resort</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="index1.php" class="nav-link text-white">Book Here</a>
        </li>
        <li><a href="my-reservation.php" class="nav-link text-white">My Reservations</a></li>
        <li><a href="my-notification.php" class="nav-link text-white">Notification</a></li>
        <li><a href="chats.php" class="nav-link text-white active">Chat with Lanmar</a></li>
        <li><a href="my-feedback.php" class="nav-link text-white">Feedback</a></li>
        <li><a href="settings_user.php" class="nav-link text-white">Settings</a></li>
    </ul>
    <hr>
    <a href="logout.php" class="nav-link text-white">Log out</a>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container-fluid">
        <button id="hamburger" class="navbar-toggler" type="button"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0"></ul>
        </div>
    </div>
</nav>
<?php 
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $bookingId = $_GET['id'];

        // Check if the booking already exists in cancel_tbl
        $exist = "SELECT booking_id FROM cancel_tbl WHERE booking_id = :id";
        $stmt_exist = $pdo->prepare($exist);
        $stmt_exist->bindParam(":id", $bookingId, PDO::PARAM_INT);
        $stmt_exist->execute();
        $result_exist = $stmt_exist->fetch(PDO::FETCH_ASSOC);

        if ($result_exist) {
            echo '<script>  
                    window.location="my-reservation.php"; 
                  </script>';
            exit(); 
        }

    } else {
        echo '<script>  
                window.location="my-reservation.php"; 
        </script>';
        exit(); 
    }

    

    
    $sql = "SELECT booking_id, dateIn, dateOut, checkin, checkout FROM booking_tbl WHERE booking_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":id", $bookingId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        $date = ($result["dateIn"] != $result["dateOut"]) 
        ? date("F j, Y", strtotime($result["dateIn"])) . ' to ' . date("F j, Y", strtotime($result["dateOut"])) 
        : date("F j, Y", strtotime($result["dateIn"]));
        $time = date("g:i A", strtotime($result["checkin"])) . ' to ' . date("g:i A", strtotime($result["checkout"]));
    }
}
?>
<div id="main-content" class="container mt-2">
    <div class="form-container">
        <div class="form-header text-center">Cancellation Form</div>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="reservationId" class="form-label">Reservation ID</label>
                <input type="text" class="form-control" id="reservationId" name="reservation_id" value="<?php echo $bookingId ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="cancellationDate" class="form-label">Date</label>
                <input type="text" class="form-control" id="cancellationDate" value="<?php echo $date ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="cancellationTime" class="form-label">Time</label>
                <input type="text" class="form-control" id="cancellationTime" value="<?php echo $time ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="cancellationReason" class="form-label">Reason for Cancellation</label>
                <textarea class="form-control" id="cancellationReason" name="cancellation_reason" rows="4" placeholder="Please explain your reason..." required></textarea>
            </div>
            <button type="submit" class="btn btn-danger w-100">Submit Cancellation</button>
            <a href="my-reservation.php" class="btn btn-secondary w-100 mt-1">Cancel</a>
        </form>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $reservationId = $_POST['reservation_id'];
            $cancellationReason = $_POST['cancellation_reason'];
            $timestamp = date('Y-m-d H:i:s'); // Get the current timestamp

            // Insert into cancel_tbl
            $sql = "INSERT INTO cancel_tbl (booking_id, cancellation_reason,is_read, timestamp) VALUES (:booking_id, :cancellation_reason, 0, :timestamp)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':booking_id', $reservationId, PDO::PARAM_INT);
            $stmt->bindParam(':cancellation_reason', $cancellationReason, PDO::PARAM_STR);
            $stmt->bindParam(':timestamp', $timestamp, PDO::PARAM_STR);

            $statusQuery = "SELECT status FROM booking_tbl WHERE booking_id = :booking_id";
            $statusStmt = $pdo->prepare($statusQuery);
            $statusStmt->bindParam(':booking_id', $reservationId, PDO::PARAM_INT);
            $statusStmt->execute();
            $currentStatus = $statusStmt->fetchColumn();

            // Update the status based on the current status
            if ($currentStatus === 'Pending') {
                $updateQuery = "UPDATE booking_tbl SET status = 'Cancellation1' WHERE booking_id = :booking_id";
            } else if ($currentStatus === 'Approved') {
                $updateQuery = "UPDATE booking_tbl SET status = 'Cancellation2' WHERE booking_id = :booking_id";
            }
            

            if ($stmt->execute()) {
                if (isset($updateQuery)) {
                    $updateStmt = $pdo->prepare($updateQuery);
                    $updateStmt->bindParam(':booking_id', $reservationId, PDO::PARAM_INT);
                    $updateStmt->execute();
                }
        
                echo '<script>  
                        alert("Cancellation submitted successfully.");  
                        window.location="chats.php"; 
                      </script>';
            } else {
                echo '<script>  
                        alert("There was an error processing your request. Please try again."); 
                      </script>';
            }
        }
        ?>

    </div>
</div>



<script src="assets/vendor/bootstrap/js/jquery.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    document.getElementById('hamburger').addEventListener('click', function() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('show');
    
    const navbar = document.querySelector('.navbar');
    navbar.classList.toggle('shifted');
    
    const mainContent = document.getElementById('main-content');
    mainContent.classList.toggle('shifted');
    });

</script>
</body>
</html>