<?php
/**
 * 🐖 HogLog Piggery System
 * -------------------------------------------------------------
 * File: roadmap_generator.php
 * Description:
 *  - Generates automatic Gilt Feed and Health Roadmaps
 *  - Called from add_sow.php when a new Gilt is added
 * -------------------------------------------------------------
 */

require_once __DIR__ . '/roadmap_templates.php'; // 🧩 Contains hardcoded templates

/**
 * Generates Feed and Health roadmaps for a newly added Gilt
 * 
 * @param PDO $pdo - active DB connection
 * @param int $sow_id - the newly inserted sow ID
 * @param string $birth_date - sow's date of birth (YYYY-MM-DD)
 */
function generateGiltRoadmaps($pdo, $sow_id, $birth_date) {
    global $gilt_feed_template, $gilt_health_template;

    // 🧮 Calculate age in days
    $birth = new DateTime($birth_date);
    $today = new DateTime();
    $age_in_days = $birth->diff($today)->days;

    // ---------------------------------------------------
    // FEED ROADMAP GENERATION
    // ---------------------------------------------------
    foreach ($gilt_feed_template as $stage) {
        $status = determineStageStatus($age_in_days, $stage['start_day'], $stage['end_day']);

        $stmt = $pdo->prepare("INSERT INTO gilt_feed_roadmap 
            (sow_id, stage_name, duration_days, age_range, feed_type, daily_feed, purpose, status)
            VALUES (?,?,?,?,?,?,?,?)");
        $stmt->execute([
            $sow_id,
            $stage['stage_name'],
            $stage['duration_days'],
            $stage['age_range'],
            $stage['feed_type'],
            $stage['daily_feed'],
            $stage['purpose'],
            $status
        ]);
    }

    // ---------------------------------------------------
    // HEALTH ROADMAP GENERATION
    // ---------------------------------------------------
    foreach ($gilt_health_template as $stage) {
        $status = determineStageStatus($age_in_days, $stage['start_day'], $stage['end_day']);

        $stmt = $pdo->prepare("INSERT INTO gilt_health_roadmap 
            (sow_id, stage_name, age_range, treatment_action, purpose, status)
            VALUES (?,?,?,?,?,?)");
        $stmt->execute([
            $sow_id,
            $stage['stage_name'],
            $stage['age_range'],
            $stage['treatment_action'],
            $stage['purpose'],
            $status
        ]);
    }
}

/**
 * Determines the stage status based on current age
 *
 * @param int $age_in_days - current age of the gilt
 * @param int $start_day - roadmap stage starting day
 * @param int $end_day - roadmap stage ending day
 * @return string - 'completed', 'current', or 'upcoming'
 */
function determineStageStatus($age_in_days, $start_day, $end_day) {
    if ($age_in_days > $end_day) return 'completed';
    if ($age_in_days >= $start_day && $age_in_days <= $end_day) return 'current';
    return 'upcoming';
}
?>
