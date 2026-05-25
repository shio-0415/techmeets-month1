<?php
require_once 'db.php';

$id = $_GET['id'] ?? '';

if ($id === '') {
    header('Location: index.php');
    exit;
}

$conn = getDBConnection();
$stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();
$conn->close();

header('Location: index.php');
exit;
?>