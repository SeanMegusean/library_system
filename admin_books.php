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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <style>
        body {
            background-color: #0d6efd; /* Bootstrap blue or your own shade */
            color: white;
        }
    </style>
<div class="container my-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <h1 class="text-center mb-4">📚 Admin - Book Management</h1>

            <?php if (isset($_GET['message'])): ?>
            <div style="color: green; font-weight: bold;">
                <?php echo htmlspecialchars($_GET['message']); ?>
            </div>
        <?php endif; ?>

            <form method="GET" class="d-flex mb-3">
                <input type="text" name="search" class="form-control me-2" placeholder="Search by title..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>

            <div class="mb-3">
                <a href="add_book.php" class="btn btn-primary me-2">➕ Add New Book</a>
                <a href="admin_dashboard.php" class="btn btn-secondary me-2">⬅️ Back to Dashboard</a>
                <a href="logout.php" class="btn btn-outline-danger">Logout</a>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="text-center">
                        <tr>
                            <th>Campus</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Year</th>
                            <th>Quantity</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['campus']) ?></td>
                            <td><?= htmlspecialchars($row['title']) ?></td>
                            <td><?= htmlspecialchars($row['author']) ?></td>
                            <td><?= htmlspecialchars($row['year']) ?></td>
                            <td><?= htmlspecialchars($row['quantity']) ?></td>
                            <td class="text-center">
                                <a href="edit_book.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                                <a href="delete_book.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS (Optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>