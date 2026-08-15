<?php
require_once 'auth_guard.php';
requireAuth(true);

$tenantId = intval($_SESSION['cwt_tenant_id'] ?? $_COOKIE['cwt_tenant_id'] ?? 1);
$input = getJSON();
$rows = $input['clients'] ?? $input['data'] ?? [];

if (empty($rows) || !is_array($rows)) {
    sendJSON(['success' => false, 'error' => 'No client rows provided for bulk import']);
}

$db = getDB();
$imported = 0;

$stmt = $db->prepare("INSERT INTO clients (client_name, company, pan, gstin, mobile, email, it_password, password, category, status, tenant_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

foreach ($rows as $r) {
    $name = trim($r['name'] ?? $r['client_name'] ?? '');
    if (empty($name)) continue;

    $comp   = trim($r['company'] ?? '');
    $pan    = strtoupper(trim($r['pan'] ?? ''));
    $gst    = strtoupper(trim($r['gstin'] ?? ''));
    $mobile = trim($r['mobile'] ?? $r['phone'] ?? '');
    $email  = trim($r['email'] ?? '');
    $itP    = encryptPassword(trim($r['itPassword'] ?? $r['it_password'] ?? ''));
    $gstP   = encryptPassword(trim($r['gstPassword'] ?? $r['gst_password'] ?? ''));
    $cat    = trim($r['category'] ?? 'Individual');
    $st     = trim($r['status'] ?? 'Active');

    $stmt->bind_param("ssssssssssi", $name, $comp, $pan, $gst, $mobile, $email, $itP, $gstP, $cat, $st, $tenantId);
    if ($stmt->execute()) {
        $imported++;
    }
}

$stmt->close();
$db->close();

sendJSON(['success' => true, 'message' => 'Bulk import completed', 'imported_count' => $imported]);
?>