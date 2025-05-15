<?php
session_start();
include('db_connection.php');

$ebook_id = $_POST['ebook_id'];
$student_number = $_SESSION['student_number'];

$borrowed_at = (new DateTime())->format('Y-m-d H:i:s');
$expires_at = (new DateTime('+1 hour'))->format('Y-m-d H:i:s');

$stmt = $conn->prepare("
    INSERT INTO ebook_borrowings (ebook_id, student_number, borrowed_at, expires_at)
    VALUES (?, ?, ?, ?)
");
$stmt->bind_param("ssss", $ebook_id, $student_number, $borrowed_at, $expires_at);
$stmt->execute();

// Fetch the ebook link
$ebook = $conn->query("SELECT file_link FROM ebooks WHERE id = $ebook_id")->fetch_assoc();

$token_data = [
    'ebook_id' => $ebook_id,
    'student_number' => $student_number,
    'expires' => $expires_at
];
$token = base64_encode(json_encode($token_data));
$token_url = "access_ebook.php?token=" . urlencode($token);

// Redirect with link message
$_SESSION['ebook_message'] = "Access your e-book here: <a href='$token_url'>Read E-Book</a>";
header("Location: student_dashboard.php");
exit;
