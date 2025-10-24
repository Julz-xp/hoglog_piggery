<?php
require_once __DIR__ . '/../../../config/db.php';
$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM sow_health_record WHERE health_id = ?");
$stmt->execute([$id]);

header("Location: list_health.php");
exit;
?>
