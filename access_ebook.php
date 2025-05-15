<?php
session_start();
include('db_connection.php');

$token = json_decode(base64_decode($_GET['token']), true);
$now = new DateTime();

if (
    $token['student_number'] === $_SESSION['student_number'] &&
    $now < new DateTime($token['expires'])
) {
    $ebook = $conn->query("SELECT file_link FROM ebooks WHERE id = {$token['ebook_id']}")->fetch_assoc();
    echo "<iframe src='{$ebook['file_link']}' width='100%' height='800px'></iframe>";
} else {
    echo "Access expired or invalid.";
}
?>
