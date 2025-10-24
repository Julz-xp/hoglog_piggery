<?php
require_once __DIR__ . '/../../../config/db.php';
$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM sow_gestation WHERE gestation_id = ?");
$stmt->execute([$id]);

header("Location: list_gestation.php");
exit;
?>
