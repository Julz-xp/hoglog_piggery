<?php
require_once __DIR__ . '/../../../config/db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    die("❌ Invalid request: Missing Gilt ID");
}

// Delete record
$stmt = $pdo->prepare("DELETE FROM sow_gilt_stage WHERE gilt_id = ?");
$stmt->execute([$id]);

header("Location: list_gilt.php");
exit;
?>
