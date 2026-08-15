<?php
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}

// 1. CORS PROTECTION
$allowed_origins = ['https://mybook1.in', 'https://leocomputers.co.in'];
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

if (in_array($origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: " . $origin);
} else {
    header("Access-Control-Allow-Origin: https://mybook1.in");
}

header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-CSRF-Token, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

header("Content-Type: application/json; charset=UTF-8");

// Load .env variables strictly
$envFile = __DIR__ . '/.env';
if (!file_exists($envFile)) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => "Environment file (.env) missing"]);
    exit();
}

$env = parse_ini_file($envFile);
if (!$env || empty($env['DB_HOST']) || empty($env['DB_USER']) || empty($env['DB_PASS']) || empty($env['DB_NAME'])) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => "Invalid database environment configuration"]);
    exit();
}

foreach ($env as $k => $v) {
    putenv("$k=$v");
}

function getDB() {
    $host = getenv('DB_HOST');
    $user = getenv('DB_USER');
    $pass = getenv('DB_PASS');
    $name = getenv('DB_NAME');

    $conn = new mysqli($host, $user, $pass, $name);
    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode(["success" => false, "error" => "Database connection failed"]);
        exit();
    }
    $conn->set_charset("utf8mb4");
    return $conn;
}

function getJSON() {
    $raw = file_get_contents('php://input');
    return json_decode($raw, true) ?? [];
}

function sendJSON($data) {
    echo json_encode($data);
    exit();
}

function encryptPassword($plainText) {
    if (empty($plainText)) return '';
    $key = getenv('CWT_ENCRYPTION_KEY') ?: 'e3f81a7b9c2d5e0f4a1b8c3d9e2f5a0b';
    $iv = openssl_random_pseudo_bytes(16);
    $encrypted = openssl_encrypt($plainText, 'AES-256-CBC', $key, 0, $iv);
    return base64_encode($iv . $encrypted);
}

function decryptPassword($cipherText) {
    if (empty($cipherText)) return '';
    $key = getenv('CWT_ENCRYPTION_KEY') ?: 'e3f81a7b9c2d5e0f4a1b8c3d9e2f5a0b';
    $data = base64_decode($cipherText, true);
    if ($data === false || strlen($data) < 16) return $cipherText;
    $iv = substr($data, 0, 16);
    $encrypted = substr($data, 16);
    $decrypted = openssl_decrypt($encrypted, 'AES-256-CBC', $key, 0, $iv);
    return $decrypted !== false ? $decrypted : $cipherText;
}
?>