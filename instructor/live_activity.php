<?php
include '../config/db.php';

$res = $conn->query("
SELECT r.*, u.name, t.title 
FROM results r
JOIN users u ON r.student_id = u.id
JOIN tests t ON r.test_id = t.id
ORDER BY r.id DESC LIMIT 5
");

while($row = $res->fetch_assoc()){
echo "<li class='list-group-item'>
<strong>{$row['name']}</strong> took 
<strong>{$row['title']}</strong> → 
<span class='badge bg-info'>{$row['score']}%</span>
</li>";
}