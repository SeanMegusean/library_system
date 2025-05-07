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
$admin_name = 'Admin'; // fallback name

if ($admin_row = $admin_result->fetch_assoc()) {
    $admin_name = $admin_row['full_name'];
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// 2️⃣ Sorting
// Whitelist the columns you allow sorting on:
$allowed_sorts = ['borrow_date','student_number','title','status'];
$sort_by    = in_array(@$_GET['sort_by'], $allowed_sorts) ? $_GET['sort_by'] : 'borrow_date';

// Only allow ASC or DESC
$sort_order = (isset($_GET['sort_order']) && strtoupper($_GET['sort_order'])==='ASC')
              ? 'ASC' : 'DESC';

// Build ORDER BY clause
$order_clause = "ORDER BY {$sort_by} {$sort_order}";


// 3️⃣ Build the base SQL
$sql = "
  SELECT 
    b.campus,
    br.id   AS borrow_id,
    b.id    AS book_id,
    b.title,
    b.category,
    u.student_number,
    br.borrow_ref,
    br.borrow_date,
    br.status
  FROM borrowings br
  JOIN books    b ON br.book_id    = b.id
  JOIN users u ON br.student_number = u.student_number
  WHERE DATE(br.borrow_date) = CURDATE() AND br.status = 'Pending'
";


// 4️⃣ Append a WHERE if there’s a search term
if ($search !== '') {
    $sql .= " WHERE
       b.title          LIKE ? OR
       u.student_number LIKE ? OR
       br.borrow_ref    LIKE ?";
}

// 5️⃣ Append ORDER BY
$sql .= " $order_clause";

// 6️⃣ Prepare and bind
$stmt = $conn->prepare($sql);
if ($search !== '') {
    $like = "%{$search}%";
    $stmt->bind_param("sss", $like, $like, $like);
}
$stmt->execute();
$result = $stmt->get_result();
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
        background-color: #0d6efd; /* Bootstrap blue or your own shade */
        color: white;
    }
</style>
<body>

<div class="container my-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <h1 class="mb-4 text-primary">Welcome, Admin <?php echo htmlspecialchars($admin_name); ?>!</h1>

            <!--Show succes message-->
            <?php if (isset($_GET['message'])) : ?>
            <p style="color: green;"><?php echo $_GET['message']; ?></p>
        <?php endif; ?>

            <!-- Navigation Buttons -->
            <div class="mb-3 d-flex flex-wrap gap-2">
                <a class="btn btn-primary" href="admin_books.php">📚 Manage Books</a>
                <a class="btn btn-info" href="admin_mr_status.php">Meeting Room Status</a>
                <a class="btn btn-secondary" href="admin_manage_requests.php">Manage Room Requests</a>
                <a class="btn btn-secondary" href="admin_manage_reservations.php">Manage Room Reservations</a>
                <a class="btn btn-info" href="mr_logs.php">Meeting Room Logs</a>
                <a class="btn btn-danger" href="logout.php">Logout</a>
            </div>

            <!-- Search Form -->
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

            <!-- Table -->
            <h2 class="mb-3">Borrowed Books</h2>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Campus</th>
                            <th>Book Title</th>
                            <th>Category</th>
                            <th>
                                <a class="text-decoration-none text-dark" href="?<?php 
                                    echo "search=".urlencode($search)
                                        ."&sort_by=student_number"
                                        ."&sort_order=" . ($sort_by==='student_number' && $sort_order==='ASC' ? 'DESC' : 'ASC');
                                    ?>">
                                    Student Number
                                    <?php if($sort_by==='student_number'): ?>
                                        <?php echo $sort_order==='ASC' ? '↑' : '↓'; ?>
                                    <?php endif; ?>
                                </a>
                            </th>
                            <th>Reference Number</th>
                            <th>
                                <a class="text-decoration-none text-dark" href="?<?php 
                                    echo "search=".urlencode($search)
                                        ."&sort_by=borrow_date"
                                        ."&sort_order=" . ($sort_by==='borrow_date' && $sort_order==='ASC' ? 'DESC' : 'ASC');
                                ?>">
                                    Date Borrowed
                                    <?php if($sort_by==='borrow_date'): ?>
                                        <?php echo $sort_order==='ASC' ? '↑' : '↓'; ?>
                                    <?php endif; ?>
                                </a>
                            </th>
                            <th>
                                <a class="text-decoration-none text-dark" href="?<?php 
                                    echo "search=".urlencode($search)
                                        ."&sort_by=status"
                                        ."&sort_order=" . ($sort_by==='status' && $sort_order==='ASC' ? 'DESC' : 'ASC');
                                ?>">
                                    Status
                                    <?php if($sort_by==='status'): ?>
                                        <?php echo $sort_order==='ASC' ? '↑' : '↓'; ?>
                                    <?php endif; ?>
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['campus']); ?></td>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['category']); ?></td>
                            <td><?php echo htmlspecialchars($row['student_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['borrow_ref']); ?></td>
                            <td>
                                <?php 
                                    $borrowDateObj = new DateTime($row['borrow_date']);
                                    $borrowDate = $borrowDateObj->format('Y-m-d');
                                    $todayDate = (new DateTime())->format('Y-m-d');
                                    $interval = (strtotime($todayDate) - strtotime($borrowDate)) / (60 * 60 * 24);
                                    $status = $row['status'];
                                    echo $borrowDate;

                                    if ($interval == 0) {
                                        echo " <span class='badge bg-success'>Today</span>";
                                    } elseif ($interval == 1) {
                                        echo $status === 'Pending' 
                                            ? " <span class='badge bg-warning text-dark'>Yesterday</span>"
                                            : " <span class='badge bg-success'>Returned Yesterday</span>";
                                    } else {
                                        echo $status === 'Pending'
                                            ? " <span class='badge bg-danger'>{$interval} days ago</span>"
                                            : " <span class='badge bg-success'>Returned {$interval} days ago</span>";
                                    }
                                ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($row['status']); ?>
                                <?php if ($row['status'] === 'Pending') : ?>
                                    <form method="post" action="mark_returned.php" class="d-inline">
                                        <input type="hidden" name="borrow_id" value="<?php echo $row['borrow_id']; ?>">
                                        <input type="hidden" name="book_id" value="<?php echo $row['book_id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-success">Mark as Returned</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>