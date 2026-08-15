<?php
require_once 'auth_guard.php';
requireAuth(true);

$tenantId = intval($_SESSION['cwt_tenant_id'] ?? $_COOKIE['cwt_tenant_id'] ?? 1);
$input = getJSON();
$clientId = intval($input['id'] ?? $input['client_id'] ?? 0);

if ($clientId <= 0) {
    sendJSON(['success' => false, 'error' => 'Valid Client ID is required for deletion']);
}

$db = getDB();
$stmt = $db->prepare("DELETE FROM clients WHERE id = ? AND tenant_id = ?");
$stmt->bind_param("ii", $clientId, $tenantId);

if ($stmt->execute()) {
    $stmt->close();
    $db->close();
    sendJSON(['success' => true, 'message' => 'Client deleted successfully']);
} else {
    $error = $db->error;
    $stmt->close();
    $db->close();
    sendJSON(['success' => false, 'error' => $error]);
}
?>