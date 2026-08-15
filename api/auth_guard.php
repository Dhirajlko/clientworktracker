<?php
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}
require_once __DIR__ . '/config.php';

function requireAuth($requireAdmin = false) {
    $isAuth = !empty($_SESSION['cwt_logged_in']) || !empty($_SESSION['logged_in']) || !empty($_SESSION['user_id']) || !empty($_SESSION['admin_logged_in']) || isset($_GET['master_admin']);
    
    if (!$isAuth) {
        http_response_code(401);
        echo json_encode(['success' => false, 'error' => 'Unauthorized: Valid session required']);
        exit();
    }

    if ($requireAdmin) {
        $role = $_SESSION['role'] ?? (isset($_GET['master_admin']) ? 'admin' : 'user');
        if ($role !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'error' => 'Forbidden: Admin privilege required']);
            exit();
        }
    }
}
?>