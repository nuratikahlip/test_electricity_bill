<?php

// Calculate power in kW
function calculatePower($voltage, $current) {
    return ($voltage * $current) / 1000; // Power in kW
}

// Determine the voltage level (Extra Low, Low, Medium, High, Extra High)
function determineVoltageLevel($voltage) {
    if ($voltage <= 50) {
        return "Extra Low Voltage";
    } elseif ($voltage <= 1000) {
        return "Low Voltage";
    } elseif ($voltage <= 50000) {
        return "Medium Voltage";
    } elseif ($voltage <= 230000) {
        return "High Voltage";
    } else {
        return "Extra High Voltage";
    }
}

// Calculate the rate based on the tariff
function calculateRateWithTariff($power, $currentRate) {
    $energyPerMonth = $power * 720; // Assume 720 hours in a month (30 days * 24 hours)
    $totalCost = 0;

    // Tariff calculation based on energy consumed
    if ($energyPerMonth <= 200) {
        $totalCost = $energyPerMonth * 21.80; // 21.80 sen/kWh
    } elseif ($energyPerMonth <= 300) {
        $totalCost = (200 * 21.80) + (($energyPerMonth - 200) * 33.40);
    } elseif ($energyPerMonth <= 600) {
        $totalCost = (200 * 21.80) + (100 * 33.40) + (($energyPerMonth - 300) * 51.60);
    } elseif ($energyPerMonth <= 900) {
        $totalCost = (200 * 21.80) + (100 * 33.40) + (300 * 51.60) + (($energyPerMonth - 600) * 54.60);
    } else {
        $totalCost = (200 * 21.80) + (100 * 33.40) + (300 * 51.60) + (300 * 54.60) + (($energyPerMonth - 900) * 57.10);
    }

    // Minimum charge of RM3.00
    return max($totalCost / 100, 3.00); // Convert sen to RM
}

// Calculate the table data for 24 hours
function calculateTableData($power, $currentRate) {
    $data = [];
    for ($hour = 1; $hour <= 24; $hour++) {
        $energy = $power * $hour; // Energy for this hour in kWh
        $totalCost = $energy * ($currentRate / 100); // Total cost in RM
        $data[] = [
            'energy' => round($energy, 5),
            'total' => round($totalCost, 2)
        ];
    }
    return $data;
}
