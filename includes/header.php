    <?php
    require_once __DIR__ . '/auth.php';
    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
    <meta charset="UTF-8">
    <title>Справочник ПО</title>
        <link rel="stylesheet" href="/css/styles.css">
        <link rel="stylesheet" href="/css/edit_software.css">
        <link rel="stylesheet" href="/css/software_list.css">
        <link rel="stylesheet" href="/css/reviews.css">
        <link rel="stylesheet" href="/css/admin.css">
        <link rel="stylesheet" href="/css/categories.css">
        <script src="/js/app.js" defer></script>
    </head>
    <body>
    <header>
    <h1><a href="/pages/index.php">Справочник ПО</a></h1>
    <nav>
        <a href="/pages/software_list.php">Все программы</a>
        <?php if (is_logged_in()): ?>
        <a href="/pages/profile.php">Профиль</a>
        <?php if (is_admin()): ?>
            <a href="/admin/index.php">Админ-панель</a>
        <?php endif; ?>
        <a href="/pages/logout.php">Выход</a>
        <?php else: ?>
        <a href="/pages/login.php">Вход</a>
        <a href="/pages/register.php">Регистрация</a>
        <?php endif; ?>
    </nav>
    </header>
    <main>
