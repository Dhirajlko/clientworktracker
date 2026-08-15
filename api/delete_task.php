<?php
require_once 'auth_guard.php';
requireAuth();

$tenantId = intval($_SESSION['cwt_tenant_id'] ?? $_COOKIE['cwt_tenant_id'] ?? 1);
$input = getJSON();
$taskId = intval($input['id'] ?? $input['taskId'] ?? 0);

if ($taskId <= 0) {
    sendJSON(['success' => false, 'error' => 'Valid Task ID is required for deletion']);
}

$db = getDB();
$stmt = $db->prepare("DELETE FROM work_tasks WHERE id = ? AND tenant_id = ?");
$stmt->bind_param("ii", $taskId, $tenantId);

if ($stmt->execute()) {
    $stmt->close();
    $db->close();
    sendJSON(['success' => true, 'message' => 'Task deleted successfully']);
} else {
    $error = $db->error;
    $stmt->close();
    $db->close();
    sendJSON(['success' => false, 'error' => $error]);
}
?>