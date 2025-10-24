<?php
session_start();
require_once "../../config/db.php";

// ✅ Ensure farm is logged in first
if (!isset($_SESSION["farm_id"])) {
  echo "<p style='color:red;'>⚠️ Access denied. Please log in as a farm first.</p>";
  exit;
}

// ✅ Handle login
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND farm_id = ?");
    $stmt->execute([$username, $_SESSION["farm_id"]]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["password"])) {
      $_SESSION["user_id"] = $user["user_id"];
      $_SESSION["full_name"] = $user["full_name"];
      $_SESSION["position"] = $user["position"];
      $_SESSION["farm_id"] = $user["farm_id"];
      header("Location: ../users/user_dashboard.php");
      exit;
    } else {
      $error = "❌ Invalid username or password.";
    }
  } catch (PDOException $e) {
    $error = "Database error: " . htmlspecialchars($e->getMessage());
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>HogLog | User Login</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
body {
  font-family: "Poppins", sans-serif;
  background: linear-gradient(135deg, #e3f2fd, #bbdefb);
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  margin: 0;
}

.login-card {
  background: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(15px);
  box-shadow: 0 8px 30px rgba(0,0,0,0.1);
  border-radius: 16px;
  padding: 40px 35px;
  width: 100%;
  max-width: 400px;
  text-align: center;
  animation: fadeIn 0.5s ease;
}

.login-card h2 {
  color: #0288d1;
  font-weight: 700;
  margin-bottom: 10px;
}

.login-card p {
  color: #555;
  font-size: 0.9em;
  margin-bottom: 30px;
}

.input-group {
  position: relative;
  margin-bottom: 25px;
}

.input-group input {
  width: 100%;
  padding: 12px 10px;
  border: 1px solid #cfd8dc;
  border-radius: 10px;
  font-size: 0.95em;
  outline: none;
  background: transparent;
  transition: all 0.3s ease;
}

.input-group label {
  position: absolute;
  left: 14px;
  top: 12px;
  color: #607d8b;
  font-size: 0.9em;
  pointer-events: none;
  transition: 0.2s ease;
}

.input-group input:focus {
  border-color: #42a5f5;
  box-shadow: 0 0 6px rgba(33,150,243,0.3);
}

.input-group input:focus + label,
.input-group input:not(:placeholder-shown) + label {
  top: -8px;
  left: 10px;
  background: #ffffff;
  padding: 0 6px;
  font-size: 0.75em;
  color: #1565c0;
}

.login-btn {
  width: 100%;
  padding: 12px;
  background: linear-gradient(135deg, #4fc3f7, #29b6f6);
  border: none;
  border-radius: 10px;
  color: white;
  font-weight: 600;
  cursor: pointer;
  transition: 0.3s ease;
}

.login-btn:hover {
  background: linear-gradient(135deg, #0288d1, #03a9f4);
  box-shadow: 0 6px 15px rgba(0,0,0,0.2);
  transform: translateY(-2px);
}

.error-message {
  color: #e53935;
  background: #ffebee;
  border: 1px solid #ef9a9a;
  padding: 8px;
  border-radius: 6px;
  margin-bottom: 20px;
  font-size: 0.9em;
}

.back-link {
  display: inline-block;
  margin-top: 20px;
  color: #0288d1;
  text-decoration: none;
  font-weight: 600;
  transition: 0.3s;
}

.back-link:hover {
  color: #01579b;
  text-decoration: underline;
}

@keyframes fadeIn {
  from {opacity: 0; transform: translateY(20px);}
  to {opacity: 1; transform: translateY(0);}
}
</style>
</head>

<body>
  <div class="login-card">
    <h2><i class="bi bi-person-circle"></i> User Login</h2>
    <p>Access your account for <strong><?= htmlspecialchars($_SESSION['farm_name']); ?></strong></p>

    <?php if (!empty($error)): ?>
      <div class="error-message"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" action="../users/user_login.php">
      <div class="input-group">
        <input type="text" name="username" id="username" required placeholder=" " autocomplete="off">
        <label for="username"><i class="bi bi-person-fill"></i> Username</label>
      </div>

      <div class="input-group">
        <input type="password" name="password" id="password" required placeholder=" ">
        <label for="password"><i class="bi bi-lock-fill"></i> Password</label>
      </div>

      <button type="submit" class="login-btn">Log In</button>
    </form>

    <a href="../farm/farm_dashboard.php" class="back-link"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
  </div>
</body>
</html>
