<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $issue_id = $_GET['id'];

    $query = "SELECT i.id, i.title, i.description, i.type, i.priority, i.status, 
                     i.created_at, i.updated_at, u.firstname, u.lastname, a.firstname AS assigned_firstname, 
                     a.lastname AS assigned_lastname
              FROM issues i
              JOIN users u ON i.created_by = u.id
              LEFT JOIN users a ON i.assigned_to = a.id
              WHERE i.id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('i', $issue_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $issue = $result->fetch_assoc();
    } else {
        echo "Issue not found.";
        exit();
    }
} else {
    echo "No issue selected.";
    exit();
}

if (isset($_POST['status_update'])) {
    $new_status = $_POST['status'];

    $update_query = "UPDATE issues SET status = ?, updated_at = ? WHERE id = ?";
    $stmt = $db->prepare($update_query);
    $updated_at = date('Y-m-d H:i:s');
    $stmt->bind_param('ssi', $new_status, $updated_at, $issue_id);
    
    if ($stmt->execute()) {
        header("Location: view_issue.php?id=$issue_id");
        exit();
    } else {
        echo "Error updating status: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Issue Details - BugMe Issue Tracker</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Issue Details</h1>
    <a href="dashboard.php">Back to Dashboard</a>
    <hr>

    <h2><?php echo htmlspecialchars($issue['title']); ?></h2>
    <p><strong>Type:</strong> <?php echo htmlspecialchars($issue['type']); ?></p>
    <p><strong>Priority:</strong> <?php echo htmlspecialchars($issue['priority']); ?></p>
    <p><strong>Status:</strong> <?php echo htmlspecialchars($issue['status']); ?></p>
    <p><strong>Description:</strong></p>
    <p><?php echo nl2br(htmlspecialchars($issue['description'])); ?></p>
    <p><strong>Created By:</strong> <?php echo htmlspecialchars($issue['firstname']) . ' ' . htmlspecialchars($issue['lastname']); ?></p>
    <p><strong>Assigned To:</strong> <?php echo $issue['assigned_firstname'] . ' ' . $issue['assigned_lastname']; ?></p>
    <p><strong>Created At:</strong> <?php echo $issue['created_at']; ?></p>
    <p><strong>Last Updated:</strong> <?php echo $issue['updated_at']; ?></p>

    <h3>Update Status</h3>
    <form action="view_issue.php?id=<?php echo $issue['id']; ?>" method="POST">
        <label for="status">Change Status:</label>
        <select name="status" id="status" required>
            <option value="In Progress" <?php if ($issue['status'] == 'In Progress') echo 'selected'; ?>>In Progress</option>
            <option value="Closed" <?php if ($issue['status'] == 'Closed') echo 'selected'; ?>>Closed</option>
        </select>
        <button type="submit" name="status_update">Update Status</button>
    </form>

</body>
</html>
