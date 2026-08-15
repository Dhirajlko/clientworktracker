<?php
require_once 'auth_guard.php';
requireAuth();

$tenantId = intval($_SESSION['cwt_tenant_id'] ?? $_COOKIE['cwt_tenant_id'] ?? 1);
$input = getJSON();
$clientId = intval($input['id'] ?? 0);
$newStatus = trim($input['status'] ?? 'Active');

if ($clientId <= 0) {
    sendJSON(['success' => false, 'error' => 'Valid Client ID is required']);
}

$db = getDB();
$stmt = $db->prepare("UPDATE clients SET status = ? WHERE id = ? AND tenant_id = ?");
$stmt->bind_param("sii", $newStatus, $clientId, $tenantId);

if ($stmt->execute()) {
    $stmt->close();
    $db->close();
    sendJSON(['success' => true, 'message' => 'Client status updated successfully', 'status' => $newStatus]);
} else {
    $error = $db->error;
    $stmt->close();
    $db->close();
    sendJSON(['success' => false, 'error' => $error]);
}
?>