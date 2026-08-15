<?php
require_once 'config.php';

$db  = getDB();
$raw = file_get_contents('php://input');

if (empty($raw)) {
    sendJSON(['success' => false, 'error' => 'No data received']);
}

$backup = json_decode($raw, true);
if (!isset($backup['clients']) || !isset($backup['tasks'])) {
    sendJSON(['success' => false, 'error' => 'Invalid backup file format']);
}

// Clear existing data
$db->query("DELETE FROM task_logs");
$db->query("DELETE FROM work_tasks");
$db->query("DELETE FROM clients");

$clientCount = 0;
$taskCount   = 0;

// Restore clients
foreach ($backup['clients'] as $c) {
    $id      = $c['id']         ?? ('c_' . uniqid());
    $name    = $c['name']       ?? '';
    $company = $c['company']    ?? '';
    $pan     = $c['pan']        ?? '';
    $itPass  = $c['itPassword'] ?? '';
    $gstin   = $c['gstin']      ?? '';
    $phone   = $c['phone']      ?? '';
    $email   = $c['email']      ?? '';
    $city    = $c['city']       ?? 'India';
    $cat     = $c['category']   ?? 'Individual';
    $notes   = $c['notes']      ?? '';

    $stmt = $db->prepare("INSERT IGNORE INTO clients (id,name,company,pan,it_password,gstin,phone,email,city,category,notes) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param('sssssssssss', $id,$name,$company,$pan,$itPass,$gstin,$phone,$email,$city,$cat,$notes);
    $stmt->execute();
    $stmt->close();
    $clientCount++;
}

// Restore tasks and logs
foreach ($backup['tasks'] as $t) {
    $id         = $t['id']                 ?? ('t_' . uniqid());
    $clientId   = $t['clientId']           ?? null;
    $clientName = $t['clientName']         ?? '';
    $svcType    = $t['serviceType']        ?? 'ITR';
    $itrForm    = $t['itrForm']            ?? 'ITR-4';
    $title      = $t['title']              ?? '';
    $period     = $t['period']             ?? '';
    $turnover   = $t['turnover']           ?? '';
    $dueDate    = !empty($t['dueDate'])    ? $t['dueDate']    : null;
    $filingDate = !empty($t['filingDate']) ? $t['filingDate'] : null;
    $arn        = $t['arn']               ?? '';
    $verifStat  = $t['verificationStatus'] ?? '';
    $status     = $t['status']             ?? 'Pending Docs';
    $priority   = $t['priority']           ?? 'Medium';
    $assignedTo = $t['assignedTo']         ?? 'Self';
    $feeAmount  = (float)($t['feeAmount']  ?? 0);
    $payStatus  = $t['paymentStatus']      ?? 'Pending';
    $desc       = $t['description']        ?? '';
    $logs       = $t['logs']              ?? [];

    $stmt = $db->prepare("INSERT IGNORE INTO work_tasks (id,client_id,client_name,service_type,itr_form,title,period,turnover,due_date,filing_date,arn,verification_status,status,priority,assigned_to,fee_amount,payment_status,description) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param('sssssssssssssssdss', $id,$clientId,$clientName,$svcType,$itrForm,$title,$period,$turnover,$dueDate,$filingDate,$arn,$verifStat,$status,$priority,$assignedTo,$feeAmount,$payStatus,$desc);
    $stmt->execute();
    $stmt->close();

    // Restore logs
    $logStmt = $db->prepare("INSERT INTO task_logs (task_id, log_date, note) VALUES (?, ?, ?)");
    foreach ($logs as $log) {
        $lDate = $log['date'] ?? date('Y-m-d');
        $lNote = $log['note'] ?? '';
        $logStmt->bind_param('sss', $id, $lDate, $lNote);
        $logStmt->execute();
    }
    $logStmt->close();
    $taskCount++;
}

$db->close();
sendJSON(['success' => true, 'clients' => $clientCount, 'tasks' => $taskCount]);
