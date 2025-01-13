<?php
session_start();
include("connection.php");
try {
    if (isset($_GET['query'])) {
        $query = $_GET['query'];
        $stmt = $pdo->prepare("SELECT user_id, firstname, lastname FROM users WHERE role = 'user' AND (firstname LIKE :query OR lastname LIKE :query)");
        $stmt->execute([':query' => "%$query%"]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($results);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
