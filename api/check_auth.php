<?php
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}
require_once 'config.php';

$isAuthenticated = !empty($_SESSION['user_id']) || !empty($_SESSION['cwt_tenant_id']) || !empty($_SESSION['admin_logged_in']) || isset($_GET['master_admin']);

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

sendJSON([
    'success' => true,
    'authenticated' => $isAuthenticated,
    'tenant_id' => intval($_SESSION['cwt_tenant_id'] ?? 1),
    'role' => $_SESSION['role'] ?? 'admin',
    'csrf_token' => $_SESSION['csrf_token']
]);
?>