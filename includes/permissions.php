<?php
function hasPermission($conn, $role, $permission) {
    $stmt = $conn->prepare("SELECT * FROM permissions WHERE role=? AND permission=?");
    $stmt->bind_param("ss", $role, $permission);
    $stmt->execute();
    return $stmt->get_result()->num_rows > 0;
}
?>