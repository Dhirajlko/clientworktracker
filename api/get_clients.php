<?php
require_once 'auth_guard.php';
requireAuth();

$tenantId = intval($_SESSION['cwt_tenant_id'] ?? $_COOKIE['cwt_tenant_id'] ?? 1);
$db = getDB();

$stmt = $db->prepare("SELECT id, client_name, mobile, company, pan, gstin, email, it_password, password AS gst_password, category, status, tenant_id, created_at FROM clients WHERE tenant_id = ? ORDER BY client_name ASC");
$stmt->bind_param("i", $tenantId);
$stmt->execute();
$result = $stmt->get_result();

$clients = [];
while ($row = $result->fetch_assoc()) {
    $clients[] = [
        'id'          => intval($row['id']),
        'clientId'    => 'CWT-' . $row['id'],
        'name'        => $row['client_name'],
        'clientName'  => $row['client_name'],
        'company'     => $row['company'],
        'pan'         => $row['pan'],
        'gstin'       => $row['gstin'],
        'phone'       => $row['mobile'],
        'mobile'      => $row['mobile'],
        'email'       => $row['email'],
        'itPassword'  => decryptPassword($row['it_password'] ?? ''),
        'gstPassword' => decryptPassword($row['gst_password'] ?? ''),
        'category'    => $row['category'],
        'status'      => $row['status'],
        'tenant_id'   => intval($row['tenant_id']),
        'created_at'  => $row['created_at']
    ];
}

$stmt->close();
$db->close();

sendJSON([
    'success'   => true,
    'count'     => count($clients),
    'clients'   => $clients,
    'tenant_id' => $tenantId
]);
?>