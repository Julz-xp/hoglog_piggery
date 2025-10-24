<?php
session_start();

// ✅ Destroy all session data
session_unset();
session_destroy();

// ✅ Redirect back to farm dashboard
header("Location: ../farm/farm_dashboard.php");
exit;
?>
