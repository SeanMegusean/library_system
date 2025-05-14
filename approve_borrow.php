<?php
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['borrow_id'])) {
    $borrow_id = intval($_POST['borrow_id']);

    // Get data from borrowings_temp
    $stmt = $conn->prepare("SELECT * FROM borrowings_temp WHERE id = ?");
    $stmt->bind_param("i", $borrow_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Insert into borrowings table (change status to 'Borrowed')
        $insert = $conn->prepare("INSERT INTO borrowings (book_id, student_number, borrow_date, borrow_ref, status)
                                  VALUES (?, ?, ?, ?, 'Borrowed')");
        
        $insert->bind_param("isss", $row['book_id'], $row['student_number'], $row['borrow_date'], $row['borrow_ref']);
        if ($insert->execute()) {
            // After inserting into borrowings table, delete the request from borrowings_temp
            $delete = $conn->prepare("DELETE FROM borrowings_temp WHERE id = ?");
            $delete->bind_param("i", $borrow_id);
            $delete->execute();

            // **Redirect Admin to admin dashboard**
            header("Location: admin_dashboard.php?message=Borrow request approved.");
            exit;
        } else {
            // Handle insert failure
            header("Location: admin_dashboard.php?message=Error occurred while approving borrow request.");
            exit;
        }
    } else {
        // Handle case if borrow request not found in borrowings_temp
        header("Location: admin_dashboard.php?message=Borrow request not found.");
        exit;
    }
} else {
    // Redirect back to the dashboard if the request is not POST or missing borrow_id
    header("Location: admin_dashboard.php");
    exit;
}
