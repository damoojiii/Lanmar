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
    .container{
        max-width: 80%;
    }
    .chat-area{
        display: flex;
        margin: 10px 15px;
        align-items: flex-end;
        height: 75vh;
    }
    .sender-box{
        display: flex;
        justify-content: flex-end;
    }
    .sender{
        margin: 5px;
        padding: 5px;
        background: #001A3E;
        border-radius: 10px;
        height: auto;
        color: #fff;

    }
    .sender-box .chat-details{
        display: flex;
        justify-content: flex-end;
        margin-right: 5px;
    }
    .msg-text{
        margin: 3px;
        padding: 5px;
        text-align: justify;
    }
    .receiver-box{
        display: flex;
        justify-content: flex-start;
    }
    .receiver-box .chat-details{
        margin-left: 5px;
    }
    .receiver{
        margin: 5px;
        padding: 5px;
        background: lightgrey;
        border-radius: 10px;
        height: auto;
        color: #222;

    }
    .chat{
        border: #222 solid 2px;
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
        <li><a href="#" class="nav-link text-white">Notification</a></li>
        <li><a href="chats.php" class="nav-link text-white active">Chat with Lanmar</a></li>
        <li><a href="#" class="nav-link text-white">Feedback</a></li>
        <li><a href="#" class="nav-link text-white">Settings</a></li>
    </ul>
    <hr>
    <a href="#" class="nav-link text-white">Log out</a>
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

<div id="main-content" class="container mt-4 pt-3">
    <div class="">
        <div class="d-flex" style="gap: 0.5rem; align-items: center;">
            <h3>Lanmar Resort</h3>
            <span>Active ## ago</span>
        </div>
        <hr class="style">
        <div class="chat-area">
            <div class="" style="width: 100%;">
                <div class="sender-box mb-1">
                    <div class="sender">
                        <div class="msg-text">ASDASHDHASDHSAHD</div>
                    </div>
                </div>
                <div class="receiver-box mb-1">
                    <div class="">
                        <div class="receiver">
                            <div class="msg-text">ASDASHDHASDHSAHD</div>
                        </div>
                        <span class="chat-details">Received at 09-09-2000 3:00 PM</span>
                    </div>
                </div>
                <div class="sender-box mb-1">
                    <div class="">
                        <div class="sender">
                            <div class="msg-text">SDHASHDSHADHSADHSAHDSHADHSADHSIAHDSAHDHSADHSAHDASHDSAHDSAHDASHDSHADHSADHASDHASDHASH</div>
                        </div>
                        <div class="chat-details">
                            <span class="">Received at 09-09-2000 3:00 PM</span>
                        </div>
                    </div>
                    
                    
                </div>
                <div class="col-md-6 mt-4 pt-2" style="width: 100%;">
                    <form action="" class="d-flex justify-content-between">
                        <div class="" style="width: 90%;">
                            <input type="text" class="form-control chat">
                        </div>
                        <div class="" style="">
                            <button type="submit" name="send" class="btn send">Send</button>
                        </div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/vendor/bootstrap/js/jquery.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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