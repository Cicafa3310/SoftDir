<?php

if (session_status() === PHP_SESSION_NONE) {
    
    ini_set('session.use_strict_mode', 1);
    ini_set('session.cookie_httponly', 1);
    session_start();
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function require_login() {
    if (!is_logged_in()) {
        header('Location: /pages/login.php');
        exit();
    }
}

function is_admin() {
    return is_logged_in() && ($_SESSION['role'] ?? '') === 'admin';
}

function require_admin() {
    if (!is_admin()) {
        header('HTTP/1.1 403 Forbidden');
        echo '<h1>403 — Доступ запрещён</h1><p>У вас нет прав администратора.</p>';
        exit();
    }
}
