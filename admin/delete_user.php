<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();   
require_once __DIR__ . '/../includes/db_connect.php';
include    __DIR__ . '/../includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id) {
    
    $pdo->prepare("DELETE FROM reviews WHERE user_id = ?")->execute([$id]);
    
    $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$id]);
}

header('Location: users_list.php');
exit();

include    __DIR__ . '/../includes/footer.php';