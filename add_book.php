<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
include('db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];
    $quantity = $_POST['quantity'];
    $category = $_POST['category'];


    // 1) Prepare with 5 columns
    $stmt = $conn->prepare("
    INSERT INTO books (title, author, year, quantity, category)
    VALUES (?, ?, ?, ?, ?)
    ");

    // 2) Bind 5 variables: string, string, int, int, string
    $stmt->bind_param(
    "ssiss",          // s: title, s: author, i: year, s: quantity, s: category
    $title,
    $author,
    $year,
    $quantity,
    $category
    );

    // 3) Execute the query
    if ($stmt->execute()) {
        // Redirect to admin_books.php with a success message after inserting the book
        header("Location: admin_books.php?message=Book added successfully");
        exit();
    } else {
        // If the query fails, display an error message
        echo "Error: " . $stmt->error;
    }  

    // 4) Close the statement
    $stmt->close();
    
    
    header("Location: admin_books.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Book</title>
</head>
<body>
    <h2>Add a New Book</h2>
    <form method="POST">
        <input type="text" name="title" placeholder="Title" required><br><br>
        <input type="text" name="author" placeholder="Author" required><br><br>
        <input type="number" name="year" placeholder="Year" required><br><br>
        <input type="number" name="quantity" placeholder="Quantity" required><br><br>
        <input type="text" name="category" id="category" placeholder="Category" required><br><br>
        <button type="submit">Add Book</button>
    </form>
</body>
</html>