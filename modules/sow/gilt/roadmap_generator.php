<?php
function generateGiltRoadmaps($pdo, $sow_id, $date_of_birth)
{
    // 🧮 Optional: You can calculate current age in days if needed later
    $dob = new DateTime($date_of_birth);
    $today = new DateTime();
    $age_in_days = $today->diff($dob)->days;

    /* --------------------------------
     🐖 FEED ROADMAP TEMPLATE
    --------------------------------- */
    $feedStages = [
        [
            'stage_name' => 'Development / Growing Gilt',
            'start_age_days' => 90,
            'end_age_days' => 150,
            'feed_type' => 'Gilt Developer Feed (16–18% CP)',
            'daily_feed' => 2.4,
            'purpose' => 'Promotes muscle and bone development; avoid over-fat gilts.'
        ],
        [
            'stage_name' => 'Pre-Breeding / Conditioning',
            'start_age_days' => 150,
            'end_age_days' => 180,
            'feed_type' => 'Gilt Developer / Breeder Feed (16% CP)',
            'daily_feed' => 2.8,
            'purpose' => 'Maintain ideal body condition (BCS 3.0); prepare for heat detection.'
        ],
        [
            'stage_name' => 'Heat Detection & Selection',
            'start_age_days' => 180,
            'end_age_days' => 200,
            'feed_type' => 'Breeder Feed',
            'daily_feed' => 3.0,
            'purpose' => 'Stimulate estrus cycle; check for regular heat signs.'
        ],
        [
            'stage_name' => 'Flushing Period (Before Breeding)',
            'start_age_days' => 200,
            'end_age_days' => 210,
            'feed_type' => 'Flushing Feed (High-energy Breeder Feed)',
            'daily_feed' => 3.5,
            'purpose' => 'Boost ovulation rate and conception success.'
        ],
        [
            'stage_name' => 'Mating / Breeding Stage',
            'start_age_days' => 210,
            'end_age_days' => 213,
            'feed_type' => 'Breeder Feed',
            'daily_feed' => 3.0,
            'purpose' => 'Maintain feed to prevent stress and improve conception rate.'
        ],
        [
            'stage_name' => 'Early Gestation (Post-breeding)',
            'start_age_days' => 213,
            'end_age_days' => 243,
            'feed_type' => 'Gestation Feed (14–16% CP)',
            'daily_feed' => 2.4,
            'purpose' => 'Support embryo implantation and stable pregnancy.'
        ]
    ];

    /* --------------------------------
     💉 HEALTH ROADMAP TEMPLATE
    --------------------------------- */
    $healthStages = [
        [
            'stage_name' => 'Entry / Isolation Period',
            'start_age_days' => 90,
            'end_age_days' => 120,
            'treatment_action' => 'Quarantine 30 days, Deworming #1, Iron + Multivitamins',
            'purpose' => 'Prevent disease introduction; strengthen immunity.'
        ],
        [
            'stage_name' => 'Adaptation / Growing Phase',
            'start_age_days' => 120,
            'end_age_days' => 150,
            'treatment_action' => 'Myco + PCV2 vaccines; E. coli & Clostridium (optional)',
            'purpose' => 'Protects against pneumonia, wasting, and gut infections.'
        ],
        [
            'stage_name' => 'Pre-Breeding Health Boost',
            'start_age_days' => 150,
            'end_age_days' => 180,
            'treatment_action' => 'Deworming #2, Vitamin ADE, Parvo + Lepto vaccines',
            'purpose' => 'Prevent reproductive losses; prepare reproductive health.'
        ],
        [
            'stage_name' => 'Flushing & Breeding Preparation',
            'start_age_days' => 180,
            'end_age_days' => 210,
            'treatment_action' => 'Erysipelas vaccine, PPV + Lepto booster, minerals',
            'purpose' => 'Enhance fertility and protection before mating.'
        ],
        [
            'stage_name' => 'Breeding Time',
            'start_age_days' => 210,
            'end_age_days' => 213,
            'treatment_action' => 'No vaccination (reduce stress)',
            'purpose' => 'Ensure conception success.'
        ]
    ];

    /* --------------------------------
     🚀 INSERT INTO DATABASE
    --------------------------------- */

    // FEED ROADMAP
    $feedSQL = $pdo->prepare("
        INSERT INTO gilt_feed_roadmap 
        (sow_id, stage_name, start_age_days, end_age_days, feed_type, daily_feed, purpose, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, 'upcoming')
    ");
    foreach ($feedStages as $f) {
        $feedSQL->execute([
            $sow_id, 
            $f['stage_name'], 
            $f['start_age_days'], 
            $f['end_age_days'], 
            $f['feed_type'], 
            $f['daily_feed'], 
            $f['purpose']
        ]);
    }

    // HEALTH ROADMAP
    $healthSQL = $pdo->prepare("
        INSERT INTO gilt_health_roadmap 
        (sow_id, stage_name, start_age_days, end_age_days, treatment_action, purpose, status)
        VALUES (?, ?, ?, ?, ?, ?, 'upcoming')
    ");
    foreach ($healthStages as $h) {
        $healthSQL->execute([
            $sow_id, 
            $h['stage_name'], 
            $h['start_age_days'], 
            $h['end_age_days'], 
            $h['treatment_action'], 
            $h['purpose']
        ]);
    }
}
?>
