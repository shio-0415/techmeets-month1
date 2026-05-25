<?php
require_once 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title   = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $author  = trim($_POST['author'] ?? '');

    if ($title === '' || $content === '' || $author === '') {
        $error = 'すべての項目を入力してください。';
    } else {
        $conn = getDBConnection();
        $stmt = $conn->prepare("INSERT INTO posts (title, content, author) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $content, $author);

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header('Location: index.php');
            exit;
        } else {
            $error = '投稿に失敗しました: ' . $stmt->error;
            $stmt->close();
            $conn->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>記事投稿</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>新規記事投稿</h1>
    <a href="index.php">← 一覧に戻る</a>

    <?php if ($error): ?>
    <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>タイトル：
            <input type="text" name="title" value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
        </label><br>
        <label>著者：
            <input type="text" name="author" value="<?php echo htmlspecialchars($_POST['author'] ?? ''); ?>">
        </label><br>
        <label>本文：<br>
            <textarea name="content" rows="10"><?php echo htmlspecialchars($_POST['content'] ?? ''); ?></textarea>
        </label><br>
        <button type="submit">投稿する</button>
    </form>
</body>
</html>