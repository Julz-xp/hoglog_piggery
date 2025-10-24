<?php
session_start();
require_once "../../config/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    try {
        $stmt = $pdo->prepare("SELECT * FROM farms WHERE username = ?");
        $stmt->execute([$username]);
        $farm = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($farm && password_verify($password, $farm["password"])) {

            // ✅ Explicitly store all key info into session
            $_SESSION["farm_id"] = $farm["farm_id"];
            $_SESSION["farm_name"] = $farm["farm_name"];
            $_SESSION["owner_name"] = $farm["owner_name"];
            $_SESSION["farm_type"] = $farm["farm_type"];
            $_SESSION["email"] = $farm["email"];
            $_SESSION["contact_number"] = $farm["contact_number"];

            // ✅ Force session data to write before redirect
            session_write_close();

            header("Location: ../farm/farm_dashboard.php");
            exit;
        } else {
            echo "<script>
                alert('❌ Invalid username or password!');
                window.location='../../index.php';
            </script>";
            exit;
        }
    } catch (PDOException $e) {
        die('Database error: ' . $e->getMessage());
    }
} else {
    header("Location: ../../index.php");
    exit;
}
?>
