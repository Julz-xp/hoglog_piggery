<?php
/**
 * ---------------------------------------------------------
 * Gestation Roadmap Automation Generator
 * ---------------------------------------------------------
 * Triggered when pregnancy confirmation = Positive.
 * Generates feed & health roadmaps automatically
 * based on gestation timeline (114 days total).
 * ---------------------------------------------------------
 */

function generateGestationRoadmap($pdo, $sow_id, $ai_date)
{
    // 🗓 Convert to date object
    $startDate = new DateTime($ai_date);

    /**
     * ------------------------------------------
     * 🐖 FEED ROADMAP TEMPLATE
     * ------------------------------------------
     */
    $feedStages = [
        [
            'stage_name' => 'Early Gestation',
            'duration_days' => '1–30 days',
            'feed_type' => 'Gestation Feed (14–16% CP, moderate energy)',
            'daily_feed' => 2.2,
            'purpose' => 'Support embryo implantation and reduce embryo loss. Avoid overfeeding.'
        ],
        [
            'stage_name' => 'Mid Gestation',
            'duration_days' => '31–80 days',
            'feed_type' => 'Gestation Feed (14–16% CP)',
            'daily_feed' => 2.4,
            'purpose' => 'Maintain BCS 3.0; support fetus and uterus development.'
        ],
        [
            'stage_name' => 'Late Gestation',
            'duration_days' => '81–110 days',
            'feed_type' => 'Gestation / Transition Feed (14–16% CP, higher energy)',
            'daily_feed' => 2.8,
            'purpose' => 'Support rapid fetal growth and prepare mammary glands.'
        ],
        [
            'stage_name' => 'Pre-Farrowing',
            'duration_days' => '111–114 days',
            'feed_type' => 'Transition / Pre-Farrowing Feed',
            'daily_feed' => 3.0,
            'purpose' => 'Prepare sow’s digestion for lactation. Split into 2–3 meals/day.'
        ],
        [
            'stage_name' => 'Farrowing Day',
            'duration_days' => 'Day 115–116',
            'feed_type' => 'Minimal Feed (1–1.5 kg)',
            'daily_feed' => 1.0,
            'purpose' => 'Avoid constipation and farrowing complications.'
        ]
    ];

    /**
     * ------------------------------------------
     * 💉 HEALTH ROADMAP TEMPLATE
     * ------------------------------------------
     */
    $healthStages = [
        [
            'stage_name' => 'Early Gestation',
            'day_range' => '1–30 days',
            'treatment_action' => 'Avoid vaccination; provide Vitamin ADE + Selenium; Deworm if not done pre-breeding.',
            'purpose' => 'Support embryo implantation and maintain hormone balance.'
        ],
        [
            'stage_name' => 'Mid Gestation',
            'day_range' => '31–80 days',
            'treatment_action' => 'Iron + Multivitamins (optional), weight and BCS monitoring, sanitation check.',
            'purpose' => 'Maintain proper body condition and prevent urinary infections.'
        ],
        [
            'stage_name' => 'Late Gestation',
            'day_range' => '81–100 days',
            'treatment_action' => 'Erysipelas booster (optional), Vitamin ADE/B-Complex injection, Deworming (final treatment).',
            'purpose' => 'Reinforce immunity and prepare for farrowing.'
        ],
        [
            'stage_name' => 'Pre-Farrowing Preparation',
            'day_range' => '101–114 days',
            'treatment_action' => 'E. coli + Clostridium vaccine, Vitamin E + Selenium booster, reduce feed 1–2 days before farrowing.',
            'purpose' => 'Protect piglets via colostrum; improve milk quality.'
        ],
        [
            'stage_name' => 'Farrowing Day',
            'day_range' => 'Day 115–116',
            'treatment_action' => 'Monitor farrowing; no injections.',
            'purpose' => 'Ensure safe and natural farrowing.'
        ]
    ];

    /**
     * ------------------------------------------
     * 🧠 INSERT INTO DATABASE
     * ------------------------------------------
     */
    // 🔸 FEED ROADMAP INSERTION
    $feedStmt = $pdo->prepare("
        INSERT INTO gestation_feed_roadmap 
        (sow_id, stage_name, duration_days, feed_type, daily_feed, purpose, status, created_at)
        VALUES (?, ?, ?, ?, ?, ?, 'upcoming', NOW())
    ");
    foreach ($feedStages as $f) {
        $feedStmt->execute([
            $sow_id,
            $f['stage_name'],
            $f['duration_days'],
            $f['feed_type'],
            $f['daily_feed'],
            $f['purpose']
        ]);
    }

    // 🔸 HEALTH ROADMAP INSERTION
    $healthStmt = $pdo->prepare("
        INSERT INTO gestation_health_roadmap 
        (sow_id, stage_name, day_range, treatment_action, purpose, status, created_at)
        VALUES (?, ?, ?, ?, ?, 'upcoming', NOW())
    ");
    foreach ($healthStages as $h) {
        $healthStmt->execute([
            $sow_id,
            $h['stage_name'],
            $h['day_range'],
            $h['treatment_action'],
            $h['purpose']
        ]);
    }
}
?>
