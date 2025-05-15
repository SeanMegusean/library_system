<?php
session_start();
include('db_connection.php');

// Fetch all meeting room requests
$sql = "SELECT * FROM comp_request WHERE status = 'Pending'";
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
                <h3 class="mb-0">Manage Computer Requests</h3>
            </div>
            <div class="card-body">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>Student Number</th>
                            <th>Date Requested</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) : ?>
                            <tr>
                                <td><?php echo $row['req_id']; ?></td>
                                <td><?php echo $row['student_number']; ?></td>
                                <td><?php echo $row['date_requested']; ?></td>
                                <td><?php echo $row['status']; ?></td>
                                <td>
                                    <form action="process_compReq.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="req_id" value="<?= $row['req_id'] ?>">
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