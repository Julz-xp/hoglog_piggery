<?php
require_once __DIR__ . '/../../../config/db.php';
$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM sow_exit_record WHERE exit_id = ?");
$stmt->execute([$id]);

header("Location: list_exit.php");
exit;
?>
