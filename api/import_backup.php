<?php
require_once 'auth_guard.php';
requireAuth(true);

$tenantId = intval($_SESSION['cwt_tenant_id'] ?? $_COOKIE['cwt_tenant_id'] ?? 1);
$input = getJSON();

if (empty($input['confirm_wipe']) || $input['confirm_wipe'] !== true) {
    sendJSON([
        'success' => false,
        'error'   => 'Importing backup will overwrite existing data. Please pass confirm_wipe=true to proceed.'
    ]);
}

$clients = $input['clients'] ?? [];
$tasks   = $input['tasks'] ?? [];

$db = getDB();

$delTasks = $db->prepare("DELETE FROM work_tasks WHERE tenant_id = ?");
$delTasks->bind_param("i", $tenantId);
$delTasks->execute();
$delTasks->close();

$delClients = $db->prepare("DELETE FROM clients WHERE tenant_id = ?");
$delClients->bind_param("i", $tenantId);
$delClients->execute();
$delClients->close();

if (!empty($clients)) {
    $cStmt = $db->prepare("INSERT INTO clients (client_name, company, pan, gstin, mobile, email, it_password, password, category, status, tenant_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($clients as $c) {
        $cStmt->bind_param("ssssssssssi", $c['client_name'], $c['company'], $c['pan'], $c['gstin'], $c['mobile'], $c['email'], $c['it_password'], $c['password'], $c['category'], $c['status'], $tenantId);
        $cStmt->execute();
    }
    $cStmt->close();
}

$db->close();

sendJSON([
    'success' => true,
    'message' => 'Backup imported successfully',
    'imported_clients' => count($clients),
    'imported_tasks' => count($tasks)
]);
?>