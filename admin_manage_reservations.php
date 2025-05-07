<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
// Fetch all meeting room requests
$sql = "SELECT * FROM mr_reservations WHERE status = 'Pending'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Requests</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    body {
        background-color: #0d6efd; /* Bootstrap blue or your own shade */
        color: white;
    }
</style>
<body>
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Manage Meeting Room Reservation</h3>
            </div>
            <div class="card-body">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Reservation ID</th>
                            <th>Room ID</th>
                            <th>Student Number</th>
                            <th>Date reserved</th>
                            <th>Time reserved</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) : ?>
                            <tr>
                                <td><?php echo $row['res_id']; ?></td>
                                <td><?php echo $row['room_id']; ?></td>
                                <td><?php echo $row['student_number']; ?></td>
                                <td><?php echo $row['date_reserved']; ?></td>
                                <td><?php echo $row['start_time'] . ' - ' . $row['end_time']; ?></td>
                                <td><?php echo htmlspecialchars($row['reason']); ?></td>
                                <td><?php echo $row['status']; ?></td>
                                <td>
                                    <form action="process_reserve.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="res_id" value="<?= $row['res_id'] ?>">
                                        <button type="submit" name="action" value="approve" class="btn btn-success btn-sm">Accept</button>
                                        <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm">Reject</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <p><a href="admin_dashboard.php" class="btn btn-primary btn-sm">← Back to Admin Dashboard</a></p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>