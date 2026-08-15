<?php
require_once 'config.php';

$db   = getDB();
$data = getInput();

$taskId = $data['taskId'] ?? '';
$note   = $data['note']   ?? '';
$today  = date('Y-m-d');

if (empty($taskId) || empty($note)) {
    sendJSON(['success' => false, 'error' => 'taskId and note required']);
}

$stmt = $db->prepare("INSERT INTO task_logs (task_id, log_date, note) VALUES (?, ?, ?)");
$stmt->bind_param('sss', $taskId, $today, $note);
$stmt->execute();
$stmt->close();
$db->close();

sendJSON(['success' => true, 'date' => $today, 'note' => $note]);
