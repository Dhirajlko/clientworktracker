<?php
require_once 'config.php';
$db = getDB();

$results = [];

// 1. Which database are we in?
$r = $db->query("SELECT DATABASE() as db, USER() as usr");
$results['connection'] = $r->fetch_assoc();

// 2. Exact column definition of status
$r2 = $db->query("SHOW FULL COLUMNS FROM `clients` LIKE 'status'");
$results['status_column_def'] = $r2->fetch_assoc();

// 3. Count rows where id = 6
$r3 = $db->query("SELECT COUNT(*) as cnt FROM `clients` WHERE `id` = 6");
$results['rows_with_id_6_int'] = $r3->fetch_assoc();

$r3b = $db->query("SELECT COUNT(*) as cnt FROM `clients` WHERE `id` = '6'");
$results['rows_with_id_6_str'] = $r3b->fetch_assoc();

// 4. Any triggers on clients?
$r4 = $db->query("SHOW TRIGGERS FROM `client_work_tracker` WHERE `Table` = 'clients'");
$triggers = [];
while($t = $r4->fetch_assoc()) $triggers[] = $t['Trigger'];
$results['triggers'] = $triggers;

// 5. Try UPDATE by name (not id)
$ok5 = $db->query("UPDATE `clients` SET `status` = 'Non-Active' WHERE `client_name` = 'Akanksha'");
$results['update_by_name_affected'] = $db->affected_rows;
$results['update_by_name_error']    = $db->error ?: 'none';

// 6. Read after name update
$r6 = $db->query("SELECT `status` FROM `clients` WHERE `client_name` = 'Akanksha'");
$results['after_name_update'] = $r6->fetch_assoc();

// 7. Restore
$db->query("UPDATE `clients` SET `status` = '' WHERE `client_name` = 'Akanksha'");

// 8. Try UPDATE by id as integer
$ok8 = $db->query("UPDATE `clients` SET `status` = 'Non-Active' WHERE `id` = 6");
$results['update_by_int_id_affected'] = $db->affected_rows;
$results['update_by_int_id_error']    = $db->error ?: 'none';
$db->query("UPDATE `clients` SET `status` = '' WHERE `id` = 6");

$db->close();
sendJSON($results);
?>
