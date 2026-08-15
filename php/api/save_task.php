<?php
require_once 'config.php';

$db   = getDB();
$data = getInput();

if (empty($data['title'])) {
    sendJSON(['success' => false, 'error' => 'Task title is required']);
}

$id         = !empty($data['id'])         ? $data['id']                 : 't_' . uniqid();
$clientId   = $data['clientId']           ?? null;
$clientName = $data['clientName']         ?? '';
$svcType    = $data['serviceType']        ?? 'ITR';
$itrForm    = $data['itrForm']            ?? 'ITR-4';
$title      = $data['title']              ?? '';
$period     = $data['period']             ?? '';
$turnover   = $data['turnover']           ?? '';
$dueDate    = !empty($data['dueDate'])    ? $data['dueDate']    : null;
$filingDate = !empty($data['filingDate']) ? $data['filingDate'] : null;
$arn        = $data['arn']               ?? '';
$verifStat  = $data['verificationStatus'] ?? '';
$status     = $data['status']             ?? 'Pending Docs';
$priority   = $data['priority']           ?? 'Medium';
$assignedTo = $data['assignedTo']         ?? 'Self';
$feeAmount  = (float)($data['feeAmount']  ?? 0);
$payStatus  = $data['paymentStatus']      ?? 'Pending';
$desc       = $data['description']        ?? '';
$logs       = $data['logs']              ?? [];

// Check if task exists
$check = $db->prepare("SELECT id FROM work_tasks WHERE id = ?");
$check->bind_param('s', $id);
$check->execute();
$exists = $check->get_result()->num_rows > 0;
$check->close();

if ($exists) {
    $stmt = $db->prepare("
        UPDATE work_tasks SET 
            client_id=?, client_name=?, service_type=?, itr_form=?, title=?, period=?, turnover=?,
            due_date=?, filing_date=?, arn=?, verification_status=?, status=?, priority=?,
            assigned_to=?, fee_amount=?, payment_status=?, description=?
        WHERE id=?
    ");
    $stmt->bind_param(
        'ssssssssssssssdsss',
        $clientId, $clientName, $svcType, $itrForm, $title, $period, $turnover,
        $dueDate, $filingDate, $arn, $verifStat, $status, $priority,
        $assignedTo, $feeAmount, $payStatus, $desc, $id
    );
    $stmt->execute();
    $stmt->close();
} else {
    $stmt = $db->prepare("
        INSERT INTO work_tasks 
            (id, client_id, client_name, service_type, itr_form, title, period, turnover,
             due_date, filing_date, arn, verification_status, status, priority,
             assigned_to, fee_amount, payment_status, description)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
    ");
    $stmt->bind_param(
        'sssssssssssssssdss',
        $id, $clientId, $clientName, $svcType, $itrForm, $title, $period, $turnover,
        $dueDate, $filingDate, $arn, $verifStat, $status, $priority,
        $assignedTo, $feeAmount, $payStatus, $desc
    );
    $stmt->execute();
    $stmt->close();
}

// Save logs: delete old + re-insert
$db->query("DELETE FROM task_logs WHERE task_id = '$id'");
if (!empty($logs)) {
    $logStmt = $db->prepare("INSERT INTO task_logs (task_id, log_date, note) VALUES (?, ?, ?)");
    foreach ($logs as $log) {
        $logDate = $log['date'] ?? date('Y-m-d');
        $logNote = $log['note'] ?? '';
        $logStmt->bind_param('sss', $id, $logDate, $logNote);
        $logStmt->execute();
    }
    $logStmt->close();
}

$db->close();
sendJSON(['success' => true, 'id' => $id]);
