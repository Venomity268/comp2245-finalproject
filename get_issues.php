<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

try {
    if ($filter === 'all') {
        $stmt = $pdo->query("SELECT Issues.id, Issues.title, Issues.type, Issues.status, Users.firstname, Users.lastname, Issues.created_at
                             FROM Issues
                             JOIN Users ON Issues.assigned_to = Users.id");
    } elseif ($filter === 'open') {
        $stmt = $pdo->query("SELECT Issues.id, Issues.title, Issues.type, Issues.status, Users.firstname, Users.lastname, Issues.created_at
                             FROM Issues
                             JOIN Users ON Issues.assigned_to = Users.id
                             WHERE Issues.status = 'Open'");
    } elseif ($filter === 'my') {
        $userId = $_SESSION['user_id'];
        $stmt = $pdo->prepare("SELECT Issues.id, Issues.title, Issues.type, Issues.status, Users.firstname, Users.lastname, Issues.created_at
                               FROM Issues
                               JOIN Users ON Issues.assigned_to = Users.id
                               WHERE Issues.assigned_to = ?");
        $stmt->execute([$userId]);
    } else {
        echo json_encode([]);
        exit();
    }

    $issues = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($issues);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
