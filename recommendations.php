<?php
/**
 * Returns an array of recommended books (id and title)
 * for $student_number who just borrowed $current_book_id.
 * Only books in the same category are considered, ordered by how often
 * others borrowed them before $current_book_id.
 *
 * @param mysqli $conn
 * @param int $student_number
 * @param int $current_book_id
 * @param int $limit  How many recommendations to return          AND b2.borrow_date < b1.borrow_date
 * @return array     [['book_id'=>..., 'title'=>...], ...]
 */
function get_recommendations($conn, $student_number, $current_book_id, $limit = 3) {
    $sql = "
        SELECT 
            b2.book_id, COUNT(*) AS freq, bk2.title 
        FROM borrowings b1
        JOIN borrowings b2 
            ON b1.student_number = b2.student_number
        JOIN books bk1 
            ON b1.book_id = bk1.id
        JOIN books bk2 
            ON b2.book_id = bk2.id
           AND bk2.category = bk1.category
        WHERE b1.book_id = ?
          AND b2.book_id != b1.book_id
          AND b1.student_number != ?
          AND b2.book_id NOT IN (
              SELECT book_id FROM borrowings WHERE student_number = ?
          )
        GROUP BY b2.book_id
        ORDER BY freq DESC
        LIMIT ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $current_book_id, $student_number, $student_number, $limit);
    $stmt->execute();
    $res = $stmt->get_result();

    $recs = [];
    while ($row = $res->fetch_assoc()) {
        $recs[] = [
            'book_id' => (int)$row['book_id'],
            'title'   => $row['title']
        ];
    }

    return $recs;
}



?>
