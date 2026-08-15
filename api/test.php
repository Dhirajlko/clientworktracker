<?php
require_once 'config.php';

header('Content-Type: text/html; charset=utf-8');

echo '<!DOCTYPE html><html><head><title>DB Test - Client Work Tracker</title><style>
body { font-family: sans-serif; background: #0f172a; color: #fff; padding: 40px; text-align: center; }
.card { background: #1e293b; border-radius: 16px; border: 2px solid #3b82f6; max-width: 600px; margin: 0 auto; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
.success { color: #4ade80; font-size: 22px; font-weight: bold; }
.error { color: #f87171; font-size: 22px; font-weight: bold; }
.info { color: #93c5fd; margin-top: 15px; font-size: 14px; text-align: left; background: #0f172a; padding: 15px; border-radius: 8px; }
</style></head><body><div class="card">';

try {
    $db = getDB();
    echo '<div class="success">✅ Database Connected Successfully!</div>';
    
    $res = $db->query("SELECT COUNT(*) AS total FROM clients");
    $row = $res ? $res->fetch_assoc() : ['total' => 0];
    
    echo '<p style="font-size: 18px; margin-top: 15px;">Total Clients in DB: <b>' . $row['total'] . '</b></p>';
    
    echo '<div class="info">';
    echo '<b>Table Structure Check:</b><br>';
    $cols = $db->query("SHOW COLUMNS FROM clients");
    while ($c = $cols->fetch_assoc()) {
        echo '• ' . $c['Field'] . ' (' . $c['Type'] . ')<br>';
    }
    echo '</div>';
    
    $db->close();
} catch (Exception $e) {
    echo '<div class="error">❌ Database Connection Error</div>';
    echo '<div class="info">' . htmlspecialchars($e->getMessage()) . '</div>';
}

echo '</div></body></html>';
?>
