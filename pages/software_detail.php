<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db_connect.php';
include    __DIR__ . '/../includes/header.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id']) && isset($_POST['rating'])) {
    $rating  = (int)$_POST['rating'];
    $comment = trim($_POST['comment']);
    $stmt = $pdo->prepare("
        INSERT INTO reviews (user_id, software_id, rating, comment) 
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([
        $_SESSION['user_id'], 
        $id, 
        $rating, 
        $comment ?: null
    ]);
    
    header("Location: software_detail.php?id={$id}");
    exit();
}
$stmt = $pdo->prepare("
    SELECT s.*, c.name AS category 
    FROM software s 
    LEFT JOIN categories c ON s.category_id = c.id 
    WHERE s.id = ?
");
$stmt->execute([$id]);
$software = $stmt->fetch();
if (!$software) {
    echo '<p>Программа не найдена.</p>';
    include __DIR__ . '/../includes/footer.php';
    exit();
}
$revStmt = $pdo->prepare("
    SELECT r.*, u.username 
    FROM reviews r 
    JOIN users u ON r.user_id = u.id 
    WHERE r.software_id = ?
    ORDER BY r.created_at DESC
");
$revStmt->execute([$id]);
$reviews = $revStmt->fetchAll();
?>
<h2><?= htmlspecialchars($software['name']) ?></h2>
<a href="/pages/reviews.php?software_id=<?= $software['id'] ?>" class="btn-reviews">
    Смотреть отзывы
</a>
<p><strong>Версия:</strong> <?= htmlspecialchars($software['version'] ?: '—') ?></p>
<p><strong>Разработчик:</strong> <?= htmlspecialchars($software['developer'] ?: '—') ?></p>
<p><strong>Описание:</strong><br><?= nl2br(htmlspecialchars($software['description'] ?: '—')) ?></p>
<p><strong>Ссылка:</strong>
<?php if ($software['link']): ?>
    <a href="<?= htmlspecialchars($software['link']) ?>" target="_blank">
    <?= htmlspecialchars($software['link']) ?>
    </a>
<?php else: ?>
    —
<?php endif; ?>
</p>
<?php include __DIR__ . '/../includes/footer.php'; ?>
