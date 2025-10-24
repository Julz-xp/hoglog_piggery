<?php
require_once "../../config/db.php";

if (!isset($_GET["farm_id"])) {
    die("No farm selected.");
}

try {
    $stmt = $pdo->prepare("DELETE FROM farms WHERE farm_id = ?");
    $stmt->execute([$_GET["farm_id"]]);

    echo "<script>alert('❌ Farm deleted successfully.'); window.location='list_farm.php';</script>";
    exit;
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
