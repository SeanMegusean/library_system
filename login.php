<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Library Login</h2>
    <form action="validate_login.php" method="POST">
        <input type="text" name="student_number" placeholder="Student Number" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <button type="submit">Login</button>
    </form>
    <?php if (isset($_GET['error'])) echo "<p style='color:red'>" . htmlspecialchars($_GET['error']) . "</p>"; ?>
</body>
</html>