<?php
require_once 'config.php';
$conn = getDB();
$res = $conn->query("SHOW COLUMNS FROM work_tasks");
$cols = [];
while ($row = $res->fetch_assoc()) {
    $cols[] = $row['Field'] . ' (' . $row['Type'] . ')';
}
echo json_encode($cols);
?>