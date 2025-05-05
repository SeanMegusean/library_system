<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
include('db_connection.php');

$today = date('Y-m-d');
$filterDate = $_GET['date'] ?? null;

$query = "
    SELECT b.campus, b.title, b.category, u.student_number, br.borrow_ref, br.borrow_date, br.status
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
<!DOCTYPE html>
<html>
<head>
    <title>Borrowing History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #0d6efd;
            color: white;
        }
        .table thead th {
            background-color: #007bff;
            color: white;
        }
        .form-label {
            margin-right: 10px;
        }
    </style>
</head>
<body>
<div class="container py-5">
    <h1 class="mb-4 bg-primary text-white p-3 rounded">📅 Borrowing History</h1>

    <div class="bg-white p-4 rounded shadow text-dark">

        <!-- Filter Form -->
        <div class="row mb-4">
            <div class="col-md-6">
                <form method="get" class="d-flex align-items-center gap-2">
                    <label for="date" class="form-label mb-0">Filter by Date:</label>
                    <input type="date" id="date" name="date" class="form-control" value="<?= htmlspecialchars($filterDate); ?>">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="admin_history.php" class="btn btn-outline-secondary">Clear</a>
                </form>
            </div>
            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                <form method="post" action="export_csv.php" target="_blank" class="d-inline-block">
                    <input type="date" name="export_date" class="form-control d-inline-block w-auto" value="<?= htmlspecialchars($filterDate ?? $today); ?>">
                    <button type="submit" name="export_csv" class="btn btn-success">📁 Export CSV</button>
                </form>
            </div>
        </div>

        <!-- Summary -->
        <p><strong>📊 Today's Borrowing Summary:</strong> <?= $total_count ?> borrowed — <?= $pending_count ?> pending, <?= $returned_count ?> returned</p>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Book Title</th>
                        <th>Campus</th>
                        <th>Category</th>
                        <th>Student Number</th>
                        <th>Reference Number</th>
                        <th>Date Borrowed</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td><?= htmlspecialchars($row['campus']) ?></td>
                        <td><?= htmlspecialchars($row['category']) ?></td>
                        <td><?= htmlspecialchars($row['student_number']) ?></td>
                        <td><?= htmlspecialchars($row['borrow_ref']) ?></td>
                        <td><?= htmlspecialchars($row['borrow_date']) ?></td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <a href="admin_dashboard.php" class="btn btn-outline-primary mt-3">← Back to Dashboard</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
