<?php
require_once __DIR__ . '/../../../config/db.php';
$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM sows WHERE sow_id = ?");
$stmt->execute([$id]);

header("Location: list_sow.php");
exit;
?>
