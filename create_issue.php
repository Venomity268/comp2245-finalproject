<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

$query = "SELECT id, firstname, lastname FROM users";
$result = $db->query($query);
$users = $result->fetch_all(MYSQLI_ASSOC);

if (isset($_POST['create_issue'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $type = $_POST['type'];
    $priority = $_POST['priority'];
    $assigned_to = $_POST['assigned_to'];
    $created_by = $_SESSION['user_id'];
    $created_at = date('Y-m-d H:i:s');
    $updated_at = date('Y-m-d H:i:s');

    $stmt = $db->prepare("INSERT INTO issues (title, description, type, priority, status, assigned_to, created_by, created_at, updated_at) 
                          VALUES (?, ?, ?, ?, 'Open', ?, ?, ?, ?)");
    $stmt->bind_param('ssssiiis', $title, $description, $type, $priority, $assigned_to, $created_by, $created_at, $updated_at);

    if ($stmt->execute()) {
        header('Location: dashboard.php');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Issue - BugMe Issue Tracker</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Create New Issue</h1>
    <a href="dashboard.php">Back to Dashboard</a>
    <hr>

    <form action="create_issue.php" method="POST">
        <label for="title">Title</label>
        <input type="text" id="title" name="title" required>

        <label for="description">Description</label>
        <textarea id="description" name="description" required></textarea>

        <label for="type">Type</label>
        <select id="type" name="type" required>
            <option value="Bug">Bug</option>
            <option value="Proposal">Proposal</option>
            <option value="Task">Task</option>
        </select>

        <label for="priority">Priority</label>
        <select id="priority" name="priority" required>
            <option value="Minor">Minor</option>
            <option value="Major">Major</option>
            <option value="Critical">Critical</option>
        </select>

        <label for="assigned_to">Assigned To</label>
        <select id="assigned_to" name="assigned_to" required>
            <?php foreach ($users as $user): ?>
                <option value="<?php echo $user['id']; ?>"><?php echo $user['firstname'] . ' ' . $user['lastname']; ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit" name="create_issue">Create Issue</button>
    </form>

</body>
</html>
