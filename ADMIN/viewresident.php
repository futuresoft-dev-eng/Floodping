<?php
include_once('../adminsidebar.php');
include_once('../db/connection.php');

if (isset($_GET['resident_id'])) {
    $resident_id = $_GET['resident_id'];

    // Fetch resident data
    $query = "SELECT * FROM residents WHERE resident_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $resident_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $resident = $result->fetch_assoc();
    } else {
        echo "<p>Resident not found.</p>";
        exit();
    }
} else {
    echo "<p>No resident ID specified.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Resident Details</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Symbols+Rounded">
    <style>
       .main-content {
            margin-left: 200px;
            padding: 20px;
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
            padding: 20px;
            font-family: Arial, sans-serif;
        }

        .header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            background-color: white;
            padding: 10px;
            border-radius: 8px;
            color: #02476A;
            gap: 15px;
        }

        .back-button {
            background-color: #0073AC;
            color: white;
            padding: 8px  20px;
            border-radius: 15%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .header h2 {
            margin: 0;
            color: #02476A;
            font-size: 18PX;
            font-weight: bold;
        }

        .profile-container {
            background-color: #E8F3F8;
            padding: 20px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
        }

        .profile-info, .profile-photo {
            flex: 1;
        }

        .profile-photo img {
            border-radius: 8px;
            width: 150px;
            height: 180px;
            margin-bottom: 10px;
        }

        .status-container {
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .status {
            background-color: #e0f3e9;
            padding: 8px 12px;
            border-radius: 5px;
            color: #4CAF50;
            font-weight: bold;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            color: white;
            cursor: pointer;
        }

        .btn-deactivate {
            background-color: #F44336;
        }

        .btn-update {
            background-color: #2196F3;
        }

    </style>
</head>
<body>


<div class="container">
    <div class="header">
        <span class="material-symbols-rounded back-button">arrow_back</span>
        <h2>RESIDENT DETAILS</h2>
    </div>
    <hr>
    <div class="profile-container">
        <div class="profile-info">
            <h3>Profile</h3>
            <div class="info-group">
                <div class="info-item">
                    <label>First Name</label>
                    <p><?php echo htmlspecialchars($resident['first_name']); ?></p>
                </div>
                <div class="info-item">
                    <label>Middle Name (Optional)</label>
                    <p><?php echo htmlspecialchars($resident['middle_name']); ?></p>
                </div>
                <div class="info-item">
                    <label>Last Name</label>
                    <p><?php echo htmlspecialchars($resident['last_name']); ?></p>
                </div>
                <div class="info-item">
                    <label>Suffix (Optional)</label>
                    <p><?php echo htmlspecialchars($resident['suffix']); ?></p>
                </div>
                <div class="info-item">
                    <label>Sex</label>
                    <p><?php echo htmlspecialchars($resident['sex']); ?></p>
                </div>
                <div class="info-item">
                    <label>Birthday</label>
                    <p><?php echo htmlspecialchars($resident['date_of_birth']); ?></p>
                </div>
                <div class="info-item">
                    <label>Mobile Number</label>
                    <p><?php echo htmlspecialchars($resident['mobile_number']); ?></p>
                </div>
                <div class="info-item">
                    <label>Email Address</label>
                    <p><?php echo htmlspecialchars($resident['email_address']); ?></p>
                </div>
                <div class="info-item">
                    <label>Civil Status</label>
                    <p><?php echo htmlspecialchars($resident['civil_status']); ?></p>
                </div>
                <div class="info-item">
                    <label>Socioeconomic Category</label>
                    <p><?php echo htmlspecialchars($resident['socioeconomic_category']); ?></p>
                </div>
                <div class="info-item">
                    <label>Health Status</label>
                    <p><?php echo htmlspecialchars($resident['health_status']); ?></p>
                </div>
                <div class="info-item">
                    <label>House/Lot Number</label>
                    <p><?php echo htmlspecialchars($resident['house_lot_number']); ?></p>
                </div>
                <div class="info-item">
                    <label>Street/Subdivision Name</label>
                    <p><?php echo htmlspecialchars($resident['street_subdivision_name']); ?></p>
                </div>
                <div class="info-item">
                    <label>Barangay</label>
                    <p><?php echo htmlspecialchars($resident['barangay']); ?></p>
                </div>
                <div class="info-item">
                    <label>Municipality</label>
                    <p><?php echo htmlspecialchars($resident['municipality']); ?></p>
                </div>
            </div>
        </div>
        <div class="profile-photo">
            <img src="/path/to/photo.jpg" alt="Resident Photo">
            <p><?php echo htmlspecialchars($resident['resident_id']); ?></p>
        </div>
    </div>
    
    <div class="status-container">
        <span class="status"><?php echo htmlspecialchars($resident['account_status']); ?></span>
        <div class="action-buttons">
            <button class="btn btn-deactivate">DEACTIVATE</button>
            <button class="btn btn-update">UPDATE</button>
        </div>
    </div>
</div>
</body>
</html>
