<?php
require_once __DIR__ . '/../../../config/db.php';

if (!isset($_GET['id'])) die("Invalid request.");
$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM piglet_records WHERE piglet_id = ?");
$stmt->execute([$id]);

header("Location: list_piglet.php?deleted=1");
exit;
?>
