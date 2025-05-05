<?php
session_start();
include('db_connection.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $borrow_id = $_POST['borrow_id'];
    $book_id = $_POST['book_id'];
    
    // 1. Update the status of this specific borrowing
    $update = $conn->prepare("UPDATE borrowings SET status = 'Returned' WHERE id = ?");
    $update->bind_param("i", $borrow_id);
    $update->execute();

    // 2. Increase the book stock back by 1
    $updateStock = $conn->prepare("UPDATE books SET quantity = quantity + 1 WHERE id = ?");
    $updateStock->bind_param("i", $book_id);
    $updateStock->execute();

    header("Location: admin_dashboard.php?message=Book marked as returned.");
    exit;
}
?>
