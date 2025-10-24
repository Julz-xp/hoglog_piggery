<?php
require_once __DIR__ . '/../../../config/db.php';

if (!isset($_GET['id'])) die("Invalid Request");

$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM fattener_records WHERE fattener_id = ?");
$stmt->execute([$id]);

header("Location: list_profile.php?deleted=1");
exit;
?>
