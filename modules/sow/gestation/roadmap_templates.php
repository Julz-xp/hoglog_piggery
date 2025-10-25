<?php
require_once __DIR__ . '/../../../config/db.php';

/**
 * This file seeds (inserts) the default Gestation Feed and Health templates
 * into the database tables:
 * - gestation_feed_templates
 * - gestation_health_templates
 *
 * Run this file ONCE to initialize template data.
 */

// 🐷 1️⃣ FEED TEMPLATES
$feedTemplates = [
    ['Early Gestation', '1–30 days', 'Gestation Feed (14–16% CP, moderate energy)', 2.3, 'Support embryo implantation and reduce risk of embryo loss. Avoid overfeeding.'],
    ['Mid Gestation', '31–80 days', 'Gestation Feed', 2.5, 'Maintain body condition (BCS 3.0); develop fetus and uterus. Avoid excess fat.'],
    ['Late Gestation', '81–110 days', 'Gestation / Transition Feed (14–16% CP, slightly higher energy)', 3.0, 'Rapid fetal growth; prepare mammary glands for milk production.'],
    ['Pre-Farrowing', '111–114 days', 'Transition / Pre-Farrowing Feed (same as lactation feed, gradually increased)', 3.5, 'Prepare sow’s digestion for lactation diet. Split into 2–3 meals per day.'],
    ['Farrowing Day', '115–116 days', 'Minimal feed (1–1.5 kg, or skip feeding)', 1.0, 'Avoid constipation and farrowing complications. Provide clean water.'],
];

$stmt = $pdo->prepare("INSERT INTO gestation_feed_templates (stage_name, duration_days, feed_type, daily_feed, purpose) VALUES (?, ?, ?, ?, ?)");
foreach ($feedTemplates as $template) {
    $stmt->execute($template);
}

// 💉 2️⃣ HEALTH TEMPLATES
$healthTemplates = [
    ['Early Gestation', '1–30 days', 'Avoid vaccination; Vitamin & Mineral Supplement (A, D, E, Selenium); Deworming if not done pre-breeding', 'Support embryo development and minimize stress.'],
    ['Mid Gestation', '31–80 days', 'Iron + Multivitamins (optional); Monitor weight and water sanitation', 'Maintain proper body condition and prevent digestive problems.'],
    ['Late Gestation', '81–100 days', 'Erysipelas Booster (optional); Vitamin ADE/B-Complex; Deworming (Final Treatment)', 'Reinforce immunity and prevent parasite transfer to piglets.'],
    ['Pre-Farrowing Preparation', '101–114 days', 'Colibacillosis (E. coli + Clostridium) Vaccine; Vitamin E + Selenium Booster; Reduce feed 1–2 days before farrowing', 'Protect piglets via colostrum and prepare for farrowing.'],
    ['Farrowing Day', '115–116 days', 'Monitor farrowing only — no injections', 'Ensure clean environment and natural birthing.'],
];

$stmt2 = $pdo->prepare("INSERT INTO gestation_health_templates (stage_name, days_range, treatment_action, purpose) VALUES (?, ?, ?, ?)");
foreach ($healthTemplates as $template) {
    $stmt2->execute($template);
}

echo "✅ Gestation templates successfully inserted into the database!";
?>
