<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BugMe Issue Tracker</title>
    <link rel="stylesheet" href="styles.css"> <!-- Optional CSS file -->
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <form action="authenticate.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Login</button>
        </form>
        <?php
        if (isset($_GET['error'])) {
            echo "<p style='color: red;'>Invalid email or password</p>";
        }
        ?>
    </div>
</body>
</html>
