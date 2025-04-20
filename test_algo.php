<?php
include('db_connection.php');
include('recommendations.php');

$student_number = 'student456';
$current_book_id = 1; 

$recs = get_recommendations($conn, $student_number, $current_book_id, 3);

echo "<h2>Recommended Books:</h2><ul>";
foreach ($recs as $r) {
    echo "<li>{$r['title']} (Book ID: {$r['book_id']})</li>";
}
echo "</ul>";
?>