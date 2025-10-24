<?php
require_once __DIR__ . '/../../../config/db.php';
$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM sow_feed_consumption WHERE feed_id = ?");
$stmt->execute([$id]);

header("Location: list_feed.php");
exit;
?>
