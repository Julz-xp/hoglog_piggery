<?php
require_once '../../config/db.php';

if (!isset($_GET['id'])) die("User ID missing.");
$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM users WHERE user_id=?");
if ($stmt->execute([$id])) {
    echo "✅ User deleted successfully.<br>";
} else {
    echo "❌ Error deleting user.<br>";
}
?>
<p><a href="list_user.php">⬅ Back to list</a></p>
