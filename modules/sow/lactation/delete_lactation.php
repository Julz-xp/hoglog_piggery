<?php
require_once __DIR__ . '/../../../config/db.php';
$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM sow_lactation WHERE lactation_id = ?");
$stmt->execute([$id]);

header("Location: list_lactation.php");
exit;
?>
