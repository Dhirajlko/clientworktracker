<?php
require_once 'auth_guard.php';
requireAuth();

sendJSON([
    'success' => true,
    'status'  => 'healthy',
    'system'  => 'Client Work Tracker API',
    'time'    => date('Y-m-d H:i:s')
]);
?>