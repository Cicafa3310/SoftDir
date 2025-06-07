<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();   
require_once __DIR__ . '/../includes/db_connect.php';
include    __DIR__ . '/../includes/header.php';
?>

<h2>Админ-панель</h2>
<ul>
    <li><a href="add_software.php">Добавить программу</a></li>
    <li><a href="edit_software.php">Редактировать программу</a></li>
    <li><a href="../pages/software_list.php">Список программ</a></li>
    <li><a href="users_list.php">Управление пользователями</a></li>
    <li><a href="categories_list.php">Управление категориями</a></li>
</ul>

<?php include __DIR__ . '/../includes/footer.php'; ?>
