<?php
session_start();
include('db_connection.php');

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $student_number = trim($_POST['student_number']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation
    if (empty($full_name) || empty($student_number) || empty($password) || empty($confirm_password)) {
        $errors[] = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    } else {
        // Check if student_number already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE student_number = ?");
        $stmt->bind_param("s", $student_number);
        $stmt->execute();
        $stmt->store_result();

        if (!preg_match('/^\d{2}-\d{4}$/', $_POST['student_number'])) {
            die("Invalid student number format. Use XX-XXXX.");
        }
        

        if ($stmt->num_rows > 0) {
            $errors[] = "Student number already registered.";
        } else {
            // Insert new user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $role = 'student';

            $insert = $conn->prepare("INSERT INTO users (full_name, student_number, password, role) VALUES (?, ?, ?, ?)");
            $insert->bind_param("ssss", $full_name, $student_number, $hashed_password, $role);

            if ($insert->execute()) {
                header("Location: login.php?message=Registration successful! Please log in.");
                exit;
            } else {
                $errors[] = "Error registering. Please try again.";
            }
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5 col-md-6">
        <div class="card p-4 shadow-sm">
            <h2 class="mb-4">Create an Account</h2>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $err) echo "<div>$err</div>"; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label for="full_name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" name="full_name" required>
                </div>

                <div class="mb-3">
                    <label for="student_number" class="form-label">Student Number</label>
                    <input type="text" name="student_number" placeholder="e.g. 12-3456" pattern="\d{2}-\d{4}" title="Format should be XX-XXXX" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" required>
                </div>

                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" name="confirm_password" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Register</button>
                <p class="mt-3 text-center">
                    Already have an account? <a href="login.php">Login</a>
                </p>
            </form>
        </div>
    </div>
</body>
</html>
