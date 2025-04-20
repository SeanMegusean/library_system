<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
include('db_connection.php');

$id = $_GET['id'];
$stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: admin_books.php");
exit;
?>