<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
include('db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $campus = $_POST['campus'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];
    $quantity = $_POST['quantity'];
    $category = $_POST['category'];


    // 1) Prepare with 5 columns
    $stmt = $conn->prepare("
    INSERT INTO books (campus, title, author, year, quantity, category)
    VALUES (?, ?, ?, ?, ?, ?)
    ");

    // 2) Bind 5 variables: string, string, int, int, string
    $stmt->bind_param(
    "sssiss",          // s: title, s: author, i: year, s: quantity, s: category
    $campus,
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
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    body {
        background-color: #0d6efd; /* Bootstrap blue or your own shade */
        color: white;
    }
</style>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="mb-3 text-start">
                <a href="admin_dashboard.php" class="btn btn-secondary">⬅️ Back to Dashboard</a>
            </div>

            <div class="card shadow p-4 rounded-4">
                <h2 class="text-center mb-4">Add a New Book</h2>

                <form method="POST">
                    <div class="mb-3">
                        <label for="campus" class="form-label">School Campus</label>
                        <input type="text" class="form-control" name="campus" id="campus" placeholder="Enter campus name" required>
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" name="title" id="title" placeholder="Enter book title" required>
                    </div>

                    <div class="mb-3">
                        <label for="author" class="form-label">Author</label>
                        <input type="text" class="form-control" name="author" id="author" placeholder="Enter author's name" required>
                    </div>

                    <div class="mb-3">
                        <label for="year" class="form-label">Year</label>
                        <input type="number" class="form-control" name="year" id="year" placeholder="Enter year" required>
                    </div>

                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" name="quantity" id="quantity" placeholder="Enter quantity" required>
                    </div>

                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <input type="text" class="form-control" name="category" id="category" placeholder="Enter category" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Add Book</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS (optional for interactivity) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>