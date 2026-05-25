<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>コメント投稿</title>
</head>
<body>

<h1>コメント投稿フォーム</h1>

<form method="POST">
  <label>名前:</label>
  <input type="text" name="name"><br>
  <label>コメント:</label>
  <textarea name="comment"></textarea><br>
  <button type="submit">投稿する</button>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // XSS対策：htmlspecialcharsでエスケープする
    $name    = htmlspecialchars(trim($_POST["name"] ?? ""));
    $comment = htmlspecialchars(trim($_POST["comment"] ?? ""));

    // 修正①：= ではなく === で比較する
    // 修正③：コメントの空チェックも追加する
    if ($name === "") {
        echo "名前を入力してください。";
    } elseif ($comment === "") {
        echo "コメントを入力してください。";
    } else {
        // 修正②：htmlspecialcharsでエスケープ済みの変数を出力する
        echo "<p>" . $name . "さんのコメント:</p>";
        echo "<p>" . $comment . "</p>";
    }
}
?>

</body>
</html>