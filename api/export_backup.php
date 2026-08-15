<?php
require_once 'auth_guard.php';
requireAuth(true);

$tenantId = intval($_SESSION['cwt_tenant_id'] ?? $_COOKIE['cwt_tenant_id'] ?? 1);
$db = getDB();

$clientsStmt = $db->prepare("SELECT * FROM clients WHERE tenant_id = ?");
$clientsStmt->bind_param("i", $tenantId);
$clientsStmt->execute();
$clients = $clientsStmt->get_result()->fetch_all(MYSQLI_ASSOC);
$clientsStmt->close();

$tasksStmt = $db->prepare("SELECT * FROM work_tasks WHERE tenant_id = ?");
$tasksStmt->bind_param("i", $tenantId);
$tasksStmt->execute();
$tasks = $tasksStmt->get_result()->fetch_all(MYSQLI_ASSOC);
$tasksStmt->close();

$db->close();

sendJSON([
    'success'     => true,
    'export_date' => date('Y-m-d H:i:s'),
    'tenant_id'   => $tenantId,
    'clients'     => $clients,
    'tasks'       => $tasks
]);
?>