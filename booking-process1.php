<?php
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=lanmartest", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }

    session_start();
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
    <style>
        .progress-container {
            width: 100%;
            margin: 5px 0;
            height: 100px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background:lightgray;
        }

        .progress-bar {
            width: 100%;
            margin-left: 300px;
            display: flex;
            flex-direction: row;
            gap: 3.5rem;
            position: relative;
        }

        .progress-bar::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 28%;
            width: 45%;
            height: 5px;
            background-color: white;
            z-index: -1;
            transform: translateY(-50%);
        }

        .step {
            text-align: center;
            position: relative;
        }

        .step .circle {
            width: 30px;
            height: 30px;
            background-color: white;
            border: 2px solid white;
            border-radius: 50%;
            margin: 0 auto;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-weight: bold;
        }

        .step.completed .circle {
            background-color: #00214b; /* Blue background for completed steps */
            border-color: #00214b; /* Blue border */
            color: white; 
        }

        .step.completed, .step .circle {
            background-color: lightgrey;
            border-color: white; 
            color: black; 
        }

        .step::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100%;
            height: 5px;
            background-color: #00214b; /* Blue color for progress line */
            transform: translateY(-50%);
            z-index: -1;
        }

        .step:last-child::after {
            content: none;
        }

        .step:not(.completed)::after {
            background-color: white;
        }
    </style>
    <style>
        .summary {
            background-color: #00214b;
            color: #fff;
            width: 25%;
            height: 100%;
        }

        .summary .section-header {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .summary .bg-light {
            background-color: #d1d5db; /* light gray for contrast */
            color: #212529; /* dark text for readability */
        }

        .summary #booked-rooms .btn-link {
            font-size: 1.5rem;
            line-height: 1;
        }

        .summary table {
            margin-top: 20px;
            font-size: 1rem;
        }

        .summary table td {
            border: none;
            padding: 5px 0;
        }

        .summary .btn-primary {
            background-color: #003366;
            border: none;
            font-size: 1.2rem;
        }

        .section-header {
            padding: 10px 0;
            font-size: 18px;
            font-weight: bold;
            border-bottom: 1px solid #ccc;
            margin-bottom: 10px;
        }
        .container{
            max-width: 80%;
        }
        .mb-3 {
            margin-bottom: 1rem;
        }
        .form-control, .form-select {
            width: 100%;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #212529;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            appearance: none;
            border-radius: 0.25rem;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }
        .btn-primary {
            color: #fff;
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        .btn-secondary {
            color: #fff;
            background-color: #6c757d;
            border-color: #6c757d;
        }
        
        .placeholder-img {
            background-size: cover;
            background-position: center;
        }

        #room-selection .list-group-item {
            cursor: pointer;
            height: 70px;
            margin-bottom: 0.5rem;
        }

        #room-selection .list-group-item.active, .add-room, .check{
            background-color: #004080;
            color: white;
        }
        .table-summary{
            margin-top: 10px;
            border-top: 1px solid #ccc;
        }
    </style>
</head>


<body>

<!-- Sidebar -->
<div id="sidebar" class="d-flex flex-column p-3 text-white position-fixed vh-100">
    <a href="#" class="mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <span class="fs-4">Lanmar Resort</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="index1.php" class="nav-link text-white active">Book Here</a>
        </li>
        <li><a href="my-reservation.php" class="nav-link text-white">My Reservations</a></li>
        <li><a href="#" class="nav-link text-white">Notification</a></li>
        <li><a href="chats.php" class="nav-link text-white">Chat with Lanmar</a></li>
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

<!-- Progress bar -->
<div class="progress-container">
    <div class="progress-bar">
        <div class="step completed">
            <div class="circle">1</div>
            <span>Check in & Check out</span>
        </div>
        <div class="step completed">
            <div class="circle">2</div>
            <span>Rooms & Rates</span>
        </div>
        <div class="step">
            <div class="circle">3</div>
            <span>Guest Information</span>
        </div>
        <div class="step">
            <div class="circle">4</div>
            <span>Payment & Receipt</span>
        </div>
    </div>
</div>

<?php 
$rooms = [];
$totalpax = 0;

if (isset($_GET['continue'])) {
    $_SESSION['dateIn'] = $_GET['dateIn'];
    $_SESSION['dateOut'] = $_GET['dateOut'];
    $_SESSION['checkin'] = $_GET['checkin'];
    $_SESSION['checkout'] = $_GET['checkout'];
    $_SESSION['numhours'] = $_GET['numhours'];
}

// Load from session if available
$dateIn = $_SESSION['dateIn'] ?? '';
$dateOut = $_SESSION['dateOut'] ?? '';
$checkin = $_SESSION['checkin'] ?? '';
$checkout = $_SESSION['checkout'] ?? '';
$numhours = $_SESSION['numhours'] ?? '';

// Check if booking is for a single day or overnight
$rateType = ($dateIn === $dateOut) ? '1' : '2';

$rateQuery = $pdo->prepare("SELECT price FROM prices_tbl WHERE id = :rateType");
$rateQuery->bindValue(':rateType', $rateType, PDO::PARAM_STR);
$rateQuery->execute();
$rate = $rateQuery->fetchColumn();

$_SESSION['rate'] = $rate;

$checkinDisplay = (new DateTime($checkin))->format('g:i A');
$checkoutDisplay = (new DateTime($checkout))->format('g:i A');

// Calculate total pax and get room info if check is set
if (isset($_GET['check'])) {
    $_SESSION['adult'] = filter_input(INPUT_GET, 'adults', FILTER_SANITIZE_NUMBER_INT);
    $_SESSION['child'] = filter_input(INPUT_GET, 'children', FILTER_SANITIZE_NUMBER_INT);
    $_SESSION['pwd'] = filter_input(INPUT_GET, 'pwd', FILTER_SANITIZE_NUMBER_INT);
    $_SESSION['reservationType'] = $_GET['reservationType'];

    $totalpax = (int)$_SESSION['adult'] + (int)$_SESSION['child'] + (int)$_SESSION['pwd'];
    $_SESSION['totalpax'] = $totalpax;

    $adult = $_SESSION['adult'] ?? 0;
    $child = $_SESSION['child'] ?? 0;
    $pwd = $_SESSION['pwd'] ?? 0;
    
    $additionalCharge = 0;
    if ($adult > 10) {
        $extraAdultCount = $adult - 10;
        $extraRateQuery = $pdo->prepare("SELECT price FROM prices_tbl WHERE id = :id");
        $extraRateQuery->bindValue(':id', 3 , PDO::PARAM_INT);
        $extraRateQuery->execute();
        $additionalCharge = $extraRateQuery->fetchColumn() * $extraAdultCount;
    }

    $_SESSION['rate'] = $rate + $additionalCharge;

    // Load rooms based on pax capacity
    if ($totalpax > 0) {
        $sql = "
            SELECT room_id, room_name, image_path, description, minpax, maxpax, price, is_offered 
            FROM rooms
            " . 
            ($rateType == '1' ? "ORDER BY (minpax <= :totalpax AND maxpax >= :totalpax) DESC, minpax ASC" : "") . "
        ";

        $stmt = $pdo->prepare($sql);

        if ($rateType == '1') {
            $stmt->bindValue(':totalpax', $totalpax, PDO::PARAM_INT);
        }

        $stmt->execute();
        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
} else {
    $totalpax = $_SESSION['totalpax'] ?? 0;
}
?>


<!-- Main content -->
<div id="main-content" class="container mt-4 pt-3">
    <div class="container1">
        <div class="row" style="justify-content:space-between;">
            <div class="col-md-6" style="width: 75%;">
                <div class="section-header">Number of Guest (Pax)</div>
                <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <label for="adults" class="form-label">Adult(s)</label>
                            <input type="number" min="0" id="adults" name="adults" class="form-control" value="<?php echo $adult; ?>">
                        </div>
                        <div class="col-md-2">
                            <label for="children" class="form-label">Child(ren)</label>
                            <input type="number" min="0" id="children" name="children" class="form-control" value="<?php echo $child; ?>">
                        </div>
                        <div class="col-md-2">
                            <label for="pwd" class="form-label">PWD(s)</label>
                            <input type="number" min="0" id="pwd" name="pwd" class="form-control" value="<?php echo $pwd; ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="reservationType" class="form-label">Type of Reservation:</label>
                            <select id="reservationType" name="reservationType" class="form-control" required>
                                <?php 
                                    $typelist = $pdo->query("SELECT * FROM reservationtype_tbl;");
                                    $types = $typelist->fetchAll(PDO::FETCH_ASSOC);

                                    $selectedType = $_SESSION['reservationType'] ?? '';

                                    echo '<option value="" hidden>Choose...</option>';
                                    foreach($types as $type) {
                                        $typename = $type['reservation_type'];
                                        $typeId = $type['id'];
                                    
                                        $isSelected = ($typeId == $selectedType) ? 'selected' : '';
                                        
                                        echo "<option value='$typeId' $isSelected>$typename</option>";
                                    }
                                ?>
                            </select>

                        </div>
                        <div class="col-md-2" style="align-content: flex-end;">
                            <button type="submit" name="check" class="btn check">Check Rooms</button>
                        </div>
                    </div>
                </form>

                <div class="section-header">Select Room(s)</div>
                
                <?php if ($totalpax > 0 && !empty($rooms)): ?>
                    <div class="row px-2">
                        <div class="list-group" id="room-selection" style="width: 20%;">
                        <?php foreach ($rooms as $room): ?>
                            <button type="button" class="list-group-item list-group-item-action room-btn" 
                                    data-id="<?php echo $room['room_id']; ?>" 
                                    data-offered="<?php echo $room['is_offered']; ?>">
                                <?php echo htmlspecialchars($room['room_name']); ?>
                            </button>
                        <?php endforeach; ?>
                        </div>
                        <!-- Room Details Section -->
                        <div class="col-md-6 py-3" style="width: 80%;">
                            <div id="room-details">
                                <div class="placeholder-text">Select a room to view details.</div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <p class="text-danger text-center">Please fill up the number of guests before you view the rooms.</p>
                <?php endif; ?>
        
            </div>
                    
            <div class="col-md-6 p-3 summary">
                <form action="booking-process2.php" method="$_GET">
                    <div class="section-header">Booking Summary</div>

                    <div class="bg-light p-3 rounded mb-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p><strong>Date:</strong> <span id="date-input"><?php echo "$dateIn to $dateOut";?></span></p>
                                <p><strong>Time:</strong> <span id="time-input"><?php echo "$checkinDisplay to $checkoutDisplay";?></span></p>
                                <p><strong>Total of Hours:</strong> <span id="hour-input"><?php echo $numhours;?></span></p>
                                <p><strong>No. of Pax:</strong> <span id="total-pax"><?php echo $totalpax; ?></span></p>
                                <p><strong>Reservation Type:</strong> <span id="reservation-type">
                                    <?php 
                                        $reservationTypeId = $_SESSION['reservationType'] ?? null;
                                        $reservationType = ""; 

                                        if ($reservationTypeId) {
                                            $stmt = $pdo->prepare("SELECT reservation_type FROM reservationtype_tbl WHERE id = :id");
                                            $stmt->bindValue(':id', $reservationTypeId, PDO::PARAM_INT);
                                            $stmt->execute();
                                            $reservationType = $stmt->fetchColumn() ?? $reservationType;
                                        }

                                        echo htmlspecialchars($reservationType);
                                    ?>
                                </span></p>
                            </div>
        
                            <div class="dropdown">
                                <button class="btn btn-link p-0" type="button" id="editDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    Edit
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="editDropdown">
                                    <li><a class="dropdown-item" href="#">Edit Date</a></li>
                                    <li><a class="dropdown-item" href="#">Edit Time</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Booked Rooms Section -->
                    <div class="row align-items-center mb-2">
                        <div class="col">
                            <h6 class="mb-0">Booked Rooms</h6>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-secondary btn-sm">Reset</button>
                        </div>
                    </div>
                
                    <div id="booked-rooms" class="mb-3">
                        <div id="no-rooms-message" class="text-light text-center py-2">No Room(s) Selected</div>
                    </div>

                    <!-- Total Calculation Section -->
                    <table class="w-100 text-light table-summary">
                        <tr>
                            <td>Rate:</td>
                            <td class="text-end" id="rate">PHP <?php echo number_format($_SESSION['rate'] ?? 0); ?></td>
                        </tr>
                        <tr>
                            <td>Room:</td>
                            <td class="text-end" id="room-total">PHP 0</td>
                        </tr>
                        <tr>
                            <td><strong>Total:</strong></td>
                            <td class="text-end"><strong id="grand-total">PHP <?php echo number_format($_SESSION['rate'] ?? 0);?></strong></td>
                        </tr>
                    </table>

                    <input type="hidden" name="reservationType" value="<?php echo htmlspecialchars($reservationType); ?>">
                    <input type="hidden" name="origPrice" value="<?php echo number_format($_SESSION['rate'] ?? 0); ?>">
                    <input type="hidden" name="grandTotal" id="grandTotal">
                    <input type="hidden" name="roomTotal" id="roomTotal">

                    <button id="Continue" name="continue" type="submit" class="btn btn-primary w-100 mt-3" >Continue</button>
                </form>
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

const rateType = '<?php echo $rateType; ?>';

document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll('.room-btn').forEach(button => {
        const isOffered = button.dataset.offered === "1";
        if (rateType === '2' && !isOffered) {
            button.style.display = 'none';
        }
    });

    document.querySelectorAll('.room-btn').forEach(button => {
        button.addEventListener('click', function() {
            const roomId = this.dataset.id;
            
            // Highlight the selected room
            document.querySelectorAll('.room-btn').forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            fetch(`getRoomDetails.php?room_id=${roomId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('room-details').innerHTML = `
                        <div class="row">
                            <div class="col-md-6">
                                <img src="${data.image_path}" class="placeholder-img" style="width: 100%; height: 200px;">
                            </div>
                            <div class="col-md-6">
                                <h5>${data.room_name}</h5>
                                <p>${data.description}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <h6>Rate & Details</h6>
                            <div class="d-flex gap-2">
                                <div class="p-3 bg-light" style="flex: 1;">
                                    <p><strong>PHP ${data.price}</strong></p>
                                    <p>Good for: ${data.minpax}-${data.maxpax} pax</p>
                                    <a href="#" class="text-decoration-underline">Conditions</a>
                                </div>
                                <div class="p-3 bg-secondary text-white" style="flex: 1;">
                                    <h6>Includes:</h6>
                                    <ul class="list">
                                        ${data.inclusions.map(inclusion => `<li>${inclusion}</li>`).join('')}
                                    </ul>
                                    <button class="btn mt-2 add-room" onclick="addToSummary(${roomId}, '${data.room_name}', ${data.price}, ${data.minpax}, ${data.maxpax}, ${data.is_offered})">+ Book this room</button>
                                </div>
                            </div>
                        </div>
                    `;
                });
        });
    });
});

let offeredRoomAdded = false;

// Function to show all rooms
function showAllRooms() {
    document.querySelectorAll('.room-btn').forEach(button => {
        button.style.display = 'block'; 
    });
}

// Function to show only is_offered rooms
function showOfferedRoomsOnly() {
    document.querySelectorAll('.room-btn').forEach(button => {
        if (button.dataset.isOffered === '1') {
            button.style.display = 'block'; 
        } else {
            button.style.display = 'none'; 
        }
    });
}

// Function to add selected room to the Booked Rooms summary
function addToSummary(roomId, roomName, price, minpax, maxpax, isOffered) {
    const bookedRoomsContainer = document.getElementById('booked-rooms');
    const noRoomsMessage = document.getElementById('no-rooms-message');

    if (document.getElementById(`room-${roomId}`)) {
        alert("This room is already added to the summary.");
        return;
    }

    if (noRoomsMessage) {
        noRoomsMessage.style.display = 'none';
    }

    // Create the room summary 
    const roomSummary = document.createElement('div');
    roomSummary.classList.add('p-3', 'mb-2', 'bg-light', 'text-dark', 'd-flex', 'justify-content-between', 'align-items-start', 'rounded');
    roomSummary.id = `room-${roomId}`;
    
    // Create the remove button
    const removeButton = document.createElement('button');
    removeButton.type = 'button';
    removeButton.className = 'btn btn-link text-danger p-0';
    removeButton.innerHTML = '&times;';
    removeButton.setAttribute('aria-label', `Remove ${roomName}`);
    removeButton.onclick = () => removeRoom(roomId, price);

    if (isOffered === 1) {
        roomSummary.dataset.isOffered = '1';
    }
    
    // Add content to room summary
    roomSummary.innerHTML = `
        <div>
            <strong>${roomName}</strong>
            <p>Good for: ${minpax}-${maxpax} pax<br>Subtotal: PHP ${price}</p>
        </div>
    `;
    roomSummary.appendChild(removeButton);
    bookedRoomsContainer.appendChild(roomSummary);

    updateRemoveButtons();

    if (rateType === '2') {
        if (offeredRoomAdded) {
            updateTotal(price); 
        } else {
            offeredRoomAdded = true; 
        }
    } else {
        updateTotal(price);
    }

    showAllRooms(); 
}

function updateRemoveButtons() {
    const bookedRoomsContainer = document.getElementById('booked-rooms');
    const allRooms = Array.from(bookedRoomsContainer.children);
    const offeredRooms = allRooms.filter(room => room.dataset.isOffered === '1');

    console.log("Total rooms:", allRooms.length);
    console.log("Total is_offered rooms:", offeredRooms.length);

    if (rateType === '2') {
        allRooms.forEach(room => {
            const removeButton = room.querySelector('button');

            // Check if the removeButton exists
            if (!removeButton) {
                console.warn(`No remove button found for room: ${room.id}`);
                return;
            }

            const isOffered = room.dataset.isOffered === '1';

            if (offeredRooms.length === 1 && isOffered) {
                removeButton.onclick = () => alert("You need at least one of the offered room for overnight stays.");
            } else {
                removeButton.onclick = () => removeRoom(room.id.replace('room-', ''), parseFloat(room.querySelector('p').textContent.match(/PHP (\d+)/)?.[1] || 0));
            }
        });
    }
}

// Function to remove room from the summary
function removeRoom(roomId, price) {
    const roomElement = document.getElementById(`room-${roomId}`);
    const bookedRoomsContainer = document.getElementById('booked-rooms');
    const noRoomsMessage = document.getElementById('no-rooms-message');

    if (roomElement) {
        roomElement.remove();    
        updateTotal(-price);
    }

    if (bookedRoomsContainer.childElementCount === 1) {
        noRoomsMessage.style.display = 'block';
    }

    if (bookedRoomsContainer.childElementCount === 1 && rateType === '2') {
        //showOfferedRoomsOnly();
        offeredRoomAdded = false;
    }
}

// Function to update total
function updateTotal(priceChange) {
    const roomTotalElement = document.getElementById("room-total");
    const grandTotalElement = document.getElementById("grand-total");
    const rateElement = document.getElementById("rate");

    // Parse the current Room total and Grand total
    const currentRoomTotal = parseInt(roomTotalElement.textContent.replace(/PHP /, "").replace(/,/g, "")) || 0;
    const currentGrandTotal = parseInt(grandTotalElement.textContent.replace(/PHP /, "").replace(/,/g, "")) || 0;

    // Parse rate to PHP
    const rate = parseInt(rateElement.textContent.replace(/PHP /, "").replace(/,/g, "")) || 0;

    // Update Room total
    const newRoomTotal = currentRoomTotal + priceChange;
    roomTotalElement.textContent = `PHP ${newRoomTotal.toLocaleString()}`;

    // Calculate total
    const newGrandTotal = rate + newRoomTotal;
    grandTotalElement.textContent = `PHP ${newGrandTotal.toLocaleString()}`;

    document.getElementById("grandTotal").value = newGrandTotal;
    document.getElementById("roomTotal").value = newRoomTotal;
}

</script>
</body>
</html>
