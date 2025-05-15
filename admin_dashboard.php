<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
include('db_connection.php');

$admin_id = $_SESSION['user_id'];

$admin_query = $conn->prepare("SELECT full_name FROM users WHERE id = ? AND role = 'admin'");
$admin_query->bind_param("i", $admin_id);
$admin_query->execute();
$admin_result = $admin_query->get_result();
$admin_name = 'Admin';

if ($admin_row = $admin_result->fetch_assoc()) {
    $admin_name = $admin_row['full_name'];
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$allowed_sorts = ['borrow_date', 'student_number', 'title', 'status'];
$sort_by = in_array(@$_GET['sort_by'], $allowed_sorts) ? $_GET['sort_by'] : 'borrow_date';
$sort_order = (isset($_GET['sort_order']) && strtoupper($_GET['sort_order']) === 'ASC') ? 'ASC' : 'DESC';
$order_clause = "ORDER BY {$sort_by} {$sort_order}";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    body {
        background-color: #0d6efd;
        color: white;
    }
</style>
<body>

<div class="container my-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <h1 class="mb-4 text-primary">Welcome, Admin <?php echo htmlspecialchars($admin_name); ?>!</h1>

            <?php if (isset($_GET['message'])) : ?>
                <p style="color: green;"><?php echo $_GET['message']; ?></p>
            <?php endif; ?>

            <div class="mb-3 d-flex flex-wrap gap-2">
                <a class="btn btn-primary" href="admin_books.php">📚 Manage Books</a>
                <a class="btn btn-info" href="admin_mr_status.php">Meeting Room Status</a>
                <a class="btn btn-secondary" href="admin_manage_requests.php">Manage Room Requests</a>
                <a class="btn btn-secondary" href="admin_manage_reservations.php">Manage Room Reservations</a>
                <a class="btn btn-info" href="mr_logs.php">Meeting Room Logs</a>
                <a class="btn btn-danger" href="logout.php">Logout</a>
            <a href="admin_ebooks.php" class="btn btn-outline-secondary">Manage E-Books</a>
            </div>

            <form method="GET" action="admin_dashboard.php" class="row g-2 mb-3">
                <div class="col-md-4">
                    <input 
                        type="text" 
                        name="search" 
                        class="form-control" 
                        placeholder="Search title, student no., ref…" 
                        value="<?php echo htmlspecialchars($search); ?>"
                    >
                </div>
                <input type="hidden" name="sort_by" value="<?php echo htmlspecialchars($sort_by); ?>">
                <input type="hidden" name="sort_order" value="<?php echo htmlspecialchars($sort_order); ?>">
                <div class="col-auto">
                    <button type="submit" class="btn btn-outline-primary">🔍 Search</button>
                </div>
            </form>

            <a class="btn btn-outline-dark mb-3" href="admin_history.php">📅 Borrowing History</a>

            <!-- Section 1: Pending Borrow Requests -->
            <h2 class="mb-3">Pending Borrow Requests</h2>
            <div class="table-responsive mb-5">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Campus</th>
                            <th>Book Title</th>
                            <th>Category</th>
                            <th>Student Number</th>
                            <th>Reference Number</th>
                            <th>Borrow Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql1 = "
                            SELECT 
                                b.campus, br.id AS borrow_id, b.id AS book_id, b.title, b.category,
                                u.student_number, br.borrow_ref, br.borrow_date, br.status
                            FROM borrowings_temp br
                            JOIN books b ON br.book_id = b.id
                            JOIN users u ON br.student_number = u.student_number
                            WHERE br.status = 'Pending'
                            $order_clause
                        ";
                        $stmt1 = $conn->prepare($sql1);
                        $stmt1->execute();
                        $res1 = $stmt1->get_result();
                        while ($row = $res1->fetch_assoc()) :
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($row['campus']) ?></td>
                            <td><?= htmlspecialchars($row['title']) ?></td>
                            <td><?= htmlspecialchars($row['category']) ?></td>
                            <td><?= htmlspecialchars($row['student_number']) ?></td>
                            <td><?= htmlspecialchars($row['borrow_ref']) ?></td>
                            <td><?= (new DateTime($row['borrow_date']))->format('Y-m-d') ?></td>
                            <td>
                                <?= htmlspecialchars($row['status']) ?>
                                <form method="post" action="approve_borrow.php" class="d-inline">
                                    <input type="hidden" name="borrow_id" value="<?= $row['borrow_id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-success">Approve</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Section 2: Currently Borrowed Books -->
            <h2 class="mb-3">Currently Borrowed Books</h2>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Campus</th>
                            <th>Book Title</th>
                            <th>Category</th>
                            <th>Student Number</th>
                            <th>Reference Number</th>
                            <th>Borrow Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql2 = "
                            SELECT 
                                b.campus, br.id AS borrow_id, b.id AS book_id, b.title, b.category,
                                u.student_number, br.borrow_ref, br.borrow_date, br.status
                            FROM borrowings br
                            JOIN books b ON br.book_id = b.id
                            JOIN users u ON br.student_number = u.student_number
                            WHERE br.status = 'Borrowed'
                            $order_clause
                        ";
                        $stmt2 = $conn->prepare($sql2);
                        $stmt2->execute();
                        $res2 = $stmt2->get_result();
                        while ($row = $res2->fetch_assoc()) :
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($row['campus']) ?></td>
                            <td><?= htmlspecialchars($row['title']) ?></td>
                            <td><?= htmlspecialchars($row['category']) ?></td>
                            <td><?= htmlspecialchars($row['student_number']) ?></td>
                            <td><?= htmlspecialchars($row['borrow_ref']) ?></td>
                            <td><?= (new DateTime($row['borrow_date']))->format('Y-m-d') ?></td>
                            <td>
                                <?= htmlspecialchars($row['status']) ?>
                                <form method="post" action="mark_returned.php" class="d-inline">
                                    <input type="hidden" name="borrow_id" value="<?= $row['borrow_id'] ?>">
                                    <input type="hidden" name="book_id" value="<?= $row['book_id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-primary">Mark as Returned</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
