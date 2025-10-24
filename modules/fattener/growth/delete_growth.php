<?php
require_once __DIR__ . '/../../../config/db.php';

if (!isset($_GET['id'])) die("Invalid request");

$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM fattener_growth_record WHERE growth_id = ?");
$stmt->execute([$id]);

header("Location: list_growth.php?deleted=1");
exit;
?>
