<?php
require_once 'db.php';

$id    = $_GET['id'] ?? '';
$error = '';

$conn = getDBConnection();

$stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$post   = $result->fetch_assoc();
$stmt->close();

if (!$post) {
    $conn->close();
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title   = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $author  = trim($_POST['author'] ?? '');

    if ($title === '' || $content === '' || $author === '') {
        $error = 'すべての項目を入力してください。';
    } else {
        $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ?, author = ? WHERE id = ?");
        $stmt->bind_param("sssi", $title, $content, $author, $id);

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header('Location: index.php');
            exit;
        } else {
            $error = '更新に失敗しました: ' . $stmt->error;
            $stmt->close();
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>記事編集</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>記事編集</h1>
    <a href="index.php">← 一覧に戻る</a>

    <?php if ($error): ?>
    <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>タイトル：
            <input type="text" name="title" value="<?php echo htmlspecialchars($_POST['title'] ?? $post['title']); ?>">
        </label><br>
        <label>著者：
            <input type="text" name="author" value="<?php echo htmlspecialchars($_POST['author'] ?? $post['author']); ?>">
        </label><br>
        <label>本文：<br>
            <textarea name="content" rows="10"><?php echo htmlspecialchars($_POST['content'] ?? $post['content']); ?></textarea>
        </label><br>
        <button type="submit">更新する</button>
    </form>
</body>
</html>