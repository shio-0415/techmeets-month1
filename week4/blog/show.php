<?php
require_once 'db.php';

$id = $_GET['id'] ?? '';
$conn = getDBConnection();

$stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();
$stmt->close();

if (!$post) {
    $conn->close();
    header('Location: index.php');
    exit;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($post['title']); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <a href="index.php">← 一覧に戻る</a>
    <div class="post-detail">
        <h1><?php echo htmlspecialchars($post['title']); ?></h1>
        <p>著者：<?php echo htmlspecialchars($post['author']); ?> | 投稿日：<?php echo $post['created_at']; ?></p>
        <div class="content">
            <?php echo nl2br(htmlspecialchars($post['content'])); ?>
        </div>
        <a href="edit.php?id=<?php echo $post['id']; ?>">編集</a>
        <a href="delete.php?id=<?php echo $post['id']; ?>" onclick="return confirm('本当に削除しますか？')">削除</a>
    </div>
</body>
</html>