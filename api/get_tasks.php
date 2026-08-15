<?php
require_once 'auth_guard.php';
requireAuth();

$tenantId = intval($_SESSION['cwt_tenant_id'] ?? $_COOKIE['cwt_tenant_id'] ?? 1);
$db = getDB();

$stmt = $db->prepare("SELECT * FROM work_tasks WHERE tenant_id = ? ORDER BY id DESC");
$stmt->bind_param("i", $tenantId);
$stmt->execute();
$res = $stmt->get_result();

$tasks = [];
while ($row = $res->fetch_assoc()) {
    $tasks[] = [
        'id'                 => intval($row['id']),
        'clientId'           => $row['client_id'],
        'clientName'         => $row['client_name'],
        'serviceType'        => $row['service_type'],
        'itrForm'            => $row['itr_form'],
        'title'              => $row['title'],
        'taskTitle'          => $row['title'],
        'period'             => $row['period'],
        'turnover'           => $row['turnover'] ?? '',
        'dueDate'            => $row['due_date'],
        'filingDate'         => $row['filing_date'] ?? '',
        'arn'                => $row['arn'] ?? '',
        'verificationStatus' => $row['verification_status'] ?? 'Aadhaar OTP Verified',
        'status'             => $row['status'],
        'priority'           => $row['priority'] ?? 'Medium',
        'assignedTo'         => $row['assigned_to'] ?? 'Self',
        'feeAmount'          => floatval($row['fee_amount'] ?? 0),
        'feeCharged'         => floatval($row['fee_amount'] ?? 0),
        'advancePaid'        => floatval($row['advance_paid'] ?? 0),
        'paymentStatus'      => $row['payment_status'] ?? 'Pending',
        'description'        => $row['description'] ?? '',
        'remarks'            => $row['description'] ?? '',
        'tenant_id'          => intval($row['tenant_id'])
    ];
}

$stmt->close();
$db->close();

sendJSON([
    'success' => true,
    'count'   => count($tasks),
    'tasks'   => $tasks
]);
?>