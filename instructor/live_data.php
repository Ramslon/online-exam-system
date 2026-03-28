<?php
include '../config/db.php';

echo json_encode([
    "tests" => $conn->query("SELECT COUNT(*) as t FROM tests")->fetch_assoc()['t'],
    "students" => $conn->query("SELECT COUNT(*) as s FROM users WHERE role='student'")->fetch_assoc()['s'],
    "attempts" => $conn->query("SELECT COUNT(*) as a FROM results")->fetch_assoc()['a']
]);