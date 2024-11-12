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

// profile photo upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_photo'])) {
    if ($_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/'; 
        $fileName = basename($_FILES['profile_photo']['name']);
        $targetPath = $uploadDir . $fileName;


        if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $targetPath)) {
            $query = "UPDATE residents SET profile_photo_path = ? WHERE resident_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $targetPath, $resident_id);
            $stmt->execute();

            $resident['profile_photo_path'] = $targetPath;
        }
    }
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
            color: white;
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
            font-size: 18px;
            font-weight: bold;
        }

        .profile-container {
            padding: 20px;
            border-radius: 8px;
            display: flex;
            flex-direction: row-reverse; 
            gap: 30px;
        }

        .title-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #4597C0;
            padding: 10px;
            border-radius: 8px;
            color: white;
        }

        .title-container h3 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }

        .upload-photo-button {
            background-color: #4597C0;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 14px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .profile-info, .profile-photo {
            flex: 1;
            color:#02476A;
            font-size: 17px;
        }

        .profile-photo {
            text-align: center;
        }

        .profile-photo img {
            border-radius: 8px;
            width: 150px;
            height: 180px;
            margin-bottom: 10px;
        }

        .info-group {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .info-item label {
            font-size: 14px;
            font-weight: bold;
            color: black;
        }

        .info-item input {
            width: 100%;
            padding: 8px;
            border: 1px solid #02476A;
            border-radius: 4px;
            font-size: 14px;
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
    <script>
        function uploadPhoto() {
            const fileInput = document.getElementById('profile_photo');
            fileInput.click(); 

            fileInput.onchange = function() {
                if (fileInput.files.length > 0) {
                    document.getElementById('photoUploadForm').submit();
                }
            };
        }
    </script>
</head>
<body>

<div class="container">
    <div class="header">
        <span class="material-symbols-rounded back-button">arrow_back</span>
        <h2>RESIDENT DETAILS</h2>
    </div>
    <hr>


     <div class="title-container">
        <h3>PROFILE</h3>
        <button type="button" class="upload-photo-button" onclick="uploadPhoto()">
            <span class="material-symbols-rounded">file_upload</span> UPLOAD A PHOTO
        </button>
    </div>

    <div class="profile-container">
        <form id="photoUploadForm" method="post" enctype="multipart/form-data" style="display: inline;">
            <input type="file" name="profile_photo" id="profile_photo" accept="image/*" style="display: none;">
            <div class="profile-photo">
                <img src="<?php echo htmlspecialchars($resident['profile_photo_path']); ?>" alt="Resident Photo">
                <p>Resident ID: <?php echo htmlspecialchars($resident['resident_id']); ?></p>
            </div>
        </form>

        <div class="profile-info">
        <h3>Personal Information</h3>
        <div class="info-group">
                <div class="info-item">
                    <label>First Name</label>
                    <input type="text" value="<?php echo htmlspecialchars($resident['first_name']); ?>" readonly>
                </div>
                <div class="info-item">
                    <label>Middle Name (Optional)</label>
                    <input type="text" value="<?php echo htmlspecialchars($resident['middle_name']); ?>" readonly>
                </div>
                <div class="info-item">
                    <label>Last Name</label>
                    <input type="text" value="<?php echo htmlspecialchars($resident['last_name']); ?>" readonly>
                </div>
                <div class="info-item">
                    <label>Suffix (Optional)</label>
                    <input type="text" value="<?php echo htmlspecialchars($resident['suffix']); ?>" readonly>
                </div>
                <div class="info-item">
                    <label>Sex</label>
                    <input type="text" value="<?php echo htmlspecialchars($resident['sex']); ?>" readonly>
                </div>
                <div class="info-item">
                    <label>Birthday</label>
                    <input type="text" value="<?php echo htmlspecialchars($resident['date_of_birth']); ?>" readonly>
                </div>
                <div class="info-item">
                    <label>Mobile Number</label>
                    <input type="text" value="<?php echo htmlspecialchars($resident['mobile_number']); ?>" readonly>
                </div>
                <div class="info-item">
                    <label>Email Address</label>
                    <input type="text" value="<?php echo htmlspecialchars($resident['email_address']); ?>" readonly>
                </div>
                <div class="info-item">
                    <label>Civil Status</label>
                    <input type="text" value="<?php echo htmlspecialchars($resident['civil_status']); ?>" readonly>
                </div>
                <div class="info-item">
                    <label>Socioeconomic Category</label>
                    <input type="text" value="<?php echo htmlspecialchars($resident['socioeconomic_category']); ?>" readonly>
                </div>
                <div class="info-item">
                    <label>Health Status</label>
                    <input type="text" value="<?php echo htmlspecialchars($resident['health_status']); ?>" readonly>
                </div>
                <div class="info-item">
                    <label>House/Lot Number</label>
                    <input type="text" value="<?php echo htmlspecialchars($resident['house_lot_number']); ?>" readonly>
                </div>
                <div class="info-item">
                    <label>Street/Subdivision Name</label>
                    <input type="text" value="<?php echo htmlspecialchars($resident['street_subdivision_name']); ?>" readonly>
                </div>
                <div class="info-item
                    <label>Barangay</label>
                    <input type="text" value="<?php echo htmlspecialchars($resident['barangay']); ?>" readonly>
                </div>
                <div class="info-item">
                    <label>Municipality</label>
                    <input type="text" value="<?php echo htmlspecialchars($resident['municipality']); ?>" readonly>
                </div>
                <div class="info-item">
                    <label>Account Status</label>
                    <input type="text" value="<?php echo htmlspecialchars($resident['account_status']); ?>" readonly>
                </div>
            </div>
        </div>
    </div>

    <div class="status-container">
        <div class="status">
            <?php echo ucfirst($resident['account_status']); ?>
        </div>

        <div class="action-buttons">
            <button class="btn btn-update" onclick="window.location.href='updateresident.php?resident_id=<?php echo htmlspecialchars($resident['resident_id']); ?>'">
                UPDATE
            </button>
            <button class="btn btn-deactivate" onclick="window.location.href='deactivateresident.php?resident_id=<?php echo htmlspecialchars($resident['resident_id']); ?>'">
                DEACTIVATE
            </button>
        </div>
    </div>
</div>

</body>
</html>
