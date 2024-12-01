<?php
$servername = "localhost"; 
$username = "root";
$password = "";
$dbname = "flood_ping";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql_new_alerts = "SELECT * FROM sensor_data WHERE status = 'NEW' ORDER BY id DESC, timestamp DESC;";
$result_new_alerts = $conn->query($sql_new_alerts);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flood Alerts</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
   
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }
        .trending-up {
            color: #EA3323;
        }
        .trending-down {
            color: #0BA4D7;
        }
        .stable {
            color: #808080;
        }
        button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 80%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover,
        .close:focus {
            color: black;
        }
        .clicked {
        background-color: #444; 
        color: #fff;
    }
    </style>
</head>
<body>
<?php
// Database configuration
$servername = "localhost";  // Change if not on localhost
$username = "root";
$password = "";
$dbname = "flood_ping";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flood Alerts</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
    <p>NEW FLOOD ALERTS</p>
    <?php
    // Fetch new flood alerts
    $sql_new_alerts = "SELECT * FROM sensor_data WHERE status = 'NEW' ORDER BY id DESC, timestamp DESC;";
    $result_new_alerts = $conn->query($sql_new_alerts);

    if ($result_new_alerts->num_rows > 0) {
        echo '<table>';
        echo '<thead>
                <tr>
                    <th>Flood Alert ID</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Height</th>
                    <th>Height Rate</th>
                    <th>Flow</th>
                    <th>Water Level</th>
                    <th>Action</th>
                </tr>
              </thead>';
        echo '<tbody>';

        $rows = $result_new_alerts->fetch_all(MYSQLI_ASSOC);
        $defaultMeter = 1.0;

        for ($i = 0; $i < count($rows); $i++) {
            $id = $rows[$i]['id'];
            $timestamp = $rows[$i]['timestamp'];
            $meters = $rows[$i]['meters'];
            $rate = $rows[$i]['rate'];
            $alert_level = $rows[$i]['alert_level'];
            $status = $rows[$i]['status'];
            $disabled = $i > 0 ? 'disabled' : '';
            $date = date("m/d/Y", strtotime($timestamp));
            $time = date("g:i:s A", strtotime($timestamp));

            $previousMeters = $i > 0 ? $rows[$i - 1]['meters'] : $defaultMeter;

    if ($meters > $previousMeters) {
        $waterflows[$id] = "RISING";
    } elseif ($meters < $previousMeters) {
        $waterflows[$id] = "SUBSIDING";
    } else {
        $waterflows[$id] = "STABLE";
    }

            $nextMeters = $rows[$i + 1]['meters'] ?? $defaultMeter;
            $flow = ($meters > $nextMeters) 
                ? '<span class="material-symbols-rounded trending-up">trending_up</span>' 
                : (($meters < $nextMeters) 
                    ? '<span class="material-symbols-rounded trending-down">trending_down</span>' 
                    : '<span class="material-symbols-rounded stable">stable</span>');

            $alertMapping = [
                "NORMAL LEVEL" => "NORMAL",
                "LOW LEVEL" => "LOW",
                "MEDIUM LEVEL" => "MODERATE",
                "CRITICAL LEVEL" => "CRITICAL"
            ];
            $mappedAlertLevel = isset($alertMapping[$alert_level]) ? $alertMapping[$alert_level] : $alert_level;

            echo "<tr>
                    <td>{$id}</td>
                    <td>{$date}</td>
                    <td>{$time}</td>
                    <td>{$status}</td>
                    <td>{$meters} m</td>
                    <td>{$rate} m/min</td>
                    <td>{$flow}</td>
                    <td>{$mappedAlertLevel}</td>
                    <td><button onclick='openModal()' {$disabled}>REVIEW ALERT</button></td>
                  </tr>";
        }

        echo '</tbody>';
        echo '</table>';
    } else {
        echo "<p>No data available.</p>";
    }
    ?>

    <p>RECENT VERIFIED FLOOD ALERTS</p>
    <?php 
    // Fetch verified flood alerts
    $sql_verified_alerts = "SELECT * FROM flood_alerts WHERE alert_status = 'Verified'";
    $result_verified_alerts = $conn->query($sql_verified_alerts);

    echo '<table border="1">';
    echo '<thead>
            <tr>
                <th>Flood Alert ID</th>
                <th>Date</th>
                <th>Time</th>
                <th>Water Level</th>
                <th>Flow</th>
                <th>Height</th>
                <th>Height Rate</th>
                <th>SMS Status</th>
                <th>SMS Status Reason</th>
                <th>Action</th>
            </tr>
          </thead>';
    echo '<tbody>';

    if ($result_verified_alerts->num_rows > 0) {
        while ($row = $result_verified_alerts->fetch_assoc()) {
            echo '<tr>
                    <td>' . htmlspecialchars($row["flood_alert_id"]) . '</td>
                    <td>' . htmlspecialchars($row["date"]) . '</td>
                    <td>' . htmlspecialchars($row["time"]) . '</td>
                    <td>' . htmlspecialchars($row["water_level"]) . '</td>
                    <td>' . htmlspecialchars($row["flow"]) . '</td>
                    <td>' . htmlspecialchars($row["height"]) . '</td>
                    <td>' . htmlspecialchars($row["height_rate"]) . '</td>
                    <td>' . htmlspecialchars($row["sms_status"]) . '</td>
                    <td>' . htmlspecialchars($row["sms_status_reason"]) . '</td>
                    <td><button>VIEW</button></td>
                  </tr>';
        }
    } else {
        echo '<tr><td colspan="10" style="text-align: center;">No records found</td></tr>';
    }

    echo '</tbody>';
    echo '</table>';

    // Close the database connection
    $conn->close();
    ?>
</body>
</html>










<!-- Modal t_t -->
<div id="alertModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <p>SUMMARY</p>
        <table>
            <thead>
                <tr>
                    <th>Flood Alert ID</th>
                    <th>Flood Alert Status</th>
                    <th>SMS Status</th>
                    <th>SMS Status Reason</th>
                </tr>
            </thead>
            <tbody id="summaryTableBody">
                <?php
                foreach ($rows as $row) {
                    $id = $row['id'];
                    echo "<tr id='row_{$id}'>
                            <td>{$id}</td>
                            <td id='status_{$id}'></td>
                            <td id='sms_{$id}'></td>
                            <td id='sms_reason_{$id}'></td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
        <button onclick="closeModal()">Cancel</button>
        <button id="confirmBtn" onclick="confirmAction()" disabled>Confirm</button>


        <p>VERIFY THE FLOOD ALERT(S) BELOW:</p>
        <table>
            <thead>
                <tr>
                    <th>Flood Alert ID</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Height</th>
                    <th>Height Rate</th>
                    <th>Flow</th>
                    <th>Water Level</th>
                    <th>Mark As:</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($rows as $index => $row) {
                    $id = $row['id'];
                    $timestamp = $row['timestamp'];
                    $meters = $row['meters'];
                    $rate = $row['rate'];
                    $alert_level = $row['alert_level'];
                    $status = $row['status'];
                    $date = date("m/d/Y", strtotime($timestamp));
                    $time = date("g:i:s A", strtotime($timestamp));

                    $flow = ($index == 0)
                        ? (($meters > $defaultMeter) 
                            ? '<span class="material-symbols-rounded trending-up">trending_up</span>' 
                            : (($meters < $defaultMeter) 
                                ? '<span class="material-symbols-rounded trending-down">trending_down</span>' 
                                : '<span class="material-symbols-rounded stable">stable</span>'))
                        : (($meters > ($rows[$index + 1]['meters'] ?? $defaultMeter)) 
                            ? '<span class="material-symbols-rounded trending-up">trending_up</span>' 
                            : (($meters < ($rows[$index + 1]['meters'] ?? $defaultMeter)) 
                                ? '<span class="material-symbols-rounded trending-down">trending_down</span>' 
                                : '<span class="material-symbols-rounded stable">stable</span>'));

                    $alertMapping = [
                        "NORMAL LEVEL" => "NORMAL",
                        "LOW LEVEL" => "LOW",
                        "MEDIUM LEVEL" => "MODERATE",
                        "CRITICAL LEVEL" => "CRITICAL"
                    ];
                    $mappedAlertLevel = $alertMapping[$alert_level] ?? $alert_level;

                    echo "<tr>
                            <td>{$id}</td>
                            <td>{$date}</td>
                            <td>{$time}</td>
                            <td>{$status}</td>
                            <td>{$meters} m</td>
                            <td>{$rate} m/min</td>
                            <td>{$flow}</td>
                            <td>{$mappedAlertLevel}</td>
                            <td>
                                <button id='falseAlarmBtn_{$id}' onclick='toggleFalseAlarm({$id})'>False Alarm</button>
                                <button id='verifyBtn_{$id}'onclick='verifyAlert({$id}, \"{$mappedAlertLevel}\")'data-mapped-alert-level='{$mappedAlertLevel}'>Verified</button>

                            </td>
                          </tr>";
                }
                $mostRecentAlertId = isset($rows[0]) ? $rows[0]['id'] : null;
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    const modal = document.getElementById('alertModal');
    const confirmButton = document.getElementById('confirmBtn');

    function openModal() {
        modal.style.display = 'flex';
    }

    function closeModal() {
        const buttons = document.querySelectorAll('button');
        buttons.forEach(button => {
            button.classList.remove('clicked');
        });

        const statusCells = document.querySelectorAll('[id^="status_"]');
        const smsStatusCells = document.querySelectorAll('[id^="sms_"]');
        const smsReasonCells = document.querySelectorAll('[id^="sms_reason_"]');

        statusCells.forEach(cell => cell.innerText = '');
        smsStatusCells.forEach(cell => cell.innerText = '');
        smsReasonCells.forEach(cell => cell.innerText = '');

        modal.style.display = 'none';
        confirmButton.disabled = true; 
    }

    function toggleFalseAlarm(id) {
        const falseAlarmButton = document.getElementById('falseAlarmBtn_' + id);
        const verifyButton = document.getElementById('verifyBtn_' + id);  

        if (!falseAlarmButton.classList.contains('clicked')) {
            falseAlarmButton.classList.add('clicked');
            verifyButton.classList.remove('clicked'); 
            updateStatus(id, 'False Alarm', 'No SMS', 'False Alarm'); 
        } else {
            falseAlarmButton.classList.remove('clicked');
            updateStatus(id, '', '', '');
        }

        checkConfirmButtonState(); 
    }

    function updateStatus(id, status, smsStatus, smsReason) {
        const statusCell = document.getElementById('status_' + id);
        const smsStatusCell = document.getElementById('sms_' + id);
        const smsReasonCell = document.getElementById('sms_reason_' + id);

        statusCell.innerText = status;
        smsStatusCell.innerText = smsStatus;
        smsReasonCell.innerText = smsReason;

        checkConfirmButtonState(); 
    }

    const waterflows = <?php echo json_encode($waterflows); ?>;

    function verifyAlert(id) {
    const verifyButton = document.getElementById('verifyBtn_' + id);
    const falseAlarmButton = document.getElementById('falseAlarmBtn_' + id);
    const mappedAlertLevel = verifyButton.getAttribute('data-mapped-alert-level');    
    const mostRecentAlertId = <?php echo json_encode($mostRecentAlertId); ?>;
    const waterflow = waterflows[id];



    if (!verifyButton.classList.contains('clicked')) {
        verifyButton.classList.add('clicked');
        falseAlarmButton.classList.remove('clicked');

        if (waterflow === 'SUBSIDING') {
            // Handle subsiding case
            updateStatus(id, 'Verified', 'No SMS', 'Not Required');
        } 
        
        else if ((mappedAlertLevel === 'LOW' || mappedAlertLevel === 'MODERATE') && waterflow === 'RISING' && id !== mostRecentAlertId) {
            updateStatus(id, 'Verified', 'No SMS', 'Overtaken');
        }
            
        else if ((mappedAlertLevel === 'LOW' || mappedAlertLevel === 'MODERATE') && waterflow === 'RISING' && id == mostRecentAlertId) {
            updateStatus(id, 'Verified', 'SMS Sent', 'Required');
        } 
        
        else if (mappedAlertLevel === 'CRITICAL') {
            updateStatus(id, 'Verified', 'No SMS', 'Not Required');
        }         
        
        else {
            updateStatus(id, 'Verified', '', '');
        }

    } else {
        verifyButton.classList.remove('clicked');
        updateStatus(id, '', '', '');


}}


    function checkConfirmButtonState() {
        const statusCells = document.querySelectorAll('[id^="status_"]');
        const smsStatusCells = document.querySelectorAll('[id^="sms_"]');
        const smsReasonCells = document.querySelectorAll('[id^="sms_reason_"]');
        const allFilled = [...statusCells, ...smsStatusCells, ...smsReasonCells].every(cell => cell.innerText.trim() !== '');
        confirmButton.disabled = !allFilled;
    }
</script>












</body>
</html>