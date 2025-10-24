<?php
require_once __DIR__ . '/../../../config/db.php';
if (!isset($_GET['id'])) die("Invalid request.");

$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM piglet_feed_consumption WHERE feed_id = ?");
$stmt->execute([$id]);

header("Location: list_feed.php?deleted=1");
exit;
?>
