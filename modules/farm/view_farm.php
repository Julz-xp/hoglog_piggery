<?php
require_once "../../config/db.php";

if (!isset($_GET["farm_id"])) {
    die("No farm selected.");
}

try {
    $stmt = $pdo->prepare("SELECT * FROM farms WHERE farm_id = ?");
    $stmt->execute([$_GET["farm_id"]]);
    $farm = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$farm) {
        die("Farm not found.");
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Farm | HogLog</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
body {
  font-family: 'Poppins', sans-serif;
  background: #e1f5fe;
  margin: 0;
  padding: 30px;
}
.container {
  background: #fff;
  padding: 25px;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  width: 600px;
  margin: auto;
}
h2 { color: #0277bd; margin-bottom: 20px; }
p { margin: 8px 0; }
strong { color: #01579b; }
a {
  display: inline-block;
  margin-top: 15px;
  padding: 8px 15px;
  background: #4fc3f7;
  color: #fff;
  border-radius: 5px;
  text-decoration: none;
}
a:hover { background: #29b6f6; }
</style>
</head>
<body>
<div class="container">
<h2>Farm Details</h2>
<p><strong>Farm Name:</strong> <?= htmlspecialchars($farm["farm_name"]); ?></p>
<p><strong>Owner:</strong> <?= htmlspecialchars($farm["owner_name"]); ?></p>
<p><strong>Contact:</strong> <?= htmlspecialchars($farm["contact_number"]); ?></p>
<p><strong>Email:</strong> <?= htmlspecialchars($farm["email"]); ?></p>
<p><strong>Address:</strong> <?= htmlspecialchars($farm["farm_address"]); ?></p>
<p><strong>Farm Size:</strong> <?= htmlspecialchars($farm["farm_size"]); ?></p>
<p><strong>Farm Type:</strong> <?= htmlspecialchars($farm["farm_type"]); ?></p>
<a href="list_farm.php">Back to List</a>
</div>
</body>
</html>
