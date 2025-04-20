<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
include('db_connection.php');

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
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #444;
            text-align: left;
        }
        h1 {
            margin-bottom: 10px;
        }
        a.button {
            display: inline-block;
            margin: 10px 0;
            padding: 8px 12px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
    </style>
</head>

    <!--Show succes message-->
        <?php if (isset($_GET['message'])) : ?>
            <p style="color: green;"><?php echo $_GET['message']; ?></p>
        <?php endif; ?>

<body>
    <h1>Welcome, Admin!</h1>
    <a class="button" href="admin_books.php">📚 Manage Books</a>
    <a class="button" href="logout.php">Logout</a>

    <form method="GET" action="admin_dashboard.php" style="margin-bottom:10px;">
        <input 
            type="text" 
            name="search" 
            placeholder="Search title, student no., ref…" 
            value="<?php echo htmlspecialchars($search); ?>"
        >
        <button type="submit">🔍 Search</button>
        <!-- preserve your current sort in the form -->
        <input type="hidden" name="sort_by"    value="<?php echo htmlspecialchars($sort_by); ?>">
        <input type="hidden" name="sort_order" value="<?php echo htmlspecialchars($sort_order); ?>">
    </form>

    <a class="button" href="admin_history.php">📅 Borrowing History</a>

    <h2>Borrowed Books</h2>
    <table>
        <tr>
            <th>Book Title</th>
            <th>Category</th>
            <th>
                <a href="?<?php 
                    // preserve search + toggle order
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
                <a href="?<?php 
                    // preserve search + toggle order
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
                <a href="?<?php     
                    // preserve search + toggle order
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
        <?php while ($row = $result->fetch_assoc()) : ?>
        <tr>
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
                    $status = $row['status']; // 'Pending' or 'Returned'

                    echo $borrowDate;

                    if ($interval == 0) {
                        echo " <span style='color:green;'>(Today)</span>";
                    } elseif ($interval == 1) {
                        if ($status === 'Pending') {
                            echo " <span style='color:orange;'>(Yesterday)</span>";
                        } else {
                            echo " <span style='color:green;'>(Returned Yesterday)</span>";
                        }
                    } else {
                        if ($status === 'Pending') {
                            echo " <span style='color:red;'>($interval days ago)</span>";
                        } else {
                            echo " <span style='color:green;'>(Returned $interval days ago)</span>";
                        }
                    }
                ?>
            </td>
            <td><?php echo htmlspecialchars($row['status']); ?>
            <?php if ($row['status'] === 'Pending') : ?>
                <form method="post" action="mark_returned.php" style="display:inline;">
                    <input type="hidden" name="borrow_id" value="<?php echo $row['borrow_id']; ?>">
                    <input type="hidden" name="book_id" value="<?php echo $row['book_id']; ?>">
                    <button type="submit">Mark as Returned</button>
                </form>
            <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
