<?php
header("Content-Type: application/json; charset=UTF-8");

$host = "localhost";
$dbname = "client_work_tracker";
$username = "Dhiraj";      // 👈 Screenshot me jo database user dikh raha hai
$password = "Vidhina#123"; // 👈 Actual password yahan daalo

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {

    die(json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]));
}