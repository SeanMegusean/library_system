<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$borrow_id = $_POST['borrow_id'];
$book_id = $_POST['book_id'];

// Mark as returned
$update = $conn->prepare("UPDATE borrowings SET status = 'Returned' WHERE id = ?");
$update->bind_param("i", $borrow_id);
$update->execute();

// Increase book quantity
$inc = $conn->prepare("UPDATE books SET quantity = quantity + 1 WHERE id = ?");
$inc->bind_param("i", $book_id);
$inc->execute();

header("Location: admin_dashboard.php?message=Book marked as returned");
exit;
?>
