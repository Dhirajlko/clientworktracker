<?php
require_once 'config.php';

$db   = getDB();
$data = getInput();

if (empty($data['id'])) {
    sendJSON(['success' => false, 'error' => 'Task ID required']);
}

$id = $data['id'];

// Delete logs first, then task
$db->query("DELETE FROM task_logs WHERE task_id = '$id'");
$stmt = $db->prepare("DELETE FROM work_tasks WHERE id = ?");
$stmt->bind_param('s', $id);
$stmt->execute();
$affected = $stmt->affected_rows;
$stmt->close();
$db->close();

sendJSON(['success' => $affected > 0]);
