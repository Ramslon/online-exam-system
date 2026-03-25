<?php
$servername = "localhost";
$username = "root";
$password = ""; // <-- leave empty if you didn't set a MySQL root password
$dbname = "exam_system";
$port = 3306;

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>