<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$borrow_id = $_POST['borrow_id'];
$book_id = $_POST['book_id'];

// Update borrow status
$stmt = $conn->prepare("UPDATE borrowings SET status = 'Returned' WHERE id = ?");
$stmt->bind_param("i", $borrow_id);
if ($stmt->execute()) {
    // Increase book quantity
    $update_qty = $conn->prepare("UPDATE books SET quantity = quantity + 1 WHERE id = ?");
    $update_qty->bind_param("i", $book_id);
    $update_qty->execute();

    header("Location: admin_dashboard.php?message=Book marked as returned");
    exit;
} else {
    header("Location: admin_dashboard.php?error=Failed to mark as returned");
    exit;
}
?>
