<?php
session_start();
include('db_connection.php');
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meeting Rooms</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="card mb-4">
            <div class="card-header">
                Meeting Room Availability
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Meeting Room</th>
                            <th>Status</th>
                            <th>Set Status to:</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $sql = "SELECT * FROM meeting_rooms";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) : 
    $room_id = $row['room_id'];
?>
    <tr>
        <td><?php echo htmlspecialchars($room_id); ?></td>
        <td><?php echo htmlspecialchars($row['Status']); ?></td>
        <td>
    <div class="btn-group" role="group">
    <form action="mr_setstatus.php" method="POST" style="display: inline;">
        <input type="hidden" name="status" value="available">
        <input type="hidden" name="room_id" value="<?php echo $room_id; ?>">
        <button type="submit" class="btn btn-success btn-sm">Available</button>
    </form>
    <form action="mr_setstatus.php" method="POST" style="display: inline;">
        <input type="hidden" name="status" value="unavailable">
        <input type="hidden" name="room_id" value="<?php echo $room_id; ?>">
        <button type="submit" class="btn btn-danger btn-sm">Unavailable</button>
    </form>
    <form action="mr_setstatus.php" method="POST" style="display: inline;">
        <input type="hidden" name="status" value="in session">
        <input type="hidden" name="room_id" value="<?php echo $room_id; ?>">
        <button type="submit" class="btn btn-warning btn-sm">In Session</button>
    </form>
    </div>
</td>
    </tr>
<?php endwhile; ?>

                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4 text-center">
            <a href="admin_dashboard.php" class="btn btn-outline-primary">← Back to Dashboard</a>
        </div>
</body>
</html>