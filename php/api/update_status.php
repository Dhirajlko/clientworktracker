<?php
require_once 'config.php';

$db   = getDB();
$data = getInput();

// Update status for one or many tasks
$ids       = $data['ids']       ?? [];   // array of task IDs
$newStatus = $data['status']    ?? '';
$logNote   = $data['logNote']   ?? "Status updated to '$newStatus'";
$today     = date('Y-m-d');

if (empty($ids) || empty($newStatus)) {
    sendJSON(['success' => false, 'error' => 'ids and status required']);
}

$placeholders = implode(',', array_fill(0, count($ids), '?'));
$types = str_repeat('s', count($ids));

// Update status
$sql  = "UPDATE work_tasks SET status = ? WHERE id IN ($placeholders)";
$stmt = $db->prepare($sql);
$params = array_merge([$newStatus], $ids);
$stmt->bind_param('s' . $types, ...$params);
$stmt->execute();
$stmt->close();

// Add log entry for each task
$logStmt = $db->prepare("INSERT INTO task_logs (task_id, log_date, note) VALUES (?, ?, ?)");
foreach ($ids as $taskId) {
    $logStmt->bind_param('sss', $taskId, $today, $logNote);
    $logStmt->execute();
}
$logStmt->close();

$db->close();
sendJSON(['success' => true, 'updated' => count($ids)]);
