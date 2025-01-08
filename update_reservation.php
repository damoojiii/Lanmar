<?php 
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the array of selected room IDs
        $selectedRooms = $_POST['rooms']; // This will be an array of room IDs
    
        // Now you can process the selected rooms, e.g., save to the database, etc.
        print_r($selectedRooms); // For debugging purposes
    }

?>