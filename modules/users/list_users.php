<?php
session_start();
require_once "../../config/db.php";

// ✅ Ensure farm is logged in
if (!isset($_SESSION["farm_id"])) {
  echo "<p style='color:red;'>⚠️ Please log in as a farm first.</p>";
  exit;
}

$farm_id = $_SESSION["farm_id"];
$farm_name = $_SESSION["farm_name"];

// ✅ Fetch all users for this farm
$stmt = $pdo->prepare("SELECT * FROM users WHERE farm_id = ? ORDER BY user_id DESC");
$stmt->execute([$farm_id]);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="user-list-container">
  <div class="header-bar">
    <h2>👥 Registered Users</h2>
    <a href="../farm/farm_dashboard.php" class="back-btn">
      <i class="bi bi-arrow-left-circle-fill"></i> Back to Dashboard
    </a>
  </div>
  <p>All users under <strong><?= htmlspecialchars($farm_name); ?></strong>.</p>

  <?php if (count($users) > 0): ?>
  <table class="user-table">
    <thead>
      <tr>
        <th>#</th>
        <th>Full Name</th>
        <th>Position</th>
        <th>Username</th>
        <th>Contact</th>
        <th>Email</th>
        <th style="text-align:center;">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($users as $index => $user): ?>
        <tr>
          <td><?= $index + 1 ?></td>
          <td><?= htmlspecialchars($user['full_name']) ?></td>
          <td><?= htmlspecialchars($user['position']) ?></td>
          <td><?= htmlspecialchars($user['username']) ?></td>
          <td><?= htmlspecialchars($user['contact_number']) ?></td>
          <td><?= htmlspecialchars($user['email']) ?></td>
          <td class="action-buttons">
            <a href="view_users.php?id=<?= $user['user_id'] ?>" class="icon-btn view" title="View All Records">
              <i class="bi bi-eye-fill"></i>
            </a>
            <a href="edit_users.php?id=<?= $user['user_id'] ?>" class="icon-btn edit" title="Edit User">
              <i class="bi bi-pencil-square"></i>
            </a>
            <a href="delete_users.php?id=<?= $user['user_id'] ?>" class="icon-btn delete" title="Delete User" onclick="return confirm('Are you sure you want to delete this user?');">
              <i class="bi bi-trash-fill"></i>
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php else: ?>
    <p style="color:#999;">No users have been registered for this farm yet.</p>
  <?php endif; ?>
</div>

<!-- STYLES -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
body {
  font-family: "Poppins", sans-serif;
  background: #f5f7fb;
  margin: 0;
  padding: 0;
}

.user-list-container {
  background: #ffffff;
  margin: 40px auto;
  padding: 25px;
  border-radius: 16px;
  max-width: 1100px;
  box-shadow: 0 6px 20px rgba(0,0,0,0.08);
  animation: fadeIn 0.4s ease;
}

/* HEADER BAR */
.header-bar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: linear-gradient(90deg, #4fc3f7, #29b6f6);
  padding: 15px 25px;
  border-radius: 10px;
  color: white;
  margin-bottom: 25px;
}

.header-bar h2 {
  margin: 0;
  font-size: 22px;
  font-weight: 700;
}

.back-btn {
  background: white;
  color: #29b6f6;
  padding: 8px 16px;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 600;
  transition: 0.3s;
  display: flex;
  align-items: center;
  gap: 6px;
}

.back-btn:hover {
  background: #29b6f6;
  color: white;
  box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}

/* TABLE DESIGN */
.user-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 10px;
}

.user-table th {
  background: #e1f5fe;
  color: #0277bd;
  padding: 12px;
  text-align: left;
  font-weight: 600;
}

.user-table td {
  padding: 12px;
  border-bottom: 1px solid #ddd;
}

.user-table tr:hover {
  background: #f1faff;
  transition: 0.3s;
}

/* ACTION ICONS */
.action-buttons {
  text-align: center;
}

.icon-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border: none;
  padding: 8px 10px;
  margin: 0 4px;
  border-radius: 8px;
  font-size: 16px;
  color: white;
  text-decoration: none;
  transition: 0.3s ease;
}

.icon-btn.view {
  background: #4fc3f7;
}

.icon-btn.view:hover {
  background: #03a9f4;
  transform: translateY(-2px);
}

.icon-btn.edit {
  background: #81c784;
}

.icon-btn.edit:hover {
  background: #66bb6a;
  transform: translateY(-2px);
}

.icon-btn.delete {
  background: #ef5350;
}

.icon-btn.delete:hover {
  background: #e53935;
  transform: translateY(-2px);
}

/* ANIMATION */
@keyframes fadeIn {
  from {opacity: 0; transform: translateY(10px);}
  to {opacity: 1; transform: translateY(0);}
}
</style>
