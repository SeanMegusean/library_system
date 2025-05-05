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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Edit Book</h2>
                        <form method="POST">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" name="title" id="title" value="<?= htmlspecialchars($book['title']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="title" class="form-label">Campus</label>
                                <input type="text" class="form-control" name="campus" id="campus" value="<?= htmlspecialchars($book['campus']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="author" class="form-label">Author</label>
                                <input type="text" class="form-control" name="author" id="author" value="<?= htmlspecialchars($book['author']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="year" class="form-label">Year</label>
                                <input type="number" class="form-control" name="year" id="year" value="<?= $book['year'] ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" name="quantity" id="quantity" value="<?= $book['quantity'] ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Update Book</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>