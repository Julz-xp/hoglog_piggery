<?php
session_start();
if (!isset($_SESSION["farm_id"])) {
  header("Location: ../../index.php");
  exit;
}
require_once "../../config/db.php";

$farm_id = $_SESSION["farm_id"];
$total_users = $pdo->query("SELECT COUNT(*) FROM users WHERE farm_id = $farm_id")->fetchColumn();
$total_caretakers = $pdo->query("SELECT COUNT(*) FROM users WHERE farm_id = $farm_id AND position = 'Caretaker'")->fetchColumn();
$total_vets = $pdo->query("SELECT COUNT(*) FROM users WHERE farm_id = $farm_id AND position = 'Farm Vet'")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>HogLog | Farm Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
:root {
  --blue-main: #4fc3f7;
  --blue-hover: #29b6f6;
  --gray-bg: #f5f7fb;
  --white: #ffffff;
}

body {
  font-family: "Poppins", sans-serif;
  margin: 0;
  background: var(--gray-bg);
  color: #333;
}

/* ===== HEADER ===== */
header {
  background: var(--blue-main);
  color: white;
  padding: 18px 40px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

header h1 {
  font-size: 22px;
  font-weight: 700;
  letter-spacing: 1px;
}

.logout-btn {
  background: white;
  color: var(--blue-main);
  border: none;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 18px;
  cursor: pointer;
  transition: 0.3s;
  position: relative;
}

.logout-btn:hover {
  background: var(--blue-hover);
  color: white;
}

.logout-btn::after {
  content: "Logout";
  position: absolute;
  bottom: -35px;
  background: var(--blue-main);
  color: white;
  font-size: 12px;
  padding: 4px 10px;
  border-radius: 8px;
  opacity: 0;
  transform: translateY(10px);
  pointer-events: none;
  transition: 0.3s ease;
}

.logout-btn:hover::after {
  opacity: 1;
  transform: translateY(0);
}

/* ===== DASHBOARD CONTENT ===== */
.dashboard-container {
  max-width: 1100px;
  margin: 40px auto;
  background: var(--white);
  padding: 30px;
  border-radius: 15px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.dashboard-header {
  text-align: center;
  margin-bottom: 30px;
}

.dashboard-header h2 {
  background: linear-gradient(90deg, #4fc3f7, #29b6f6);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  font-size: 32px;
  font-weight: 700;
  margin: 0;
  text-transform: uppercase;
}

/* ===== STAT CARDS ===== */
.stats {
  display: flex;
  justify-content: space-around;
  flex-wrap: wrap;
  gap: 20px;
}

.stat-card {
  flex: 1 1 250px;
  padding: 25px;
  border-radius: 12px;
  text-align: center;
  box-shadow: 0 4px 10px rgba(0,0,0,0.05);
  transition: 0.4s ease;
  text-decoration: none;
  color: white;
}

/* Gradient colors */
.stat-card.total-users { background: linear-gradient(135deg, #43a047, #66bb6a); } /* Green */
.stat-card.caretakers { background: linear-gradient(135deg, #fb8c00, #ffb74d); } /* Orange */
.stat-card.vets { background: linear-gradient(135deg, #e53935, #ef5350); } /* Red */

/* Hover effect */
.stat-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 20px rgba(0,0,0,0.2);
  filter: brightness(0.9);
}

.stat-card i {
  font-size: 38px;
  margin-bottom: 10px;
  color: white;
}

.stat-card h3 {
  margin: 5px 0;
  font-size: 26px;
  font-weight: 700;
}

.stat-card p {
  font-size: 14px;
  opacity: 0.9;
}

/* ===== QUICK ACTIONS ===== */
.actions {
  display: flex;
  justify-content: center;
  gap: 25px;
  margin-top: 40px;
  flex-wrap: wrap;
}

.action-btn {
  background: var(--blue-main);
  color: white;
  text-decoration: none;
  padding: 12px 25px;
  border-radius: 8px;
  font-weight: 600;
  transition: 0.3s;
}

.action-btn:hover {
  background: var(--blue-hover);
}
</style>
</head>

<body>

<header>
  <h1>🐖 HogLog Farm Dashboard</h1>
  <button class="logout-btn" onclick="window.location.href='../processs/farm_logout.php'">
    <i class="bi bi-box-arrow-right"></i>
  </button>
</header>

<div class="dashboard-container">
  <div class="dashboard-header">
    <h2>Welcome to <?= htmlspecialchars($_SESSION["farm_name"]); ?>!</h2>
  </div>

  <!-- STAT CARDS -->
  <div class="stats">
    <a href="../users/list_users.php" class="stat-card total-users">
      <i class="bi bi-people-fill"></i>
      <h3><?= $total_users ?></h3>
      <p>Total Users</p>
    </a>

    <a href="../users/list_users.php" class="stat-card caretakers">
      <i class="bi bi-person-workspace"></i>
      <h3><?= $total_caretakers ?></h3>
      <p>Caretakers</p>
    </a>

    <a href="../users/list_users.php" class="stat-card vets">
      <i class="bi bi-heart-pulse-fill"></i>
      <h3><?= $total_vets ?></h3>
      <p>Farm Veterinarians</p>
    </a>
  </div>

  <!-- QUICK ACTIONS -->
  <div class="actions">
    <a href="../users/add_users.php" class="action-btn">➕ Register User</a>
    <a href="../users/user_login.php" class="action-btn">🔐 User Log In</a>
    <a href="../users/list_users.php" class="action-btn">👥 View All Users</a>
  </div>
</div>

</body>
</html>
