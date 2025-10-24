<?php
require_once "../../config/db.php";

if (!isset($_GET["farm_id"])) {
    die("No farm selected.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $farm_id = $_POST["farm_id"];
    $farm_name = $_POST["farm_name"];
    $owner_name = $_POST["owner_name"];
    $contact_number = $_POST["contact_number"];
    $email = $_POST["email"];
    $farm_address = $_POST["farm_address"];
    $farm_size = $_POST["farm_size"];
    $farm_type = $_POST["farm_type"];

    try {
        $stmt = $pdo->prepare("UPDATE farms SET farm_name=?, owner_name=?, contact_number=?, email=?, farm_address=?, farm_size=?, farm_type=? WHERE farm_id=?");
        $stmt->execute([$farm_name, $owner_name, $contact_number, $email, $farm_address, $farm_size, $farm_type, $farm_id]);

        echo "<script>alert('✅ Farm updated successfully!'); window.location='list_farm.php';</script>";
        exit;
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
} else {
    $stmt = $pdo->prepare("SELECT * FROM farms WHERE farm_id = ?");
    $stmt->execute([$_GET["farm_id"]]);
    $farm = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Farm | HogLog</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
body {
  font-family: 'Poppins', sans-serif;
  background: #e1f5fe;
  margin: 0;
  padding: 30px;
}
form {
  background: #fff;
  padding: 25px;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  width: 600px;
  margin: auto;
}
h2 { color: #0277bd; margin-bottom: 20px; }
input, textarea, select {
  width: 100%;
  padding: 10px;
  margin-bottom: 15px;
  border-radius: 6px;
  border: 1px solid #ccc;
}
button {
  background: #4fc3f7;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 8px;
  cursor: pointer;
}
button:hover { background: #29b6f6; }
</style>
</head>
<body>
<form method="POST">
<h2>Edit Farm Details</h2>
<input type="hidden" name="farm_id" value="<?= $farm["farm_id"]; ?>">
<input type="text" name="farm_name" value="<?= htmlspecialchars($farm["farm_name"]); ?>" required>
<input type="text" name="owner_name" value="<?= htmlspecialchars($farm["owner_name"]); ?>" required>
<input type="text" name="contact_number" value="<?= htmlspecialchars($farm["contact_number"]); ?>" required>
<input type="email" name="email" value="<?= htmlspecialchars($farm["email"]); ?>" required>
<textarea name="farm_address" required><?= htmlspecialchars($farm["farm_address"]); ?></textarea>
<select name="farm_size" required>
  <option value="<?= $farm["farm_size"]; ?>" selected><?= $farm["farm_size"]; ?></option>
  <option value="Small">Small</option>
  <option value="Medium">Medium</option>
  <option value="Large">Large</option>
</select>
<select name="farm_type" required>
  <option value="<?= $farm["farm_type"]; ?>" selected><?= $farm["farm_type"]; ?></option>
  <option value="Backyard">Backyard</option>
  <option value="Commercial">Commercial</option>
  <option value="Cooperative">Cooperative</option>
</select>
<button type="submit">Update Farm</button>
</form>
</body>
</html>
