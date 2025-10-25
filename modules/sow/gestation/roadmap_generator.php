<?php
/**
 * GESTATION AUTOMATION
 * Generates Feed + Health roadmap entries for a sow based on template tables.
 * Triggered after AI confirmation → Positive.
 */

function generateGestationRoadmap(PDO $pdo, int $sow_id, string $ai_date): void {
    // -------------------------------------------
    // 🐷 1. FETCH FEED TEMPLATE
    // -------------------------------------------
    $feedTemplates = $pdo->query("SELECT * FROM gestation_feed_templates ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);

    // Create per-sow feed roadmap table if it doesn't exist
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS gestation_feed_roadmap (
            id INT AUTO_INCREMENT PRIMARY KEY,
            sow_id INT NOT NULL,
            stage_name VARCHAR(100),
            duration_days VARCHAR(50),
            feed_type VARCHAR(150),
            daily_feed DECIMAL(4,2),
            purpose TEXT,
            status ENUM('upcoming','current','completed') DEFAULT 'upcoming',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (sow_id) REFERENCES sows(sow_id) ON DELETE CASCADE
        )
    ");

    $insertFeed = $pdo->prepare("
        INSERT INTO gestation_feed_roadmap
        (sow_id, stage_name, duration_days, feed_type, daily_feed, purpose, status, created_at)
        VALUES (?, ?, ?, ?, ?, ?, 'upcoming', NOW())
    ");

    foreach ($feedTemplates as $f) {
        $insertFeed->execute([
            $sow_id,
            $f['stage_name'],
            $f['duration_days'],
            $f['feed_type'],
            $f['daily_feed'],
            $f['purpose']
        ]);
    }

    // -------------------------------------------
    // 💉 2. FETCH HEALTH TEMPLATE
    // -------------------------------------------
    $healthTemplates = $pdo->query("SELECT * FROM gestation_health_templates ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);

    // Create per-sow health roadmap table if it doesn't exist
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS gestation_health_roadmap (
            id INT AUTO_INCREMENT PRIMARY KEY,
            sow_id INT NOT NULL,
            stage_name VARCHAR(100),
            days_range VARCHAR(50),
            treatment_action TEXT,
            purpose TEXT,
            status ENUM('upcoming','current','completed') DEFAULT 'upcoming',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (sow_id) REFERENCES sows(sow_id) ON DELETE CASCADE
        )
    ");

    $insertHealth = $pdo->prepare("
        INSERT INTO gestation_health_roadmap
        (sow_id, stage_name, days_range, treatment_action, purpose, status, created_at)
        VALUES (?, ?, ?, ?, ?, 'upcoming', NOW())
    ");

    foreach ($healthTemplates as $h) {
        $insertHealth->execute([
            $sow_id,
            $h['stage_name'],
            $h['days_range'],
            $h['treatment_action'],
            $h['purpose']
        ]);
    }

    // -------------------------------------------
    // ✅ Done
    // -------------------------------------------
}
?>
