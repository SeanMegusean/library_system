<?php
session_start();
include('db_connection.php');
include('recommendations.php');

// Check if student is logged in
if (!isset($_SESSION['student_number'])) {
    header("Location: login.php?error=Please log in first");
    exit;
}

$student_number = $_SESSION['student_number'];

// Fetch full name from database
$fullNameStmt = $conn->prepare("SELECT full_name FROM users WHERE student_number = ?");
$fullNameStmt->bind_param("s", $student_number);
$fullNameStmt->execute();
$fullNameResult = $fullNameStmt->get_result();
$student = $fullNameResult->fetch_assoc();
$full_name = $student['full_name'] ?? $student_number;
$fullNameStmt->close();


// Get the last borrowed book for this student
$stmt = $conn->prepare("
  SELECT book_id
  FROM borrowings
  WHERE student_number = ?
  ORDER BY borrow_date DESC
  LIMIT 1
");
$stmt->bind_param("s", $_SESSION['student_number']);
$stmt->execute();
$last = $stmt->get_result()->fetch_assoc();

$recommendations = [];
if ($last) {
    // If there's a last borrowed book, get recommendations based on that book
    $current_book_id = (int)$last['book_id'];
    $recommendations = get_recommendations($conn, $student_number, $current_book_id, 3);
}

$search = '';
if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $stmt = $conn->prepare("SELECT * FROM books WHERE title LIKE ? OR author LIKE ?");
    $searchTerm = "%$search%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
} else {
    $stmt = $conn->prepare("SELECT * FROM books");
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<style>
body {
    background: linear-gradient(90deg, #5A9FFF 0%, #407BFF 100%);
}
</style>
<body>
    <div class="container p-4 rounded shadow-lg" style="background: linear-gradient(90deg, #FFFFFF 0%, #E4E1E1 100%); box-shadow: inset 1px 1px 2px 0 rgba(0, 0, 0, 0.2), inset -1px -1px 2px 0 rgba(255, 255, 255, 0.8);">
        <h1 class="text-center font-weight-bold text-primary">Welcome, <?php echo htmlspecialchars($full_name); ?>!</h1>
        <p class="text-end"><a href="logout.php" class="btn btn-outline-danger border-2" style=" font-weight: 600;">Log Out</a></p>

        <?php if (isset($_GET['message'])) : ?>
            <div class="message success"><?php echo $_GET['message']; ?></div>
        <?php endif; ?>
        
        <?php
        if (isset($_SESSION['ebook_message'])) {
            echo $_SESSION['ebook_message'];
            unset($_SESSION['ebook_message']);
        }
        ?>

        <?php if (isset($_GET['error'])) : ?>
            <div class="message error"><?php echo $_GET['error']; ?></div>
        <?php endif; ?>

        <form method="POST" action="student_dashboard.php">
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group mb-3">
                        <input class="form-control rounded-start" type="text" name="search" placeholder="Search by title or author" value="<?php echo $search; ?>">
                        <button class="btn btn-outline-primary rounded-end border-2" style="font-weight: 600;" type="submit">🔍 Search</button>
                    </div>
                </div>
            </div>
        </form>

        <p><a href="student_RRR.php" class="btn btn-outline-primary border-2" style="font-weight: 600;">Request/Reserve a Meeting Room?</a></p>
        <h2>Available Books</h2>
        <table class="table table-striped table-bordered table-hover">
            <thead class="text-primary text-center">
                <tr>
                    <th>Campus</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Year</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($book = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $book['campus']; ?></td>
                        <td><?php echo $book['title']; ?></td>
                        <td><?php echo $book['author']; ?></td>
                        <td><?php echo $book['year']; ?></td>
                        <td><?php echo htmlspecialchars($book['category']); ?></td>
                        <td><?php echo $book['quantity']; ?></td>
                        <td class="text-center">
                            <?php
                                $book_id = $book['id'];

                                $checkPending = $conn->prepare("SELECT 1 FROM borrowings_temp WHERE student_number = ? AND book_id = ?");
                                $checkPending->bind_param("si", $student_number, $book_id);
                                $checkPending->execute();
                                $checkPending->store_result();
                                $isPending = $checkPending->num_rows > 0;
                                $checkPending->close();
                            ?>

                            <?php if ($isPending): ?>
                                <button class="borrow-btn btn btn-warning" disabled>Pending for Approval</button>
                            <?php elseif ($book['quantity'] > 0): ?>
                                <form method="POST" action="borrow.php" onsubmit="return confirmBorrow()">
                                    <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
                                    <button type="submit" class="borrow-btn btn btn-outline-success border-2" style="font-weight: 600;">Borrow</button>
                                </form>
                            <?php else: ?>
                                <button class="borrow-btn btn btn-secondary" disabled>Out of Stock</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <?php if (!empty($recommendations)): ?>
    <div class="container mt-4 p-4 rounded shadow-lg" style="background: linear-gradient(90deg, #FFFFFF 0%, #E4E1E1 100%); box-shadow: inset 1px 1px 2px 0 rgba(0, 0, 0, 0.2), inset -1px -1px 2px 0 rgba(255, 255, 255, 0.8);">
        <h3 class="text-primary">📚 Recommended for you:</h3>
        <ul class="list-group">
        <?php foreach ($recommendations as $rec): ?>
            <?php
                $bookId = $rec['book_id'];
                $qtyStmt = $conn->prepare("SELECT quantity FROM books WHERE id = ?");
                $qtyStmt->bind_param("i", $bookId);
                $qtyStmt->execute();
                $qtyResult = $qtyStmt->get_result()->fetch_assoc();
                $quantity = $qtyResult['quantity'] ?? 0;
                $qtyStmt->close();
            ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <?php echo htmlspecialchars($rec['title']); ?> &mdash;
                <?php if ($quantity > 0): ?>
                    <form method="POST" action="borrow.php" style="display:inline;" onsubmit="return confirm('Are you sure you want to borrow this book?');">
                        <input type="hidden" name="book_id" value="<?php echo $bookId; ?>">
                        <button type="submit" class="borrow-btn-link btn btn-outline-primary btn-sm">Borrow this</button>
                    </form>
                <?php else: ?>
                    <span style="color: red;">Out of stock</span>
                <?php endif; ?>
            </li> 
        <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <div class="container mt-4 p-4 rounded shadow-lg" style="background: linear-gradient(90deg, #FFFFFF 0%, #E4E1E1 100%);">
        <h2 class="text-primary">📘 E-Books Available</h2>
        <table class="table table-bordered">
            <thead><tr><th>Title</th><th>Author</th><th>Category</th><th>Action</th></tr></thead>
            <tbody>
            <?php
            $ebookQuery = $conn->query("SELECT * FROM ebooks WHERE available = 1");
            while ($ebook = $ebookQuery->fetch_assoc()):
            ?>
                <tr>
                    <td><?= htmlspecialchars($ebook['title']) ?></td>
                    <td><?= htmlspecialchars($ebook['author']) ?></td>
                    <td><?= htmlspecialchars($ebook['category']) ?></td>
                    <td>
                        <form method="POST" action="borrow_ebook.php">
                            <input type="hidden" name="ebook_id" value="<?= $ebook['id'] ?>">
                            <button type="submit" class="btn btn-outline-primary btn-sm">Borrow</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
