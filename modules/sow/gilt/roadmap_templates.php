<?php
/**
 * 🐖 HogLog Piggery System
 * -------------------------------------------------------------
 * File: roadmap_templates.php
 * Description:
 *   Fixed roadmap templates for Gilt Feed Consumption
 *   and Gilt Health Treatment Workflow.
 * -------------------------------------------------------------
 * NOTE:
 *   These values act as the master reference.
 *   Do NOT modify unless the farm protocol changes.
 * -------------------------------------------------------------
 */

$gilt_feed_template = [
    [
        'stage_name' => 'Development / Growing Gilt',
        'start_day' => 0,
        'end_day' => 60,
        'duration_days' => '0–60 days',
        'age_range' => '90–150 days old',
        'feed_type' => 'Gilt Developer Feed (16–18% CP)',
        'daily_feed' => 2.4,
        'purpose' => 'Promotes muscle and bone development; avoid over-fat gilts.'
    ],
    [
        'stage_name' => 'Pre-Breeding / Conditioning',
        'start_day' => 60,
        'end_day' => 90,
        'duration_days' => '60–90 days',
        'age_range' => '150–180 days old',
        'feed_type' => 'Gilt Developer / Breeder Feed (16% CP)',
        'daily_feed' => 2.8,
        'purpose' => 'Maintain ideal body condition (BCS 3.0); prepare for heat detection.'
    ],
    [
        'stage_name' => 'Heat Detection & Selection',
        'start_day' => 90,
        'end_day' => 110,
        'duration_days' => '90–110 days',
        'age_range' => '180–200 days old',
        'feed_type' => 'Breeder Feed',
        'daily_feed' => 3.0,
        'purpose' => 'Stimulate estrus cycle; check for regular heat signs.'
    ],
    [
        'stage_name' => 'Flushing Period (Before Breeding)',
        'start_day' => 110,
        'end_day' => 120,
        'duration_days' => '7–10 days',
        'age_range' => 'Around 200 days old',
        'feed_type' => 'Flushing Feed (High-energy Breeder Feed)',
        'daily_feed' => 3.5,
        'purpose' => 'Boost ovulation rate and conception success.'
    ],
    [
        'stage_name' => 'Mating / Breeding Stage',
        'start_day' => 120,
        'end_day' => 123,
        'duration_days' => '1–3 days',
        'age_range' => 'Around 210 days old',
        'feed_type' => 'Breeder Feed',
        'daily_feed' => 3.0,
        'purpose' => 'Maintain feed to prevent stress and improve conception rate.'
    ],
    [
        'stage_name' => 'Early Gestation (Post-breeding)',
        'start_day' => 123,
        'end_day' => 153,
        'duration_days' => '1–30 days',
        'age_range' => 'After 210 days old',
        'feed_type' => 'Gestation Feed (14–16% CP)',
        'daily_feed' => 2.4,
        'purpose' => 'Support early pregnancy and embryo development.'
    ]
];

$gilt_health_template = [
    [
        'stage_name' => 'Entry / Isolation Period',
        'start_day' => 0,
        'end_day' => 30,
        'age_range' => '90–120 days old',
        'treatment_action' => 'Quarantine (30 days); Deworming #1; Iron + Multivitamins (if needed)',
        'purpose' => 'Prevent disease introduction and strengthen immunity.'
    ],
    [
        'stage_name' => 'Adaptation / Growing Phase',
        'start_day' => 30,
        'end_day' => 60,
        'age_range' => '120–150 days old',
        'treatment_action' => 'Vaccination: Mycoplasma hyopneumoniae (Myco) & PCV2 (Circovirus); optional E. coli + Clostridium',
        'purpose' => 'Protect lungs and prevent wasting or gut infections.'
    ],
    [
        'stage_name' => 'Pre-Breeding Health Boost',
        'start_day' => 60,
        'end_day' => 90,
        'age_range' => '150–180 days old',
        'treatment_action' => 'Deworming #2; Vitamin ADE or B-complex; Parvovirus + Leptospirosis (PPV + Lepto)',
        'purpose' => 'Clean and prepare gilt for breeding; prevent reproductive losses.'
    ],
    [
        'stage_name' => 'Flushing & Breeding Preparation',
        'start_day' => 90,
        'end_day' => 120,
        'age_range' => '180–210 days old',
        'treatment_action' => 'Erysipelas Vaccine; Mineral & Vitamin Supplement; Booster for PPV + Lepto (if needed)',
        'purpose' => 'Protect against fever, arthritis, and enhance fertility before mating.'
    ],
    [
        'stage_name' => 'Breeding Time',
        'start_day' => 120,
        'end_day' => 123,
        'age_range' => 'Around 210 days old',
        'treatment_action' => 'No vaccination (avoid stress during breeding)',
        'purpose' => 'Ensure calmness and improve conception rate.'
    ]
];
?>

