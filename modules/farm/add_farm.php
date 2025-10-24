<?php
require_once "../../config/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $farm_name = trim($_POST["farm_name"]);
    $owner_name = trim($_POST["owner_name"]);
    $contact_number = trim($_POST["contact_number"]);
    $email = trim($_POST["email"]);
    $farm_address = trim($_POST["farm_address"]);
    $farm_size = $_POST["farm_size"];
    $farm_type = $_POST["farm_type"];
    $username = trim($_POST["username"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    try {
        // ✅ Check if username already exists
        $check = $pdo->prepare("SELECT * FROM farms WHERE username = ?");
        $check->execute([$username]);

        if ($check->rowCount() > 0) {
            echo "<script>
                alert('⚠️ Username already exists. Please choose another.');
                window.location='../../index.php';
            </script>";
            exit;
        }

        // ✅ Insert new record
        $stmt = $pdo->prepare("INSERT INTO farms 
            (farm_name, owner_name, contact_number, email, farm_address, farm_size, farm_type, username, password)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$farm_name, $owner_name, $contact_number, $email, $farm_address, $farm_size, $farm_type, $username, $password]);

        echo "<script>
            alert('✅ Farm registered successfully! You can now log in.');
            window.location='../../index.php';
        </script>";
        exit;

    } catch (PDOException $e) {
        die('Database error: ' . $e->getMessage());
    }
} else {
    header("Location: ../../index.php");
    exit;
}
?>
