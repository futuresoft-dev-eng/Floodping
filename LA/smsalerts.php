<?php
include_once('../db/db_conn.php');
include_once('../sidebar.php');

// Fetch Pending SMS Logs
$sql_pending_sms = "SELECT * FROM sms_logs_pending";
$result_pending_sms = $conn->query($sql_pending_sms);

// Fetch Sent SMS Logs
$sql_sent_sms = "SELECT * FROM sms_logs_sent";
$result_sent_sms = $conn->query($sql_sent_sms);

if (!$result_pending_sms || !$result_sent_sms) {
    die("Error fetching SMS logs: " . $conn->error);
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS Flood Alerts</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Rounded" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>

    <style>
        .main-content {
            margin-left: 100px; 
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
        

            <h1>SMS Alert Logs</h1>
            <!-- Pending SMS Logs Section -->
            <!-- SMS Alerts ni LA papunta sa registered residents  -->
            <div class="table-container">
                <h1>Pending Status</h1>
                <table id="pending-sms-table" class="display responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Sending ID</th>
                            <th>Date Sent</th>
                            <th>Recipients</th>
                            <th>Flood ID</th>
                            <th>Water Level</th>
                            <th>Sending Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result_pending_sms->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['sending_id']; ?></td>
                            <td><?php echo $row['date_sent']; ?></td>
                            <td><?php echo $row['recipients']; ?></td>
                            <td><?php echo $row['flood_id']; ?></td>
                            <td><?php echo $row['water_level']; ?></td>
                            <td><?php echo $row['sending_progress']; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <!-- Sent SMS Logs Section -->
            <div class="table-container">
                <h1>Sent status</h1>
                <table id="sent-sms-table" class="display responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Batch ID</th>
                            <th>Date Sent</th>
                            <th>Time Sent</th>
                            <th>Success Count</th>
                            <th>Failed Count</th>
                            <th>Recipients</th>
                            <th>Flood ID</th>
                            <th>Water Level</th>
                            <th>Credits Consumed</th>
                            <th>Sent By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result_sent_sms->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['sms_batch_id']; ?></td>
                            <td><?php echo $row['date_sent']; ?></td>
                            <td><?php echo $row['time_sent']; ?></td>
                            <td><?php echo $row['success_count']; ?></td>
                            <td><?php echo $row['failed_count']; ?></td>
                            <td><?php echo $row['recipients']; ?></td>
                            <td><?php echo $row['flood_id']; ?></td>
                            <td><?php echo $row['water_level']; ?></td>
                            <td><?php echo $row['credits_consumed']; ?></td>
                            <td><?php echo $row['sent_by']; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        // DataTabless
        $(document).ready(function () {
            $('#pending-sms-table').DataTable({
                responsive: true
            });
            $('#sent-sms-table').DataTable({
                responsive: true
            });
        });
    </script>
</body>
</html>
