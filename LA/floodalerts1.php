<?php
include_once('../db/db_conn.php');
include_once('../sidebar.php'); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flood Alerts</title>
    <link rel="stylesheet" href="../styles.css"> 
</head>
<body>
    <div class="content">
        <h1>Flood Alerts</h1>
        <div class="new-flood-alerts">
            <h2>New Flood Alerts</h2>
            <form method="POST" action="send_alert.php">
                <table>
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
                    <tr>
                        <td><input type="text" name="flood_id" readonly value="0000002"></td>
                        <td><input type="date" name="date" required></td>
                        <td><input type="time" name="time" required></td>
                        <td><input type="text" name="height" required></td>
                        <td><input type="text" name="speed" required></td>
                        <td><input type="text" name="flow" required></td>
                        <td>
                            <select name="water_level">
                                <option value="Normal">Normal</option>
                                <option value="Low">Low</option>
                                <option value="Moderate">Moderate</option>
                                <option value="Critical">Critical</option>
                            </select>
                        </td>
                        <td>
                            <select name="status">
                                <option value="New">New</option>
                                <option value="Updated">Updated</option>
                            </select>
                        </td>
                        <td><button type="submit" name="send_sms">SEND</button></td>
                    </tr>
                </table>
            </form>
        </div>

        <!-- Sent Flood Alerts Section -->
        <div class="sent-flood-alerts">
            <h2>Sent Flood Alerts Today</h2>
            <table>
                <thead>
                    <tr>
                        <th>Flood ID</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Height</th>
                        <th>Speed</th>
                        <th>Flow</th>
                        <th>Water Level</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM flood_events ORDER BY date DESC");
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['event_id']}</td>
                            <td>{$row['date']}</td>
                            <td>{$row['time']}</td>
                            <td>{$row['sms_status']}</td>
                            <td>{$row['water_level']}</td>
                            <td>{$row['flow_rate']}</td>
                            <td><a href='view_flood.php?id={$row['event_id']}'>VIEW</a></td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Information Cards -->
        <div class="info-cards">
            <div class="card">
                <h3>Water Level</h3>
                <p>Normal: 9m | No Flood</p>
                <p>Low: 10m | Self-Evacuation</p>
                <p>Moderate: 13m | Force Evacuation</p>
                <p>Critical: 15m | Stay in Evacuation Sites</p>
            </div>
            <div class="card">
                <h3>Evacuation Sites</h3>
                <p>Low: Remarville Court</p>
                <p>Moderate: Bagbag Elementary School</p>
                <p>Critical: Goodwill Elementary School</p>
            </div>
        </div>
    </div>
</body>
</html>
