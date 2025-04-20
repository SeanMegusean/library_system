<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit;
}

$student_number = $_SESSION['user_id'];
$book_id = $_POST['book_id'];


// Check if student already borrowed this book and hasn't returned it
$check = $conn->prepare("SELECT borrow_ref FROM borrowings WHERE student_number = ? AND book_id = ? AND status = 'Pending'");
$check->bind_param("ii", $student_number, $book_id);
$check->execute();
$check_result = $check->get_result();

if ($check_result->num_rows > 0) {
    // Student already borrowed this book and has not returned it
    $existing = $check_result->fetch_assoc();
    $ref = $existing['borrow_ref'];
    header("Location: student_dashboard.php?error=You+already+borrowed+this+book.+Reference:+$ref");
    exit;
}

// Check book quantity
$qty_check = $conn->prepare("SELECT quantity FROM books WHERE id = ?");
$qty_check->bind_param("i", $book_id);
$qty_check->execute();
$qty_result = $qty_check->get_result();
$book = $qty_result->fetch_assoc();

if ($book['quantity'] <= 0) {
    // If the book quantity is zero or less, prevent borrowing
    header("Location: student_dashboard.php?error=Book+is+out+of+stock");
    exit;
}

// Generate a unique reference for the borrow
$borrow_ref = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
$borrow_date = date('Y-m-d H:i:s');

// Insert borrow request
$stmt = $conn->prepare("
  INSERT INTO borrowings
    (student_number, book_id, borrow_ref, borrow_date, status)
  VALUES (?, ?, ?, ?, 'Pending')
");
$stmt->bind_param("siss", $_SESSION['student_number'], $book_id, $borrow_ref, $borrow_date);


if ($stmt->execute()) {
    // After borrowing, reduce the book quantity
    $update_qty = $conn->prepare("UPDATE books SET quantity = quantity - 1 WHERE id = ?");
    $update_qty->bind_param("i", $book_id);
    $update_qty->execute();

    header("Location: student_dashboard.php?message=Book+borrowed+successfully.+Reference:+$borrow_ref");
} else {
    header("Location: student_dashboard.php?error=Failed+to+borrow+book");
}
?>
