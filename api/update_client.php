<?php
require_once 'auth_guard.php';
requireAuth();

$tenantId = intval($_SESSION['cwt_tenant_id'] ?? $_COOKIE['cwt_tenant_id'] ?? 1);
$input = getJSON();

$id          = intval($input['id'] ?? 0);
$name        = trim($input['name'] ?? $input['client_name'] ?? '');
$company     = trim($input['company'] ?? '');
$pan         = strtoupper(trim($input['pan'] ?? ''));
$gstin       = strtoupper(trim($input['gstin'] ?? ''));
$mobile      = trim($input['mobile'] ?? $input['phone'] ?? '');
$email       = trim($input['email'] ?? '');
$itPassword  = encryptPassword(trim($input['itPassword'] ?? $input['it_password'] ?? ''));
$gstPassword = encryptPassword(trim($input['gstPassword'] ?? $input['gst_password'] ?? ''));
$category    = trim($input['category'] ?? 'Individual');
$status      = trim($input['status'] ?? 'Active');

if ($id <= 0 || empty($name)) {
    sendJSON(['success' => false, 'error' => 'Valid Client ID and Name are required']);
}

$db = getDB();
$stmt = $db->prepare("UPDATE clients SET client_name=?, company=?, pan=?, gstin=?, mobile=?, email=?, it_password=?, password=?, category=?, status=? WHERE id=? AND tenant_id=?");
$stmt->bind_param("ssssssssssii", $name, $company, $pan, $gstin, $mobile, $email, $itPassword, $gstPassword, $category, $status, $id, $tenantId);

if ($stmt->execute()) {
    $stmt->close();
    $db->close();
    sendJSON([
        'success' => true,
        'message' => 'Client updated successfully',
        'client'  => [
            'id'          => $id,
            'name'        => $name,
            'company'     => $company,
            'pan'         => $pan,
            'gstin'       => $gstin,
            'phone'       => $mobile,
            'email'       => $email,
            'itPassword'  => decryptPassword($itPassword),
            'gstPassword' => decryptPassword($gstPassword),
            'category'    => $category,
            'status'      => $status
        ]
    ]);
} else {
    $error = $db->error;
    $stmt->close();
    $db->close();
    sendJSON(['success' => false, 'error' => $error]);
}
?>