<?php
require_once 'auth_guard.php';
requireAuth();

$tenantId = intval($_SESSION['cwt_tenant_id'] ?? $_COOKIE['cwt_tenant_id'] ?? 1);
$input = getJSON();

$taskId             = intval($input['id'] ?? $input['taskId'] ?? 0);
$clientId           = trim($input['clientId'] ?? $input['client_id'] ?? '');
$clientName         = trim($input['clientName'] ?? $input['client_name'] ?? '');
$serviceType        = trim($input['serviceType'] ?? $input['service_type'] ?? 'ITR');
$itrForm            = trim($input['itrForm'] ?? $input['itr_form'] ?? '');
$title              = trim($input['title'] ?? $input['taskTitle'] ?? $input['task_title'] ?? '');
$period             = trim($input['period'] ?? '');
$turnover           = trim($input['turnover'] ?? '');
$dueDate            = trim($input['dueDate'] ?? $input['due_date'] ?? date('Y-m-d'));
$filingDate         = trim($input['filingDate'] ?? $input['filing_date'] ?? '');
$arn                = trim($input['arn'] ?? '');
$verificationStatus = trim($input['verificationStatus'] ?? $input['verification_status'] ?? 'Aadhaar OTP Verified');
$status             = trim($input['status'] ?? 'Pending Docs');
$priority           = trim($input['priority'] ?? 'Medium');
$assignedTo         = trim($input['assignedTo'] ?? $input['assigned_to'] ?? 'Self');
$feeAmount          = floatval($input['feeAmount'] ?? $input['fee_amount'] ?? $input['feeCharged'] ?? $input['fee_charged'] ?? 0);
$advancePaid        = floatval($input['advancePaid'] ?? $input['advance_paid'] ?? 0);
$paymentStatus      = trim($input['paymentStatus'] ?? $input['payment_status'] ?? 'Pending');
$description        = trim($input['description'] ?? $input['remarks'] ?? '');

$srvLower = strtolower($serviceType);
$formLower = strtolower($itrForm);

if (strpos($srvLower, 'food') !== false || strpos($srvLower, 'fssai') !== false) {
    if (strpos($formLower, 'itr') !== false || strpos($formLower, 'gstr') !== false) {
        sendJSON(['success' => false, 'error' => 'Invalid Form / Work Type for Food License (FSSAI).']);
    }
    if (empty($title) || strpos(strtolower($title), 'itr') !== false) {
        $title = 'FSSAI Registration';
    }
} else if (strpos($srvLower, 'gst') !== false) {
    if (strpos($formLower, 'itr') !== false) {
        sendJSON(['success' => false, 'error' => 'Invalid Form / Work Type for GST.']);
    }
} else if (strpos($srvLower, 'itr') !== false || strpos($srvLower, 'income tax') !== false) {
    if (strpos($formLower, 'gstr') !== false || strpos($formLower, 'fssai') !== false) {
        sendJSON(['success' => false, 'error' => 'Invalid Form / Work Type for Income Tax.']);
    }
}

if (empty($title)) {
    $title = $serviceType . " " . $itrForm;
}

$db = getDB();

if ($taskId > 0) {
    $stmt = $db->prepare("UPDATE work_tasks SET client_id=?, client_name=?, service_type=?, itr_form=?, title=?, period=?, turnover=?, due_date=?, filing_date=?, arn=?, verification_status=?, status=?, priority=?, assigned_to=?, fee_amount=?, advance_paid=?, payment_status=?, description=? WHERE id=? AND tenant_id=?");
    $stmt->bind_param("sssssssssssssdssdiii", $clientId, $clientName, $serviceType, $itrForm, $title, $period, $turnover, $dueDate, $filingDate, $arn, $verificationStatus, $status, $priority, $assignedTo, $feeAmount, $advancePaid, $paymentStatus, $description, $taskId, $tenantId);
    
    if ($stmt->execute()) {
        $stmt->close();
        $db->close();
        sendJSON([
            'success' => true,
            'message' => 'Task updated successfully',
            'task'    => [
                'id'                 => $taskId,
                'clientId'           => $clientId,
                'clientName'         => $clientName,
                'serviceType'        => $serviceType,
                'itrForm'            => $itrForm,
                'title'              => $title,
                'period'             => $period,
                'dueDate'            => $dueDate,
                'status'             => $status,
                'feeAmount'          => $feeAmount,
                'advancePaid'        => $advancePaid,
                'paymentStatus'      => $paymentStatus,
                'description'        => $description,
                'tenant_id'          => $tenantId
            ]
        ]);
    } else {
        $error = $db->error;
        $stmt->close();
        $db->close();
        sendJSON(['success' => false, 'error' => $error]);
    }
} else {
    $stmt = $db->prepare("INSERT INTO work_tasks (client_id, client_name, service_type, itr_form, title, period, turnover, due_date, filing_date, arn, verification_status, status, priority, assigned_to, fee_amount, advance_paid, payment_status, description, tenant_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssssssdssdsi", $clientId, $clientName, $serviceType, $itrForm, $title, $period, $turnover, $dueDate, $filingDate, $arn, $verificationStatus, $status, $priority, $assignedTo, $feeAmount, $advancePaid, $paymentStatus, $description, $tenantId);
    
    if ($stmt->execute()) {
        $newTaskId = $stmt->insert_id;
        $stmt->close();
        $db->close();

        sendJSON([
            'success' => true,
            'message' => 'Task created successfully',
            'task'    => [
                'id'                 => $newTaskId,
                'clientId'           => $clientId,
                'clientName'         => $clientName,
                'serviceType'        => $serviceType,
                'itrForm'            => $itrForm,
                'title'              => $title,
                'period'             => $period,
                'dueDate'            => $dueDate,
                'status'             => $status,
                'feeAmount'          => $feeAmount,
                'advancePaid'        => $advancePaid,
                'paymentStatus'      => $paymentStatus,
                'description'        => $description,
                'tenant_id'          => $tenantId
            ]
        ]);
    } else {
        $error = $db->error;
        $stmt->close();
        $db->close();
        sendJSON(['success' => false, 'error' => $error]);
    }
}
?>