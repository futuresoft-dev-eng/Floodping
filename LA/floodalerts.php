<?php
include('../db/connection.php');
include_once('../sidebar.php');

// Fetch Flood Alerts
$sql_flood_alerts = "SELECT * FROM flood_alerts";
$result_flood_alerts = $conn->query($sql_flood_alerts);

if (!$result_flood_alerts) {
    die("Error fetching flood alerts: " . $conn->error);
}

// Predefined messages
$predefined_messages = [
    'LOW' => 'Water level is 10m. Low flood risk identified. Please consider self-evacuation for safety. Stay updated.',
    'MODERATE' => 'Water level reached 13m, moderate flood risk. Immediate evacuation recommended for your safety.',
    'CRITICAL' => 'Water level at 15m, critical flood risk. Remain in evacuation sites until further notice for safety.'
];
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flood Alerts</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Rounded" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>

    <style>
        .main-content {
            margin-left: 0; 
            padding: 20px;
        }

        .container {
            max-width: 100%;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .table-container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            border: 1px solid #e0e0e0;
            margin-top: 20px;
            margin-bottom: 50px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow-x: auto; 
        }

        h1 {
            text-align: center;
            color: #343a40;
            margin-bottom: 20px;
        }

        /* Responsive design tweaks */
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0; 
            }
            .table-container {
                padding: 10px;
            }
            h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <main class="main-content">
        <div class="container">
            <h1>Flood Alerts</h1>

            <!-- Flood Alerts Section -->
             <!-- Alerts from the sensor -->
            <div class="table-container">
                <h1>NEW FLOOD ALERTS</h1> 
                <table id="flood-alerts-table" class="display responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Flood ID</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Height</th>
                            <th>Speed</th>
                            <th>Flow</th>
                            <th>Water Level</th>
                            <th>Status</th>
                            <th>SMS Alert</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result_flood_alerts->fetch_assoc()) { 
                            $status = $row['status'];
                            $message = $predefined_messages[$status] ?? 'No predefined message available.';
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['flood_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['date']); ?></td>
                            <td><?php echo htmlspecialchars($row['time']); ?></td>
                            <td><?php echo htmlspecialchars($row['height']); ?></td>
                            <td><?php echo htmlspecialchars($row['speed']); ?></td>
                            <td><?php echo htmlspecialchars($row['flow']); ?></td>
                            <td><?php echo htmlspecialchars($row['water_level']); ?></td>
                            <td><?php echo htmlspecialchars($status); ?></td>
                            <td>
                                <form action="send_sms.php" method="POST">
                                    <input type="hidden" name="flood_id" value="<?php echo htmlspecialchars($row['flood_id']); ?>">
                                    <input type="hidden" name="message" value="<?php echo htmlspecialchars($message); ?>">
                                    <button type="submit">Send</button>
                                </form>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div> 
        </div>
    </main>

    <script>
        // DataTables
        $(document).ready(function () {
            $('#flood-alerts-table').DataTable({
                responsive: true
            });
        });
    </script>
</body>
</html>
