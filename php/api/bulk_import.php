<?php
require_once 'config.php';

$db   = getDB();
$data = getInput();

$items = $data['items'] ?? [];
if (empty($items)) {
    sendJSON(['success' => false, 'error' => 'No items to import']);
}

$imported = 0;
foreach ($items as $item) {
    // Upsert client
    $clientId   = !empty($item['clientId'])   ? $item['clientId']   : 'c_' . uniqid();
    $clientName = $item['clientName'] ?? '';
    $pan        = strtoupper($item['pan']     ?? '');
    $company    = $item['company']    ?? '';
    $gstin      = strtoupper($item['gstin']   ?? '');
    $phone      = $item['phone']      ?? '';
    $email      = $item['email']      ?? '';
    $itPass     = $item['password']   ?? '';

    // Check if client with same PAN or name exists
    if (!empty($pan)) {
        $chk = $db->prepare("SELECT id FROM clients WHERE pan = ? LIMIT 1");
        $chk->bind_param('s', $pan);
        $chk->execute();
        $row = $chk->get_result()->fetch_assoc();
        $chk->close();
        if ($row) $clientId = $row['id'];
    }

    $chk2 = $db->prepare("SELECT id FROM clients WHERE id = ? LIMIT 1");
    $chk2->bind_param('s', $clientId);
    $chk2->execute();
    $exists = $chk2->get_result()->num_rows > 0;
    $chk2->close();

    if (!$exists) {
        $stmt = $db->prepare("
            INSERT IGNORE INTO clients (id, name, company, pan, it_password, gstin, phone, email, city, category, notes)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'India', 'Individual', 'Bulk Import')
        ");
        $stmt->bind_param('ssssssss', $clientId, $clientName, $company, $pan, $itPass, $gstin, $phone, $email);
        $stmt->execute();
        $stmt->close();
    }

    // Insert task
    $taskId     = 't_imp_' . uniqid();
    $svcType    = $item['serviceType']  ?? 'ITR';
    $itrForm    = $item['itrForm']      ?? 'ITR-4';
    $title      = $item['title']        ?? ($clientName . ' - ' . $svcType);
    $period     = $item['period']       ?? '';
    $status     = $item['status']       ?? 'Pending Docs';
    $feeAmount  = (float)($item['feeAmount'] ?? 0);
    $payStatus  = $item['paymentStatus'] ?? 'Pending';
    $dueDate    = !empty($item['dueDate']) ? $item['dueDate'] : null;

    $stmt = $db->prepare("
        INSERT INTO work_tasks (id, client_id, client_name, service_type, itr_form, title, period, status, fee_amount, payment_status, due_date, description)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Imported from spreadsheet')
    ");
    $stmt->bind_param('ssssssssdss', $taskId, $clientId, $clientName, $svcType, $itrForm, $title, $period, $status, $feeAmount, $payStatus, $dueDate);
    $stmt->execute();
    $stmt->close();

    // Initial log
    $today   = date('Y-m-d');
    $logNote = 'Imported from Excel/CSV.';
    $logStmt = $db->prepare("INSERT INTO task_logs (task_id, log_date, note) VALUES (?, ?, ?)");
    $logStmt->bind_param('sss', $taskId, $today, $logNote);
    $logStmt->execute();
    $logStmt->close();

    $imported++;
}

$db->close();
sendJSON(['success' => true, 'imported' => $imported]);
