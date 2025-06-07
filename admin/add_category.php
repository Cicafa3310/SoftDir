<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();   
require_once __DIR__ . '/../includes/db_connect.php';
include    __DIR__ . '/../includes/header.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);

    if ($name === '') {
        $error = 'Название не может быть пустым.';
    } else {
        
        $check = $pdo->prepare("SELECT COUNT(*) FROM categories WHERE name = ?");
        $check->execute([$name]);
        if ($check->fetchColumn() > 0) {
            $error = 'Категория с таким именем уже существует.';
        } else {
            
            try {
                $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
                $stmt->execute([$name]);
                
                header('Location: categories_list.php');
                exit();
            } catch (PDOException $e) {
                
                $error = 'Ошибка при добавлении категории: ' . htmlspecialchars($e->getMessage());
            }
        }
    }
}


?>

<h2>Добавить категорию</h2>

<?php if ($error): ?>
    <p style="color: red;"><?= $error ?></p>
<?php endif; ?>

<form method="post">
    <label>
        Название категории:
        <input name="name" value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>" required>
    </label>
    <button type="submit">Создать</button>
    <a href="categories_list.php">← Назад к списку</a>
</form>

<?php include __DIR__ . '/../includes/footer.php'; ?>
