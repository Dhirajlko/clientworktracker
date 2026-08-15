<?php
require_once 'auth_guard.php';
requireAuth();

$tenantId = intval($_SESSION['cwt_tenant_id'] ?? $_COOKIE['cwt_tenant_id'] ?? 1);
$input = getJSON();

$name         = trim($input['name'] ?? $input['client_name'] ?? '');
$company      = trim($input['company'] ?? '');
$pan          = strtoupper(trim($input['pan'] ?? ''));
$gstin        = strtoupper(trim($input['gstin'] ?? ''));
$mobile       = trim($input['mobile'] ?? $input['phone'] ?? '');
$email        = trim($input['email'] ?? '');
$itPassword   = encryptPassword(trim($input['itPassword'] ?? $input['it_password'] ?? ''));
$gstPassword  = encryptPassword(trim($input['gstPassword'] ?? $input['gst_password'] ?? ''));
$category     = trim($input['category'] ?? 'Individual');
$status       = trim($input['status'] ?? 'Active');

if (empty($name)) {
    sendJSON(['success' => false, 'error' => 'Client name is required']);
}

$db = getDB();

$stmt = $db->prepare("INSERT INTO clients (client_name, company, pan, gstin, mobile, email, it_password, password, category, status, tenant_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssssssi", $name, $company, $pan, $gstin, $mobile, $email, $itPassword, $gstPassword, $category, $status, $tenantId);

if ($stmt->execute()) {
    $newId = $stmt->insert_id;
    $stmt->close();
    $db->close();

    sendJSON([
        'success' => true,
        'client'  => [
            'id'          => $newId,
            'clientId'    => 'CWT-' . $newId,
            'name'        => $name,
            'company'     => $company,
            'pan'         => $pan,
            'gstin'       => $gstin,
            'phone'       => $mobile,
            'email'       => $email,
            'itPassword'  => decryptPassword($itPassword),
            'gstPassword' => decryptPassword($gstPassword),
            'category'    => $category,
            'status'      => $status,
            'tenant_id'   => $tenantId
        ]
    ]);
} else {
    $error = $db->error;
    $stmt->close();
    $db->close();
    sendJSON(['success' => false, 'error' => $error]);
}
?>