<?php
require_once __DIR__ . '/../config.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, DB_OPT);
} catch (PDOException $e) {
    error_log("DB Connection failed: " . $e->getMessage());
    
    if (PROD) {
        http_response_code(500);
        echo '<h1>500 — Внутренняя ошибка сервера</h1><p>Попробуйте позже.</p>';
        exit();
    } else {
        die("Ошибка подключения: " . $e->getMessage());
    }
}
