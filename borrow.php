<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit;
}

$student_number = $_SESSION['student_number'];
$book_id = $_POST['book_id'] ?? null;

if (!$book_id) {
    header("Location: student_dashboard.php?error=Invalid+book+ID");
    exit;
}

// Check if student already has a pending confirmed borrow
$check = $conn->prepare("SELECT borrow_ref FROM borrowings WHERE student_number = ? AND book_id = ? AND status = 'Pending'");
$check->bind_param("si", $student_number, $book_id);
$check->execute();
$check_result = $check->get_result();

if ($check_result->num_rows > 0) {
    $existing = $check_result->fetch_assoc();
    $ref = $existing['borrow_ref'];
    header("Location: student_dashboard.php?error=You+already+borrowed+this+book.+Reference:+$ref");
    exit;
}

// Check if student already has a pending request in borrowings_temp
$pendingCheck = $conn->prepare("SELECT * FROM borrowings_temp WHERE student_number = ? AND book_id = ?");
$pendingCheck->bind_param("si", $student_number, $book_id);
$pendingCheck->execute();
$pendingResult = $pendingCheck->get_result();

if ($pendingResult->num_rows > 0) {
    header("Location: student_dashboard.php?error=You+already+have+a+pending+request+for+this+book");
    exit;
}

// Check book quantity
$qty_check = $conn->prepare("SELECT quantity FROM books WHERE id = ?");
$qty_check->bind_param("i", $book_id);
$qty_check->execute();
$qty_result = $qty_check->get_result();
$book = $qty_result->fetch_assoc();

if (!$book || $book['quantity'] <= 0) {
    header("Location: student_dashboard.php?error=Book+is+out+of+stock");
    exit;
}

// Generate borrow reference
$borrow_ref = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
$borrow_date = date('Y-m-d H:i:s');

// Insert into borrowings_temp instead of borrowings
$stmt = $conn->prepare("
    INSERT INTO borrowings_temp
        (student_number, book_id, borrow_ref, borrow_date, status)
    VALUES (?, ?, ?, ?, 'Pending')
");
$stmt->bind_param("siss", $student_number, $book_id, $borrow_ref, $borrow_date);

if ($stmt->execute()) {
    header("Location: student_dashboard.php?message=Your+borrow+request+is+pending+approval.+Reference:+$borrow_ref");
} else {
    header("Location: student_dashboard.php?error=Failed+to+submit+borrow+request");
}
?>
