<?php
require_once 'config.php';

$db = getDB();

// Fetch all clients ordered by name
$result = $db->query("SELECT * FROM clients ORDER BY name ASC");
$clients = [];

while ($row = $result->fetch_assoc()) {
    $clients[] = [
        'id'         => $row['id'],
        'name'       => $row['name'],
        'company'    => $row['company'],
        'pan'        => $row['pan'],
        'itPassword' => $row['it_password'],
        'gstin'      => $row['gstin'],
        'phone'      => $row['phone'],
        'email'      => $row['email'],
        'city'       => $row['city'],
        'category'   => $row['category'],
        'notes'      => $row['notes'],
    ];
}

$db->close();
sendJSON(['success' => true, 'clients' => $clients]);
