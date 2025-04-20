<?php
include('db_connection.php');

// Only run on POST with a chosen date
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['export_date'])) {
    // Normalize the date input (YYYY-MM-DD)
    $export_date = date('Y-m-d', strtotime($_POST['export_date']));

    // Force download headers
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="borrow_report_' . $export_date . '.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');

    // Clean output buffer
    if (ob_get_length()) ob_clean();
    flush();

    // Open output stream
    $output = fopen('php://output', 'w');

    // Write CSV column headers
    fputcsv($output, ['Book Title', 'Category', 'Student Number', 'Reference Number', 'Date Borrowed', 'Status']);

    $stmt = $conn->prepare("
        SELECT b.title, b.category, u.student_number, br.borrow_ref, br.borrow_date, br.status
        FROM borrowings br
        JOIN books b ON br.book_id = b.id
        JOIN users u ON br.student_number = u.student_number
        WHERE DATE(br.borrow_date) = ?
        ORDER BY br.borrow_date ASC
    ");
    $stmt->bind_param("s", $export_date);
    $stmt->execute();
    $result = $stmt->get_result();

    // Output each row to CSV
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            $row['title'],
            $row['category'],
            $row['student_number'],
            $row['borrow_ref'],
            $row['borrow_date'],
            $row['status']
        ]);        
    }

    // Close stream and exit
    fclose($output);
    exit;
}

// If not a proper POST
echo "Invalid request.";
