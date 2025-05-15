<?php
session_start();
include('db_connection.php');

// Check if student is logged in
if (!isset($_SESSION['student_number'])) {
    header("Location: login.php?error=Please log in first");
    exit;
}

$student_number = $_SESSION['student_number'];
$error = "";
$success = "";

// Cancel request and corresponding log
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cancel_request'])) {
    $conn->begin_transaction();

    try {
        $delete_request = $conn->prepare("DELETE FROM comp_request WHERE student_number = ? AND status = 'Pending'");
        $delete_request->bind_param("s", $student_number);
        $delete_request->execute();
        $delete_request->close();

        $delete_log = $conn->prepare("DELETE FROM comp_logs WHERE student_number = ? AND status = 'Pending'");
        $delete_log->bind_param("s", $student_number);
        $delete_log->execute();
        $delete_log->close();

        $conn->commit();
        $success = "Request and associated log cancelled successfully.";
    } catch (Exception $e) {
        $conn->rollback();
        $error = "Failed to cancel the request: " . $e->getMessage();
    }
}

// Submit new request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['request_computer'])) {
    $campus = $_POST['campus'] ?? '';

    $allowed_campuses = ['San Bartolome', 'San Francisco', 'Batasan'];
    if (!in_array($campus, $allowed_campuses)) {
        $error = "Invalid campus selected.";
    } else {
        $check = $conn->prepare("SELECT * FROM comp_request WHERE student_number = ? AND status = 'Pending' LIMIT 1");
        $check->bind_param("s", $student_number);
        $check->execute();
        $existing = $check->get_result();

        if ($existing->num_rows > 0) {
            $error = "You already have a pending request.";
        } else {
            $date_requested = date('Y-m-d H:i:s');
            $status = "Pending";

            $conn->begin_transaction();

            try {
                $stmt = $conn->prepare("INSERT INTO comp_request (student_number, date_requested, status, campus) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $student_number, $date_requested, $status, $campus);
                $stmt->execute();
                $stmt->close();

                $log_stmt = $conn->prepare("INSERT INTO comp_logs (student_number, status, timestamp, campus) VALUES (?, ?, ?, ?)");
                $log_stmt->bind_param("ssss", $student_number, $status, $date_requested, $campus);
                $log_stmt->execute();
                $log_stmt->close();

                $conn->commit();
                $success = "Request submitted successfully.";
            } catch (Exception $e) {
                $conn->rollback();
                $error = "Error submitting request: " . $e->getMessage();
            }
        }
    }
}

// Check for pending request
$stmt = $conn->prepare("SELECT * FROM comp_request WHERE student_number = ? AND status = 'Pending' LIMIT 1");
$stmt->bind_param("s", $student_number);
$stmt->execute();
$result = $stmt->get_result();
$hasPending = $result->num_rows > 0;
$pendingRequest = $hasPending ? $result->fetch_assoc() : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Request a Computer Device</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <h2 class="mb-4 text-center text-primary">Request a Computer Device</h2>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
      <div class="alert alert-success text-center"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <div class="card">
      <div class="card-body">
        <?php if ($hasPending): ?>
          <h5 class="card-title text-success">You have a pending request</h5>
          <p><strong>Status:</strong> <?= htmlspecialchars($pendingRequest['status']) ?></p>
          <p><strong>Campus:</strong> <?= htmlspecialchars($pendingRequest['campus']) ?></p>
          <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to cancel your computer request? This action cannot be undone.');">
  <button type="submit" name="cancel_request" class="btn btn-danger">Cancel Request</button>
</form>

        <?php else: ?>
          <h5 class="card-title">You have no pending requests</h5>
          <form method="POST" class="row g-3">
            <div class="col-md-6">
              <label for="campus" class="form-label">Select Campus</label>
              <select name="campus" id="campus" class="form-select" required>
                <option value="">-- Choose Campus --</option>
                <option value="San Bartolome">San Bartolome</option>
                <option value="San Francisco">San Francisco</option>
                <option value="Batasan">Batasan</option>
              </select>
            </div>
            <div class="col-12">
              <button type="submit" name="request_computer" class="btn btn-success">Request a Computer Device</button>
            </div>
          </form>
        <?php endif; ?>
      </div>
    </div>

    <div class="mt-4 text-center">
      <a href="student_dashboard.php" class="btn btn-outline-primary">← Back to Dashboard</a>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
