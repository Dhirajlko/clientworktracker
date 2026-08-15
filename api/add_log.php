<?php
require_once 'auth_guard.php';
requireAuth();

$input = getJSON();
$taskId = intval($input['taskId'] ?? $input['task_id'] ?? 0);
$note   = trim($input['note'] ?? $input['log'] ?? '');
$logDate = trim($input['date'] ?? date('Y-m-d'));

if ($taskId <= 0 || empty($note)) {
    sendJSON(['success' => false, 'error' => 'Task ID and Log Note are required']);
}

$db = getDB();
$stmt = $db->prepare("INSERT INTO task_logs (task_id, log_date, note) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $taskId, $logDate, $note);

if ($stmt->execute()) {
    $stmt->close();
    $db->close();
    sendJSON(['success' => true, 'message' => 'Log added successfully', 'log' => ['date' => $logDate, 'note' => $note]]);
} else {
    $error = $db->error;
    $stmt->close();
    $db->close();
    sendJSON(['success' => false, 'error' => $error]);
}
?>