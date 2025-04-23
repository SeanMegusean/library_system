<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
include('db_connection.php');

$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $stmt = $conn->prepare("SELECT * FROM books WHERE title LIKE ?");
    $searchTerm = "%" . $search . "%";
    $stmt->bind_param("s", $searchTerm);
} else {
    $stmt = $conn->prepare("SELECT * FROM books");
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Manage Books</title>
</head>
<body>
    <h1>📚 Admin - Book Management</h1>

    <!-- Success Message Display -->
    <?php if (isset($_GET['message'])): ?>
        <div style="color: green; font-weight: bold;">
            <?php echo htmlspecialchars($_GET['message']); ?>
        </div>
    <?php endif; ?>

    <form method="GET">
        <input type="text" name="search" placeholder="Search by title..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Search</button>
    </form>
    <br>
    <a href="add_book.php">➕ Add New Book</a> |
    <a href="logout.php">Logout</a>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>Campus</th><th>Title</th><th>Author</th><th>Year</th><th>Quantity</th><th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['campus']) ?></td>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= htmlspecialchars($row['author']) ?></td>
            <td><?= htmlspecialchars($row['year']) ?></td>
            <td><?= htmlspecialchars($row['quantity']) ?></td>
            <td>
                <a href="edit_book.php?id=<?= $row['id'] ?>">Edit</a> |
                <a href="delete_book.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
