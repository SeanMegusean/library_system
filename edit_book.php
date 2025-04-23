<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
include('db_connection.php');

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$book = $stmt->get_result()->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $campus = $_POST['campus'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];
    $quantity = $_POST['quantity'];
    $category = $_POST['category'];

    $stmt = $conn->prepare("UPDATE books SET campus = ?, title = ?, author = ?, year = ?, quantity = ? WHERE id = ?");
    $stmt->bind_param("sssiii", $campus, $title, $author, $year, $quantity, $id);
    $stmt->execute();

    header("Location: admin_books.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Book</title>
</head>
<body>
    <h2>Edit Book</h2>
    <form method="POST">
        <input type="text" name="title" value="<?= htmlspecialchars($book['title']) ?>" required><br><br>
        <input type="text" name="author" value="<?= htmlspecialchars($book['author']) ?>" required><br><br>
        <input type="number" name="year" value="<?= $book['year'] ?>" required><br><br>
        <input type="number" name="quantity" value="<?= $book['quantity'] ?>" required><br><br>
        <button type="submit">Update Book</button>
    </form>
</body>
</html>
