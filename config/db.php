<?php
// =========================================
// DATABASE CONNECTION — HogLog Piggery System
// =========================================

$host = 'localhost';          // usually 'localhost' for XAMPP or online hosting
$dbname = 'hoglog';           // your database name
$username = 'root';           // your MySQL username (default: 'root')
$password = '';               // your MySQL password (keep blank in XAMPP)

// =========================================
// CREATE PDO CONNECTION
// =========================================

try {
    // Data Source Name (DSN)
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

    // PDO Options
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // throw exceptions on error
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // fetch as associative array
        PDO::ATTR_EMULATE_PREPARES => false, // use real prepared statements
    ];

    // Create connection
    $pdo = new PDO($dsn, $username, $password, $options);

    // Optional success message (remove on production)
    // echo "✅ Database connected successfully";

} catch (PDOException $e) {
    // Handle connection error
    die("❌ Database connection failed: " . $e->getMessage());
}


?>
