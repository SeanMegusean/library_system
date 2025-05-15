<?php
session_start();
include('db_connection.php');

$student_number = $_POST['student_number'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM users WHERE student_number = ?");
$stmt->bind_param("s", $student_number);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['student_number'] = $user['student_number'];
        $_SESSION['role'] = $user['role'];

         if ($user['role'] === 'admin') {
                $_SESSION['is_admin'] = true;
            } else {
                $_SESSION['is_admin'] = false;
            }

        if ($user['role'] === 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: student_dashboard.php");
        }
        exit;
    } else {
        header("Location: login.php?error=Incorrect password");
        exit;
    }
} else {
    header("Location: login.php?error=Invalid student number or password");
    exit;
}
?>