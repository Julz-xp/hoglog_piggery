<?php
session_start();
require_once "../../config/db.php";

// ✅ Ensure farm is logged in
if (!isset($_SESSION["farm_id"])) {
  echo "<p style='color:red;'>⚠️ Please log in as a farm first.</p>";
  exit;
}

$farm_id = $_SESSION["farm_id"];

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ? AND farm_id = ?");
  $stmt->execute([$id, $farm_id]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    echo "<p style='color:red;'>❌ User not found or doesn’t belong to this farm.</p>";
    exit;
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View User | HogLog</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
body {
  font-family: "Poppins", sans-serif;
  background: linear-gradient(135deg, #e3f2fd, #bbdefb, #e1f5fe);
  height: 100vh;
  margin: 0;
  display: flex;
  justify-content: center;
  align-items: flex-start;
  padding: 50px 0;
  overflow-x: hidden;
}

/* Floating glass card */
.profile-wrapper {
  width: 90%;
  max-width: 950px;
  background: rgba(255,255,255,0.9);
  backdrop-filter: blur(20px);
  border-radius: 20px;
  box-shadow: 0 10px 40px rgba(0,0,0,0.1);
  padding: 40px 50px;
  animation: fadeIn 0.6s ease;
  position: relative;
}

/* Back button */
.back-btn {
  position: absolute;
  top: 25px;
  left: 25px;
  background: linear-gradient(135deg, #4fc3f7, #29b6f6);
  color: white;
  border: none;
  padding: 10px 16px;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 6px;
  transition: 0.3s;
}
.back-btn:hover {
  background: linear-gradient(135deg, #0288d1, #03a9f4);
  box-shadow: 0 5px 15px rgba(0,0,0,0.2);
  transform: translateY(-2px);
}

/* Header Section */
.profile-header {
  text-align: center;
  margin-bottom: 35px;
}
.profile-header h2 {
  color: #01579b;
  font-size: 1.8em;
  font-weight: 700;
  margin: 0;
}
.role-tag {
  background: #4fc3f7;
  color: #fff;
  display: inline-block;
  padding: 6px 14px;
  border-radius: 20px;
  font-weight: 600;
  margin-top: 10px;
  letter-spacing: 0.5px;
}

/* Info Grid */
.section {
  background: #ffffff;
  border-radius: 14px;
  padding: 25px;
  margin-bottom: 30px;
  box-shadow: 0 3px 10px rgba(0,0,0,0.05);
  border-left: 6px solid #29b6f6;
  transition: 0.3s;
}
.section:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}
.section h3 {
  color: #0277bd;
  margin-bottom: 15px;
  font-weight: 700;
  font-size: 1.1em;
  display: flex;
  align-items: center;
  gap: 8px;
}
.section h3 i {
  color: #4fc3f7;
}

/* Grid Layout */
.info-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px 25px;
}
.info-grid div {
  font-size: 0.95em;
  color: #333;
}
.info-grid strong {
  color: #0277bd;
  font-weight: 600;
}

/* Animation */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(15px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
</head>
<body>

<div class="profile-wrapper">
  <button class="back-btn" onclick="window.location.href='../users/list_users.php'">
    <i class="bi bi-arrow-left-circle"></i> Back to User List
  </button>

  <div class="profile-header">
    <h2><i class="bi bi-person-bounding-box"></i> <?= htmlspecialchars($user['full_name']) ?></h2>
    <span class="role-tag"><?= htmlspecialchars($user['position']) ?></span>
  </div>

  <div class="section">
    <h3><i class="bi bi-person-lines-fill"></i> Personal Information</h3>
    <div class="info-grid">
      <div><strong>📞 Contact:</strong> <?= htmlspecialchars($user['contact_number']) ?></div>
      <div><strong>📧 Email:</strong> <?= htmlspecialchars($user['email']) ?></div>
      <div><strong>⚧ Gender:</strong> <?= htmlspecialchars($user['gender']) ?></div>
      <div><strong>🎂 Birth Date:</strong> <?= htmlspecialchars($user['date_of_birth']) ?></div>
      <div><strong>🏠 Address:</strong> <?= htmlspecialchars($user['home_address']) ?></div>
      <div><strong>🌏 Nationality:</strong> <?= htmlspecialchars($user['nationality']) ?></div>
      <div><strong>💍 Civil Status:</strong> <?= htmlspecialchars($user['civil_status']) ?></div>
      <div><strong>👤 Username:</strong> <?= htmlspecialchars($user['username']) ?></div>
    </div>
  </div>

  <div class="section">
    <h3><i class="bi bi-heart-pulse-fill"></i> Emergency Details</h3>
    <div class="info-grid">
      <div><strong>👨‍👩‍👧 Contact Name:</strong> <?= htmlspecialchars($user['emergency_contact_name']) ?></div>
      <div><strong>📞 Number:</strong> <?= htmlspecialchars($user['emergency_contact_number']) ?></div>
      <div><strong>📍 Address:</strong> <?= htmlspecialchars($user['emergency_address']) ?></div>
    </div>
  </div>

  <div class="section">
    <h3><i class="bi bi-shield-lock-fill"></i> Account Details</h3>
    <div class="info-grid">
      <div><strong>💼 Position:</strong> <?= htmlspecialchars($user['position']) ?></div>
      <div><strong>🆔 Farm ID:</strong> <?= htmlspecialchars($user['farm_id']) ?></div>
      <div><strong>👁 Username:</strong> <?= htmlspecialchars($user['username']) ?></div>
      <div><strong>🔒 Password:</strong> ********</div>
    </div>
  </div>
</div>

</body>
</html>
<?php
  exit;
}
?>
<p style="color:red;">⚠️ Invalid access — no user selected.</p>
