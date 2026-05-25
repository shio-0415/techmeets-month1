<?php
require_once 'db.php';
$conn = getDBConnection();

$query = "SELECT id, title, author, created_at FROM posts ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>記事一覧</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>記事一覧</h1>
    <a href="create.php">新規記事投稿</a>

    <div class="posts">
        <?php while ($row = $result->fetch_assoc()): ?>
        <div class="post-card">
            <h2><a href="show.php?id=<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['title']); ?></a></h2>
            <p>著者：<?php echo htmlspecialchars($row['author']); ?> | 投稿日：<?php echo $row['created_at']; ?></p>
            <a href="edit.php?id=<?php echo $row['id']; ?>">編集</a>
            <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('本当に削除しますか？')">削除</a>
        </div>
        <?php endwhile; ?>
    </div>
</body>
</html>

<?php $conn->close(); ?>