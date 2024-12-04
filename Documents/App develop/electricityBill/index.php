<?php
require_once 'function.php';

$power = $rate = null;
$tableData = [];
$voltageCondition = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $voltage = $_POST['voltage'];
    $current = $_POST['current'];
    $currentRate = $_POST['current_rate'];

    // Calculate Power
    $power = calculatePower($voltage, $current); // Power in kW
    
    // Determine Voltage Level Condition
    $voltageCondition = determineVoltageLevel($voltage);

    // Calculate Rate (RM) per hour
    $rate = calculateRateWithTariff($power, $currentRate);

    // Generate Table Data for 24 hours
    $tableData = calculateTableData($power, $currentRate);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electricity Bill Calculator</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Electricity Bill Calculator</h1>
        <form method="POST" action="">
            <div class="form-group">
                <label for="voltage">Voltage (V):</label>
                <input type="number" class="form-control" id="voltage" name="voltage" required>
            </div>
            <div class="form-group">
                <label for="current">Current (A):</label>
                <input type="number" class="form-control" id="current" name="current" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="current_rate">Current Rate (sen/kWh):</label>
                <input type="number" class="form-control" id="current_rate" name="current_rate" step="0.01" required>
            </div>
            <button type="submit" class="btn btn-primary">Calculate</button>
        </form>

        <?php if (!empty($tableData)) : ?>
            <!-- Display Results Above the Table -->
            <div class="mt-5">
                <h4>Results</h4>
                <p><strong>Power (kW):</strong> <?= round($power, 5) ?></p>
                <p><strong>Rate (RM):</strong> <?= round($rate, 3) ?></p>
                <p><strong>Voltage Level:</strong> <?= $voltageCondition ?></p>
            </div>

            <h2 class="mt-5">Energy Consumption Table</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Hour</th>
                        <th>Energy (kWh)</th>
                        <th>Total (RM)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tableData as $hour => $data) : ?>
                        <tr>
                            <td><?= $hour + 1 ?></td>
                            <td><?= $data['energy'] ?></td>
                            <td><?= $data['total'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
