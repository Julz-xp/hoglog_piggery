<?php
require_once __DIR__ . '/../../../config/db.php';
$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM sow_ai_attempts WHERE ai_id = ?");
$stmt->execute([$id]);

header("Location: list_ai.php");
exit;
?>

