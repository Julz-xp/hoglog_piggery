<?php
require_once __DIR__ . '/../../config/db.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM sop_category_sections WHERE section_id=?");
$stmt->execute([$id]);

header("Location: list_section.php");
exit;
?>
