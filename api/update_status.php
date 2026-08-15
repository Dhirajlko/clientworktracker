<?php
require_once 'auth_guard.php';
requireAuth();

$tenantId = intval($_SESSION['cwt_tenant_id'] ?? $_COOKIE['cwt_tenant_id'] ?? 1);
$input = getJSON();
$taskId = intval($input['id'] ?? $input['taskId'] ?? 0);
$newStatus = trim($input['status'] ?? 'Completed');

if ($taskId <= 0) {
    sendJSON(['success' => false, 'error' => 'Valid Task ID is required']);
}

$db = getDB();
$stmt = $db->prepare("UPDATE work_tasks SET status = ? WHERE id = ? AND tenant_id = ?");
$stmt->bind_param("sii", $newStatus, $taskId, $tenantId);

if ($stmt->execute()) {
    $stmt->close();
    $db->close();
    sendJSON(['success' => true, 'message' => 'Task status updated successfully', 'status' => $newStatus]);
} else {
    $error = $db->error;
    $stmt->close();
    $db->close();
    sendJSON(['success' => false, 'error' => $error]);
}
?>