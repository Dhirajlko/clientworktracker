<?php
require_once 'config.php';

$db   = getDB();
$data = getInput();

if (empty($data['name'])) {
    sendJSON(['success' => false, 'error' => 'Client name is required']);
}

$id      = !empty($data['id'])      ? $data['id']         : 'c_' . uniqid();
$name    = $data['name']            ?? '';
$company = $data['company']         ?? '';
$pan     = strtoupper($data['pan']  ?? '');
$itPass  = $data['itPassword']      ?? '';
$gstin   = strtoupper($data['gstin'] ?? '');
$phone   = $data['phone']           ?? '';
$email   = $data['email']           ?? '';
$city    = $data['city']            ?? 'India';
$cat     = $data['category']        ?? 'Individual';
$notes   = $data['notes']           ?? '';

// Check if client exists (UPDATE) or new (INSERT)
$check = $db->prepare("SELECT id FROM clients WHERE id = ?");
$check->bind_param('s', $id);
$check->execute();
$exists = $check->get_result()->num_rows > 0;
$check->close();

if ($exists) {
    // UPDATE
    $stmt = $db->prepare("
        UPDATE clients 
        SET name=?, company=?, pan=?, it_password=?, gstin=?, phone=?, email=?, city=?, category=?, notes=?
        WHERE id=?
    ");
    $stmt->bind_param('sssssssssss', $name, $company, $pan, $itPass, $gstin, $phone, $email, $city, $cat, $notes, $id);
    $stmt->execute();
    $stmt->close();
    $db->close();
    sendJSON(['success' => true, 'id' => $id, 'action' => 'updated']);
} else {
    // INSERT
    $stmt = $db->prepare("
        INSERT INTO clients (id, name, company, pan, it_password, gstin, phone, email, city, category, notes)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param('sssssssssss', $id, $name, $company, $pan, $itPass, $gstin, $phone, $email, $city, $cat, $notes);
    $stmt->execute();
    $stmt->close();
    $db->close();
    sendJSON(['success' => true, 'id' => $id, 'action' => 'inserted']);
}
