<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include('db_connection.php');

$sql = "SELECT * FROM comp_logs";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Computer Requests Logs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">🗂️ Computer Requests Logs</h1>
            <a href="admin_dashboard.php" class="btn btn-success">← Back to Dashboard</a>
        </div>
        <div class="table-responsive shadow-sm rounded bg-white p-3">
            <h4 class="mb-3">Request Logs</h4>
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Req. Log ID</th>
                        <th scope="col">Request ID</th>
                        <th scope="col">Action</th>
                        <th scope="col">Admin</th>
                        <th scope="col">campus</th>
                        <th scope="col">Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['log_id']) ?></td>
                                <td><?= htmlspecialchars($row['req_id']) ?></td>
                                <td><?= htmlspecialchars($row['action']) ?></td>
                                <td><?= htmlspecialchars($row['admin_id']) ?></td>
                                <td><?= htmlspecialchars($row['campus']) ?></td>
                                <td><?= htmlspecialchars($row['timestamp']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No request logs found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>