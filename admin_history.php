<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
include('db_connection.php');

$today = date('Y-m-d');


// Optional: Filter by date
$filterDate = $_GET['date'] ?? null;

$query = "
    SELECT b.title, b.category, u.student_number, br.borrow_ref, br.borrow_date, br.status
    FROM borrowings br
    JOIN books b ON br.book_id = b.id
    JOIN users u ON br.student_number = u.student_number
";

if ($filterDate) {
    $query .= " WHERE DATE(br.borrow_date) = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $filterDate);
} else {
    $query .= " ORDER BY br.borrow_date DESC";
    $stmt = $conn->prepare($query);
}

$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Borrowing History</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #444; text-align: left; }
        h1 { margin-bottom: 10px; }
        form { margin-bottom: 10px; }
    </style>
</head>
<body>
    <h1>📅 Borrowing History</h1>
    <form method="get">
        <label for="date">Filter by Date:</label>
        <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($filterDate); ?>">
        <button type="submit">Filter</button>
        <a href="admin_history.php">Clear</a>
    </form>

    <form method="post" action="export_csv.php" target="_blank">
    <input type="date" name="export_date" value="<?php echo htmlspecialchars($filterDate ?? date('Y-m-d')); ?>">
    <button type="submit" name="export_csv">📁 Export CSV</button>
    </form>



    <?php
        // Get today's date
        $today = date('Y-m-d');

        // Count today's borrowings
        $count_stmt = $conn->prepare("
            SELECT 
                SUM(status = 'Pending') AS pending_count,
                SUM(status = 'Returned') AS returned_count
            FROM borrowings
            WHERE DATE(borrow_date) = ?
        ");
        $count_stmt->bind_param("s", $today);
        $count_stmt->execute();
        $count_result = $count_stmt->get_result()->fetch_assoc();

        $pending_count = $count_result['pending_count'] ?? 0;
        $returned_count = $count_result['returned_count'] ?? 0;
        $total_count = $pending_count + $returned_count;
        ?>
        <p><strong>📊 Today's Borrowing Summary:</strong> <?= $total_count ?> borrowed —
            <?= $pending_count ?> pending, <?= $returned_count ?> returned</p>

    <table>
        <tr>
            <th>Book Title</th>
            <th>Category</th>
            <th>Student Number</th>
            <th>Reference Number</th>
            <th>Date Borrowed</th>
            <th>Status</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) : ?>
        <tr>
            <td><?php echo htmlspecialchars($row['title']); ?></td>
            <td><?php echo htmlspecialchars($row['category']); ?></td>
            <td><?php echo htmlspecialchars($row['student_number']); ?></td>
            <td><?php echo htmlspecialchars($row['borrow_ref']); ?></td>
            <td><?php echo htmlspecialchars($row['borrow_date']); ?></td>
            <td><?php echo htmlspecialchars($row['status']); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <p><a href="admin_dashboard.php">← Back to Dashboard</a></p>
</body>
</html>
