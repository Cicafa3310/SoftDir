<?php
// pages/reviews.php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db_connect.php';
include    __DIR__ . '/../includes/header.php';

$software_id = isset($_GET['software_id']) ? (int)$_GET['software_id'] : 0;

// Получаем название программы
$stmt = $pdo->prepare("SELECT name FROM software WHERE id = ?");
$stmt->execute([$software_id]);
$soft = $stmt->fetch();

if (!$soft) {
    echo '<p>Программа не найдена.</p>';
    include __DIR__ . '/../includes/footer.php';
    exit();
}

// Обработка отправки отзыва
if ($_SERVER['REQUEST_METHOD'] === 'POST' && is_logged_in() && isset($_POST['rating'])) {
    $rating  = (int)$_POST['rating'];
    $comment = trim($_POST['comment']);

    $ins = $pdo->prepare("
        INSERT INTO reviews (user_id, software_id, rating, comment) 
        VALUES (?, ?, ?, ?)
    ");
    $ins->execute([
        $_SESSION['user_id'],
        $software_id,
        $rating,
        $comment ?: null
    ]);

    header("Location: reviews.php?software_id={$software_id}");
    exit();
}

// Получаем все отзывы
$revStmt = $pdo->prepare("
    SELECT r.*, u.username 
    FROM reviews r 
    JOIN users u ON r.user_id = u.id 
    WHERE r.software_id = ?
    ORDER BY r.created_at DESC
");
$revStmt->execute([$software_id]);
$reviews = $revStmt->fetchAll();
?>

<h2>Отзывы для «<?= htmlspecialchars($soft['name'], ENT_QUOTES) ?>»</h2>

<?php if (empty($reviews)): ?>
    <p>Пока нет ни одного отзыва.</p>
<?php else: ?>
    <?php foreach ($reviews as $r): ?>
        <div class="review">
            <strong><?= htmlspecialchars($r['username'], ENT_QUOTES) ?></strong>
            <span class="rating">
                [<?= str_repeat('★', $r['rating']) ?><?= str_repeat('☆', 5 - $r['rating']) ?>]
            </span>
            <em><?= date('Y-m-d H:i', strtotime($r['created_at'])) ?></em>
            <?php if ($r['comment']): ?>
                <p><?= nl2br(htmlspecialchars($r['comment'], ENT_QUOTES)) ?></p>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php if (is_logged_in()): ?>
    <h3>Оставить отзыв</h3>
    <form method="post" class="review-form">
        <label>
            Оценка:
            <select name="rating" required>
                <option value="">– выберите –</option>
                <?php for ($i = 5; $i >= 1; $i--): ?>
                    <option value="<?= $i ?>"><?= $i ?> звёзд</option>
                <?php endfor; ?>
            </select>
        </label>
        <label>
            Комментарий (необязательно):
            <textarea name="comment" rows="3"></textarea>
        </label>
        <button type="submit">Отправить</button>
    </form>
<?php else: ?>
    <p><a href="login.php">Войдите</a>, чтобы оставить отзыв.</p>
<?php endif; ?>

<p><a href="software_detail.php?id=<?= $software_id ?>">← Назад к деталям</a></p>

<?php include __DIR__ . '/../includes/footer.php'; ?>
