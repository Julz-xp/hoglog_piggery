<?php
require_once __DIR__ . '/../../../config/db.php';
$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM sow_dry_stage WHERE dry_id = ?");
$stmt->execute([$id]);

header("Location: list_dry.php");
exit;
?>
