<?php
// ================================================
// MODULE: Farm-Wide Feed Summary (Placeholder)
// FILE: modules/farm_wide_feed/wide_feed.php
// AUTHOR: Michael James I. Evallar
// DESCRIPTION: Displays farm-wide feed overview (Batch, Sow, Piglet)
// ================================================

// Include database connection
require_once __DIR__ . '/../../config/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farm-Wide Feed Summary | HogLog</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f9fafb;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .header-bar {
            background-color: #4b7bec;
            color: white;
            padding: 1rem;
            border-radius: 10px;
        }
        .summary-icon {
            font-size: 2rem;
            opacity: 0.8;
        }
    </style>
</head>

<body>
<div class="container py-4">

    <!-- 🐖 Header -->
    <div class="header-bar mb-4 text-center">
        <h3 class="mb-0"><i class="bi bi-bar-chart-fill"></i> Farm-Wide Feed Summary</h3>
        <small>Centralized view of total feed consumption across the farm</small>
    </div>

    <!-- 🌾 Summary Cards -->
    <div class="row g-3">

        <!-- Batch Feed -->
        <div class="col-md-4">
            <div class="card text-center p-3">
                <div class="summary-icon text-primary mb-2">
                    <i class="bi bi-box-seam"></i>
                </div>
                <h5>Batch Feed</h5>
                <p class="text-muted mb-1">Total Feed Used (kg): <strong>0.00</strong></p>
                <p class="text-muted">Total Feed Cost (₱): <strong>0.00</strong></p>
            </div>
        </div>

        <!-- Sow Feed -->
        <div class="col-md-4">
            <div class="card text-center p-3">
                <div class="summary-icon text-success mb-2">
                    <i class="bi bi-piggy-bank-fill"></i>
                </div>
                <h5>Sow Feed</h5>
                <p class="text-muted mb-1">Total Feed Used (kg): <strong>0.00</strong></p>
                <p class="text-muted">Total Feed Cost (₱): <strong>0.00</strong></p>
            </div>
        </div>

        <!-- Piglet Feed -->
        <div class="col-md-4">
            <div class="card text-center p-3">
                <div class="summary-icon text-warning mb-2">
                    <i class="bi bi-droplet-fill"></i>
                </div>
                <h5>Piglet Feed</h5>
                <p class="text-muted mb-1">Total Feed Used (kg): <strong>0.00</strong></p>
                <p class="text-muted">Total Feed Cost (₱): <strong>0.00</strong></p>
            </div>
        </div>

    </div>

    <!-- 📈 Chart Placeholder -->
    <div class="card mt-4 p-4 text-center">
        <h5 class="mb-3"><i class="bi bi-pie-chart-fill"></i> Feed Distribution Overview</h5>
        <p class="text-muted">Chart will be displayed here later...</p>
    </div>

</div>

</body>
</html>
