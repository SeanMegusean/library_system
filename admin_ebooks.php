<?php
session_start();
include('db_connection.php');

// Redirect if not admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: login.php");
    exit;
}
 
// Handle Add/Edit/Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'add') {
        $stmt = $conn->prepare("INSERT INTO ebooks (title, author, category, file_link, available) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $_POST['title'], $_POST['author'], $_POST['category'], $_POST['file_link'], $_POST['available']);
        $stmt->execute();
    }

    if ($action === 'edit') {
        $stmt = $conn->prepare("UPDATE ebooks SET title = ?, author = ?, category = ?, file_link = ?, available = ? WHERE id = ?");
        $stmt->bind_param("ssssii", $_POST['title'], $_POST['author'], $_POST['category'], $_POST['file_link'], $_POST['available'], $_POST['ebook_id']);
        $stmt->execute();
    }

    if ($action === 'delete') {
        $stmt = $conn->prepare("DELETE FROM ebooks WHERE id = ?");
        $stmt->bind_param("i", $_POST['ebook_id']);
        $stmt->execute();
    }

    header("Location: admin_ebooks.php");
    exit;
}

$result = $conn->query("SELECT * FROM ebooks ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage E-Books</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="p-4">
    <div class="container">
        <h1 class="mb-4">E-Book Management</h1>

        <h3>Add New E-Book</h3>
        <form method="POST" class="row g-3 mb-4">
            <input type="hidden" name="action" value="add">
            <div class="col-md-4"><input type="text" name="title" class="form-control" placeholder="Title" required></div>
            <div class="col-md-4"><input type="text" name="author" class="form-control" placeholder="Author" required></div>
            <div class="col-md-4"><input type="text" name="category" class="form-control" placeholder="Category"></div>
            <div class="col-md-8"><input type="text" name="file_link" class="form-control" placeholder="E-Book URL" required></div>
            <div class="col-md-2">
                <select name="available" class="form-select">
                    <option value="1">Available</option>
                    <option value="0">Unavailable</option>
                </select>
            </div>
            <div class="col-md-2"><button type="submit" class="btn btn-success">Add</button></div>
        </form>

        <h3>Current E-Books</h3>
        <table class="table table-bordered">
            <thead class="table-primary">
                <tr>
                    <th>Title</th><th>Author</th><th>Category</th><th>Link</th><th>Status</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($ebook = $result->fetch_assoc()): ?>
                <tr>
                    <form method="POST">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="ebook_id" value="<?= $ebook['id'] ?>">
                        <td><input type="text" name="title" class="form-control" value="<?= htmlspecialchars($ebook['title']) ?>"></td>
                        <td><input type="text" name="author" class="form-control" value="<?= htmlspecialchars($ebook['author']) ?>"></td>
                        <td><input type="text" name="category" class="form-control" value="<?= htmlspecialchars($ebook['category']) ?>"></td>
                        <td><input type="text" name="file_link" class="form-control" value="<?= htmlspecialchars($ebook['file_link']) ?>"></td>
                        <td>
                            <select name="available" class="form-select">
                                <option value="1" <?= $ebook['available'] ? 'selected' : '' ?>>Available</option>
                                <option value="0" <?= !$ebook['available'] ? 'selected' : '' ?>>Unavailable</option>
                            </select>
                        </td>
                        <td>
                            <button type="submit" class="btn btn-primary btn-sm">Update</button>
                    </form>
                    <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this e-book?');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="ebook_id" value="<?= $ebook['id'] ?>">
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                        </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
