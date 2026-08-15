<?php
require_once 'config.php';

$db = getDB();

// Fetch all clients
$clients = [];
$cRes = $db->query("SELECT * FROM clients ORDER BY name ASC");
while ($row = $cRes->fetch_assoc()) {
    $clients[] = [
        'id' => $row['id'], 'name' => $row['name'], 'company' => $row['company'],
        'pan' => $row['pan'], 'itPassword' => $row['it_password'],
        'gstin' => $row['gstin'], 'phone' => $row['phone'],
        'email' => $row['email'], 'city' => $row['city'],
        'category' => $row['category'], 'notes' => $row['notes'],
    ];
}

// Fetch all tasks with logs
$tasks = [];
$tRes = $db->query("SELECT * FROM work_tasks ORDER BY created_at DESC");
while ($row = $tRes->fetch_assoc()) {
    $tid  = $row['id'];
    $logR = $db->query("SELECT log_date, note FROM task_logs WHERE task_id = '$tid' ORDER BY id ASC");
    $logs = [];
    while ($l = $logR->fetch_assoc()) {
        $logs[] = ['date' => $l['log_date'], 'note' => $l['note']];
    }
    $tasks[] = [
        'id' => $tid, 'clientId' => $row['client_id'], 'clientName' => $row['client_name'],
        'serviceType' => $row['service_type'], 'itrForm' => $row['itr_form'],
        'title' => $row['title'], 'period' => $row['period'], 'turnover' => $row['turnover'],
        'dueDate' => $row['due_date'], 'filingDate' => $row['filing_date'],
        'arn' => $row['arn'], 'verificationStatus' => $row['verification_status'],
        'status' => $row['status'], 'priority' => $row['priority'],
        'assignedTo' => $row['assigned_to'], 'feeAmount' => (float)$row['fee_amount'],
        'paymentStatus' => $row['payment_status'], 'description' => $row['description'],
        'logs' => $logs,
    ];
}

$db->close();

$backup = [
    'app'        => 'Client Work & Tax Tracker',
    'version'    => '2.0',
    'exportedAt' => date('c'),
    'clients'    => $clients,
    'tasks'      => $tasks,
];

header('Content-Disposition: attachment; filename="client_work_backup_' . date('Y-m-d') . '.json"');
header('Content-Type: application/json');
echo json_encode($backup, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
exit();
