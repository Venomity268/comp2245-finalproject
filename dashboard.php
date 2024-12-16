<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - BugMe Issue Tracker</title>
    <link rel="stylesheet" href="styles.css">
    <script src="script.js" defer></script>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($user_name); ?></h1>
    <a href="logout.php">Logout</a>
    <hr>

    <div class="filters">
        <button id="all-tickets">All Tickets</button>
        <button id="open-tickets">Open Tickets</button>
        <button id="my-tickets">My Tickets</button>
    </div>

    <div id="issues-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody id="issues-table-body">
            </tbody>
        </table>
    </div>
</body>
</html>
