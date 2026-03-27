<?php
session_start();
include '../config/db.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$res = $conn->query("SELECT * FROM users");
?>

<h3>All Users</h3>

<table border="1">
<tr>
<th>Name</th>
<th>Email</th>
<th>Role</th>
</tr>

<?php while($row = $res->fetch_assoc()): ?>
<tr>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['email']; ?></td>
<td><?php echo $row['role']; ?></td>
</tr>
<?php endwhile; ?>
</table>