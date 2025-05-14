<?php
session_start();
include('db_connection.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $borrow_id = intval($_POST['borrow_id']);
    $book_id   = intval($_POST['book_id']);

    // 1. Check if the borrowing exists and is currently Borrowed
    $check = $conn->prepare("SELECT status FROM borrowings WHERE id = ?");
    $check->bind_param("i", $borrow_id);
    $check->execute();
    $result = $check->get_result();

    if ($row = $result->fetch_assoc()) {
        if ($row['status'] === 'Borrowed') {
            // 2. Set the return date and update status to Returned
            $return_date = date('Y-m-d H:i:s'); // Current date and time
            $update = $conn->prepare("UPDATE borrowings 
                                      SET status = 'Returned', return_date = ? 
                                      WHERE id = ?");
            $update->bind_param("si", $return_date, $borrow_id);
            $update->execute();

            // 3. Increase book stock
            $updateStock = $conn->prepare("UPDATE books SET quantity = quantity + 1 WHERE id = ?");
            $updateStock->bind_param("i", $book_id);
            $updateStock->execute();

            header("Location: admin_dashboard.php?message=Book marked as returned.");
            exit;
        } else {
            header("Location: admin_dashboard.php?message=Book is not currently borrowed.");
            exit;
        }
    } else {
        header("Location: admin_dashboard.php?message=Borrowing record not found.");
        exit;
    }
} else {
    header("Location: admin_dashboard.php");
    exit;
}
