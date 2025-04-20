<?php
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="test.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['Name', 'Age']);
fputcsv($output, ['Alice', 25]);
fputcsv($output, ['Bob', 30]);
fclose($output);
exit;
?>
