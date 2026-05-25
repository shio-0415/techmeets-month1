<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>お問い合わせフォーム</title>
<style>
  body { font-family: sans-serif; padding: 24px; max-width: 560px; }
  h1 { font-size: 22px; margin-bottom: 24px; }
  label { display: block; margin-bottom: 4px; font-weight: bold; }
  input, textarea { width: 100%; padding: 8px; margin-bottom: 4px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
  .error { color: red; font-size: 13px; margin-bottom: 12px; }
  button { padding: 10px 24px; font-size: 14px; cursor: pointer; background: #4a90e2; color: #fff; border: none; border-radius: 4px; }
  .result { background: #f0f4ff; padding: 16px; border-radius: 6px; margin-top: 24px; }
  .result p { margin: 8px 0; }
</style>
</head>
<body>

<h1>お問い合わせフォーム</h1>

<?php
$errors = [];
$submitted = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // 入力値の取得とXSS対策
    $name    = htmlspecialchars(trim($_POST["name"] ?? ""));
    $email   = htmlspecialchars(trim($_POST["email"] ?? ""));
    $subject = htmlspecialchars(trim($_POST["subject"] ?? ""));
    $message = htmlspecialchars(trim($_POST["message"] ?? ""));

    // バリデーション：全項目必須チェック
    if ($name === "") {
        $errors["name"] = "名前を入力してください。";
    }
    if ($email === "") {
        $errors["email"] = "メールアドレスを入力してください。";
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        // メールアドレスの形式チェック
        $errors["email"] = "正しいメールアドレスを入力してください。";
    }
    if ($subject === "") {
        $errors["subject"] = "件名を入力してください。";
    }
    if ($message === "") {
        $errors["message"] = "メッセージを入力してください。";
    }

    // エラーがなければ送信成功
    if (empty($errors)) {
        $submitted = true;
    }
}
?>

<?php if ($submitted): ?>
  <!-- 送信成功：確認画面 -->
  <div class="result">
    <h2>送信内容の確認</h2>
    <p><strong>名前：</strong><?php echo $name; ?></p>
    <p><strong>メール：</strong><?php echo $email; ?></p>
    <p><strong>件名：</strong><?php echo $subject; ?></p>
    <p><strong>メッセージ：</strong><?php echo $message; ?></p>
  </div>

<?php else: ?>
  <!-- フォーム -->
  <form method="POST">
    <label>名前 <span style="color:red">*</span></label>
    <input type="text" name="name" value="<?php echo isset($name) ? $name : ''; ?>">
    <?php if (isset($errors["name"])): ?>
      <p class="error"><?php echo $errors["name"]; ?></p>
    <?php endif; ?>

    <label>メールアドレス <span style="color:red">*</span></label>
    <input type="text" name="email" value="<?php echo isset($email) ? $email : ''; ?>">
    <?php if (isset($errors["email"])): ?>
      <p class="error"><?php echo $errors["email"]; ?></p>
    <?php endif; ?>

    <label>件名 <span style="color:red">*</span></label>
    <input type="text" name="subject" value="<?php echo isset($subject) ? $subject : ''; ?>">
    <?php if (isset($errors["subject"])): ?>
      <p class="error"><?php echo $errors["subject"]; ?></p>
    <?php endif; ?>

    <label>メッセージ <span style="color:red">*</span></label>
    <textarea name="message" rows="5"><?php echo isset($message) ? $message : ''; ?></textarea>
    <?php if (isset($errors["message"])): ?>
      <p class="error"><?php echo $errors["message"]; ?></p>
    <?php endif; ?>

    <button type="submit">送信する</button>
  </form>
<?php endif; ?>

</body>
</html>