<?php
include_once('../db/db_conn.php');
include_once('../sidebar.php');

$sql_flood_alerts = "
    SELECT fa.*, pm.message AS predefined_message
    FROM flood_alerts fa
    LEFT JOIN predefined_messages pm
    ON fa.water_level = pm.level
";
$result_flood_alerts = $conn->query($sql_flood_alerts);

if (!$result_flood_alerts) {
    die("Error fetching flood alerts: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
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
            margin-left: 50px;
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

        /* Modal styles */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none; 
            justify-content: center; 
            align-items: center; 
            z-index: 1000; 
            overflow: hidden; 
        }

        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            width: 50%;
            max-width: 600px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.3s ease-in-out;
            box-sizing: border-box; 
        }

        .modal-content h2 {
            color: #02476A;
            text-align: center;
        }

        .modal-content p {
            color: #0056b3;
            text-align: center;
        }

        .close-modal {
            float: right;
            cursor: pointer;
            font-size: 50px;
            color: black;
        }

        .modal-content div {
            margin-top: 20px;
        }

        .modal-content button {
            background-color: #0073AC;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 20px;
            display: block;
            margin-left: auto;
            margin-right: auto;
            cursor: pointer;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
</head>
<body>
    <main class="main-content">
        <div class="container">
            <h1>Flood Alerts</h1>

            <!-- Flood Alerts Section -->
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
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result_flood_alerts->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['flood_id']; ?></td>
                            <td><?php echo $row['date']; ?></td>
                            <td><?php echo $row['time']; ?></td>
                            <td><?php echo $row['height']; ?></td>
                            <td><?php echo $row['speed']; ?></td>
                            <td><?php echo $row['flow']; ?></td>
                            <td><?php echo $row['water_level']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td>
                                <button class="btn-view-message"
                                        data-message="<?php echo htmlspecialchars($row['predefined_message']); ?>"
                                        data-flood-id="<?php echo $row['flood_id']; ?>"
                                        data-date="<?php echo $row['date']; ?>"
                                        data-time="<?php echo $row['time']; ?>"
                                        data-water-level="<?php echo $row['water_level']; ?>">
                                    Send
                                </button>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Modal HTML -->
    <div id="smsModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal()">×</span>
            <h2>Send SMS Alert to the registered residents</h2>
            <p>(Magpadala ng mensahe sa mga residente)</p>
            <div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span><strong>Flood ID:</strong> <span id="refId"></span></span>
                    <span><strong>Date (Petsa):</strong> <span id="date"></span></span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span><strong>Water Level:</strong> <span id="waterLevel"></span></span>
                    <span><strong>Time (Oras):</strong> <span id="time"></span></span>
                </div>
                <div>
                    <strong>Message Content (Mensahe):</strong>
                    <p id="messageContent" style="background-color: #f9f9f9; padding: 10px; border-radius: 5px;"></p>
                </div>
            </div>
            <form method="POST" action="send_sms.php">
                <input type="hidden" name="flood_id" id="hiddenFloodId">
                <input type="hidden" name="date" id="hiddenDate">
                <input type="hidden" name="time" id="hiddenTime">
                <input type="hidden" name="water_level" id="hiddenWaterLevel">
                <textarea name="message" id="hiddenMessage" style="display: none;"></textarea>
                <button type="submit">SEND SMS</button>
            </form>

        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#flood-alerts-table').DataTable({
                responsive: true
            });

            $(document).on('click', '.btn-view-message', function () {
                const floodId = $(this).data('flood-id');
                const date = $(this).data('date');
                const time = $(this).data('time');
                const waterLevel = $(this).data('water-level');
                const message = $(this).data('message');

                $('#refId').text(floodId);
                $('#date').text(date);
                $('#time').text(time);
                $('#waterLevel').text(waterLevel);
                $('#messageContent').text(message);

                // Populate the hidden inputs in the form
                $('#hiddenFloodId').val(floodId);
                $('#hiddenDate').val(date);
                $('#hiddenTime').val(time);
                $('#hiddenWaterLevel').val(waterLevel);
                $('#hiddenMessage').val(message);

                $('#smsModal').css("display", "flex").hide().fadeIn();
            });

        });

        function closeModal() {
            $('#smsModal').fadeOut();
        }
    </script>
</body>
</html>