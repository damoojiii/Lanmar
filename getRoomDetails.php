<?php
include("connection.php");
if (isset($_GET['room_id'])) {
    $room_id = filter_input(INPUT_GET, 'room_id', FILTER_SANITIZE_NUMBER_INT);

    $stmt = $pdo->prepare("SELECT room_name, image_path, description, minpax, maxpax, price, is_offered FROM rooms WHERE room_id = ?");
    $stmt->execute([$room_id]);
    $room = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($room) {
        $stmt = $pdo->prepare("
            SELECT inclusion_tbl.inclusion_name 
            FROM inclusion_tbl
            INNER JOIN room_inclusions ON inclusion_tbl.inclusion_id = room_inclusions.inclusion_id
            WHERE room_inclusions.room_id = ?
        ");
        $stmt->execute([$room_id]);
        $inclusions = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $room['inclusions'] = $inclusions;

        echo json_encode($room);
    } else {
        echo json_encode(['error' => 'Room not found']);
    }
}
?>
