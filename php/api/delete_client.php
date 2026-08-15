<?php
require_once 'config.php';

$db   = getDB();
$data = getInput();

if (empty($data['id'])) {
    sendJSON(['success' => false, 'error' => 'Client ID required']);
}

$id = $data['id'];

// Delete task logs first, then tasks, then client
$db->query("DELETE tl FROM task_logs tl INNER JOIN work_tasks wt ON tl.task_id = wt.id WHERE wt.client_id = '$id'");
// Note: tasks remain but client_id becomes NULL (or delete them too)
$stmt = $db->prepare("DELETE FROM clients WHERE id = ?");
$stmt->bind_param('s', $id);
$stmt->execute();
$affected = $stmt->affected_rows;
$stmt->close();
$db->close();

sendJSON(['success' => $affected > 0, 'deleted' => $affected]);
