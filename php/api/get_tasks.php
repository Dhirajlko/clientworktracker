<?php
require_once 'config.php';

$db = getDB();

// Fetch all tasks
$result = $db->query("SELECT * FROM work_tasks ORDER BY created_at DESC");
$tasks  = [];

while ($row = $result->fetch_assoc()) {
    // Fetch logs for each task
    $taskId  = $row['id'];
    $logRes  = $db->query("SELECT log_date, note FROM task_logs WHERE task_id = '$taskId' ORDER BY log_date ASC, id ASC");
    $logs    = [];
    while ($log = $logRes->fetch_assoc()) {
        $logs[] = ['date' => $log['log_date'], 'note' => $log['note']];
    }

    $tasks[] = [
        'id'                 => $row['id'],
        'clientId'           => $row['client_id'],
        'clientName'         => $row['client_name'],
        'serviceType'        => $row['service_type'],
        'itrForm'            => $row['itr_form'],
        'title'              => $row['title'],
        'period'             => $row['period'],
        'turnover'           => $row['turnover'],
        'dueDate'            => $row['due_date'],
        'filingDate'         => $row['filing_date'],
        'arn'                => $row['arn'],
        'verificationStatus' => $row['verification_status'],
        'status'             => $row['status'],
        'priority'           => $row['priority'],
        'assignedTo'         => $row['assigned_to'],
        'feeAmount'          => (float)$row['fee_amount'],
        'paymentStatus'      => $row['payment_status'],
        'description'        => $row['description'],
        'logs'               => $logs,
    ];
}

$db->close();
sendJSON(['success' => true, 'tasks' => $tasks]);
