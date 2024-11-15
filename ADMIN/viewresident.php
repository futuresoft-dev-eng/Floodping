<?php
include_once('../adminsidebar.php');
include_once('../db/connection.php');

$resident_id = isset($_GET['resident_id']) ? $_GET['resident_id'] : null;

if ($resident_id) {
    $sql = "SELECT r.*, c1.category_value AS sex, c2.category_value AS civil_status, 
                   c3.category_value AS socioeconomic_category, c4.category_value AS health_status, 
                   a.category_value AS account_status
            FROM residents r
            LEFT JOIN categories c1 ON r.sex_id = c1.category_id
            LEFT JOIN categories c2 ON r.civil_status_id = c2.category_id
            LEFT JOIN categories c3 ON r.socioeconomic_category_id = c3.category_id
            LEFT JOIN categories c4 ON r.health_status_id = c4.category_id
            LEFT JOIN categories a ON r.account_status_id = a.category_id
            WHERE r.resident_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $resident_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $resident = $result->fetch_assoc();
    $stmt->close();

    // Set account status if available
    $account_status = isset($resident['account_status']) ? $resident['account_status'] : 'Unknown';
} else {
    echo "Resident ID not provided.";
    exit;
}

// Determine button label and action based on account status
$button_label = $account_status === 'Active' ? 'DEACTIVATE' : 'REACTIVATE';
$button_action = $account_status === 'Active' ? 'deactivate' : 'reactivate';




//  update resident
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_resident'])) {
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $suffix = $_POST['suffix'];
    $sex = $_POST['sex'];
    $date_of_birth = $_POST['date_of_birth'];
    $mobile_number = $_POST['mobile_number'];
    $email_address = $_POST['email_address'];
    $civil_status = $_POST['civil_status'];
    $socioeconomic_category = $_POST['socioeconomic_category'];
    $health_status = $_POST['health_status'];
    $house_lot_number = $_POST['house_lot_number'];
    $street_subdivision_name = $_POST['street_subdivision_name'];
    $barangay = $_POST['barangay'];
    $municipality = $_POST['municipality'];
    $profile_photo_path = $resident['profile_photo_path']; 

    // photo upload
    if (!empty($_FILES['profile_photo']['name'])) {
        $photo_name = $_FILES['profile_photo']['name'];
        $target_dir = "../uploads/";
        $profile_photo_path = $target_dir . basename($photo_name);
        if (!move_uploaded_file($_FILES['profile_photo']['tmp_name'], $profile_photo_path)) {
            echo "Error uploading profile photo.";
        }
    }

    $sql = "UPDATE residents 
            SET first_name = ?, 
                middle_name = ?, 
                last_name = ?, 
                suffix = ?, 
                sex_id = ?, 
                date_of_birth = ?, 
                mobile_number = ?, 
                email_address = ?, 
                civil_status_id = ?, 
                socioeconomic_category_id = ?, 
                health_status_id = ?, 
                house_lot_number = ?, 
                street_subdivision_name = ?, 
                barangay = ?, 
                municipality = ?, 
                profile_photo_path = ?
            WHERE resident_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssississssssiss", 
        $first_name, $middle_name, $last_name, $suffix, $sex, $date_of_birth,
        $mobile_number, $email_address, $civil_status, $socioeconomic_category, $health_status,
        $house_lot_number, $street_subdivision_name, $barangay, $municipality,
        $profile_photo_path, $resident_id 
    );

    if ($stmt->execute()) {
        echo "<script>document.addEventListener('DOMContentLoaded', () => { showSuccessModal(); });</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

$sexQuery = "SELECT category_id, category_value FROM categories WHERE category_type = 'sex'";
$sexResult = $conn->query($sexQuery);
$civilStatusQuery = "SELECT category_id, category_value FROM categories WHERE category_type = 'civil_status'";
$civilStatusResult = $conn->query($civilStatusQuery);
$socioeconomicCategoryQuery = "SELECT category_id, category_value FROM categories WHERE category_type = 'socioeconomic_category'";
$socioeconomicCategoryResult = $conn->query($socioeconomicCategoryQuery);
$healthStatusQuery = "SELECT category_id, category_value FROM categories WHERE category_type = 'health_status'";
$healthStatusResult = $conn->query($healthStatusQuery);

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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_resident'])) {
    $sql = "DELETE FROM residents WHERE resident_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $resident_id);

    if ($stmt->execute()) {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            showSuccessDeleteModal(); 
        });
    </script>";
    } else {
        echo "<script>alert('Error deleting record: {$stmt->error}');</script>";
    }

    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_account_status'])) {
    $action = $_POST['update_account_status'];
    $resident_id = $_POST['resident_id'];
    
    $new_status_id = ($action === 'deactivate') ? 2 : 1; // Assuming 1 = Active, 2 = Deactivated

    $sql = "UPDATE residents SET account_status_id = ? WHERE resident_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $new_status_id, $resident_id);

    if ($stmt->execute()) {
        echo "<script>
            alert('Account status successfully updated.');
            window.location.href = 'viewresident.php?resident_id={$resident_id}';
        </script>";
    } else {
        echo "<script>alert('Error updating account status: {$stmt->error}');</script>";
    }

    $stmt->close();
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
            color: white;
            padding: 10px;
            border-radius: 8px;
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
            text-decoration: none;
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
            width: 250px;
            height: 200px;
            overflow: hidden;
            border: 2px solid #02476A;
            cursor: pointer;
            display: inline-block;
        }

        .profile-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .resident-id-box {
            text-align: center;
            margin-top: 10px;
        }

        .resident-id-box input {
            width: 150px;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 5px;
            font-size: 17px;
            color: #525252;
            
        }

        .resident-id-box p {
            font-size: 17px;
            color: ##000000;
            margin-top: 5px;
            font-weight: bold;
        }

        .info-group {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 40px;
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
    
        hr {
            border: 1px solid #e0e0e0;
            margin: 20px 0;
        }

        .info-group select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-color: #fff;
            border: 1px solid #9DB6C1;
            border-radius: 4px;
            padding: 8px;
            font-size: 14px;
            color: #3C5364;
            width: 100%;
            max-width: 100%;
            cursor: pointer;
            padding-right: 24px; 
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="%239DB6C1"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>');
            background-repeat: no-repeat;
            background-position: right 8px center;
            background-size: 16px;
        }

.info-group select:disabled {
    color: #9DB6C1;
    background-color: #f8f9fa;
    cursor: not-allowed;
}
.info-item button {
        color: white;
        background-color: #4597C0;
        border: none; 
        cursor: pointer; 
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        font-weight: bold;
        color: white;
        cursor: pointer;
    }

    .info-item button:hover {
        text-decoration: none; 
    } 

/* Modal styles */
.modal {
    display: none;
    position: fixed;
    z-index: 100;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
}

.modal-content {
    margin: 15% auto;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    width: 500px;
    text-align: center;
    font-family: Arial, sans-serif;
}

.modal-content p {
    font-size: 16px;
    margin-bottom: 20px;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin: 5px;
}

.btn-yes {
    background-color: #4597C0;
    color: #fff;
}

.btn-no {
    background-color: #EA3323;
    color: #000;
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
       
function enableEdit() {
    document.querySelectorAll('.info-item input, .info-item select').forEach(input => {
        input.removeAttribute('readonly');
        input.removeAttribute('disabled');
        input.style.backgroundColor = '#ffffff'; 
    });
    document.getElementById('editButton').style.display = 'none';
    document.getElementById('updateButton').style.display = 'inline-block';
}

function showModal() {
    document.getElementById('confirmationModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('confirmationModal').style.display = 'none';
}

function showDeleteModal() {
    document.getElementById('deleteModal').style.display = 'block';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
}

function showSuccessModal() {
    const modal = document.getElementById('successModal');
    modal.style.display = 'flex';
}
function refreshPage() {
    window.location.reload();
}
function showSuccessDeleteModal() {
    document.getElementById('successdeleteModal').style.display = 'flex';
}


</script>

    </script>
</head>
<body>

<div class="container">
    <div class="header">
    <a href="http://localhost/floodping/ADMIN/accountservices.php" class="back-button">
    <span class="material-symbols-rounded">arrow_back</span>
</a>
        <h2>RESIDENT DETAILS</h2>
    </div>
    <hr>

    <div class="title-container">
        <h3>PROFILE</h3>
        <button type="button" class="upload-photo-button"  onclick="uploadPhoto()">
            <span class="material-symbols-rounded">file_upload</span> UPLOAD A PHOTO
        </button>
    </div>

    <div class="profile-container">
    <form id="photoUploadForm" method="post" enctype="multipart/form-data" style="display: inline;">
            <input type="file" name="profile_photo" id="profile_photo" accept="image/*" style="display: none;">
           
            <div class="profile-photo">
                <img src="<?php echo htmlspecialchars($resident['profile_photo_path']); ?>" alt="Resident Photo">
            </div>
           
            <div class="resident-id-box">
            <input type="text" value="<?php echo htmlspecialchars($resident['resident_id']); ?>" readonly>
            <p>Resident ID</p>
        </div>
</form>


<div class="profile-info">
    <h3>Personal Information</h3>
    <form method="POST">
        <div class="info-group">
            <!-- First row -->
            <div class="info-item">
                <label>First Name</label>
                <input type="text" name="first_name" value="<?php echo htmlspecialchars($resident['first_name']); ?>" readonly>
            </div>
            <div class="info-item">
                <label>Middle Name (Optional)</label>
                <input type="text" name="middle_name" value="<?php echo htmlspecialchars($resident['middle_name']); ?>" readonly style="background-color: #F5F5F5;">
            </div>
            <div class="info-item">
                <label>Last Name</label>
                <input type="text" name="last_name" value="<?php echo htmlspecialchars($resident['last_name']); ?>" readonly style="background-color: #F5F5F5;">
            </div>
            <div class="info-item">
                <label>Suffix (Optional)</label>
                <input type="text" name="suffix" value="<?php echo htmlspecialchars($resident['suffix']); ?>" readonly style="background-color: #F5F5F5;">
            </div>

            <!-- Second row -->
            <div class="info-item">
                <label for="sex">Sex:</label>
                <div class="radio-group">
                    <?php while ($row = mysqli_fetch_assoc($sexResult)) : ?>
                        <label class="radio-option">
                            <input type="radio" name="sex" value="<?php echo htmlspecialchars($row['category_id']); ?>"
                                <?php echo $resident['sex_id'] == $row['category_id'] ? 'checked' : ''; ?> disabled>
                            <?php echo htmlspecialchars($row['category_value']); ?>
                        </label>
                    <?php endwhile; ?>
                </div>
            </div>
            <div class="info-item">
                <label>Birthday</label>
                <input type="text" name="date_of_birth" value="<?php echo htmlspecialchars($resident['date_of_birth']); ?>" readonly style="background-color: #F5F5F5;">
            </div>
            <div class="info-item">
                <label>Mobile Number</label>
                <input type="text" name="mobile_number" value="<?php echo htmlspecialchars($resident['mobile_number']); ?>" readonly style="background-color: #F5F5F5;">
            </div>
            <div class="info-item">
                <label>Email Address</label>
                <input type="text" name="email_address" value="<?php echo htmlspecialchars($resident['email_address']); ?>" readonly style="background-color: #F5F5F5;">
            </div>

            <!-- Third row -->
            <div class="info-item">
                <label for="civil_status">Civil Status:</label>
                <select name="civil_status" disabled>
                    <?php while ($row = mysqli_fetch_assoc($civilStatusResult)) : ?>
                        <option value="<?php echo htmlspecialchars($row['category_id']); ?>"
                            <?php echo $resident['civil_status_id'] == $row['category_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($row['category_value']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="info-item">
                <label for="socioeconomic_category">Socioeconomic Category</label>
                <select name="socioeconomic_category" disabled>
                    <?php while ($row = mysqli_fetch_assoc($socioeconomicCategoryResult)) : ?>
                        <option value="<?php echo htmlspecialchars($row['category_id']); ?>"
                            <?php echo $resident['socioeconomic_category_id'] == $row['category_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($row['category_value']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="info-item">
                <label for="health_status">Health Status</label>
                <select name="health_status" disabled style="background-color: #F5F5F5;">
                    <?php while ($row = mysqli_fetch_assoc($healthStatusResult)) : ?>
                        <option value="<?php echo htmlspecialchars($row['category_id']); ?>"
                            <?php echo $resident['health_status_id'] == $row['category_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($row['category_value']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Fourth row -->
            <div class="info-item">
                <label>House/Lot Number</label>
                <input type="text" name="house_lot_number" value="<?php echo htmlspecialchars($resident['house_lot_number']); ?>" readonly style="background-color: #F5F5F5;">
            </div>
            <div class="info-item">
                <label>Street/Subdivision Name</label>
                <input type="text" name="street_subdivision_name" value="<?php echo htmlspecialchars($resident['street_subdivision_name']); ?>" readonly style="background-color: #F5F5F5;">
            </div>
            <div class="info-item">
                <label>Barangay</label>
                <input type="text" name="barangay" value="<?php echo htmlspecialchars($resident['barangay']); ?>" readonly style="background-color: #F5F5F5;">
            </div>
            <div class="info-item">
                <label>Municipality</label>
                <input type="text" name="municipality" value="<?php echo htmlspecialchars($resident['municipality']); ?>" readonly style="background-color: #F5F5F5;">
            </div>
            </div>
            
           
<hr>


<!-- Account Status -->
<div class="info-item">
    <label for="account_status">Account Status:</label>
    <input type="text" id="account_status" name="account_status" value="<?php echo htmlspecialchars($account_status); ?>" readonly>
    <form method="POST" style="display:inline;">
        <input type="hidden" name="resident_id" value="<?php echo htmlspecialchars($resident_id); ?>">
        <button type="submit" name="update_account_status" value="<?php echo $button_action; ?>">
            <?php echo $button_label; ?>
        </button>
    </form>
</div>






<div class="info-item">
<button type="button" id="deleteButton" onclick="showDeleteModal()">DELETE</button>
<button type="button" id="editButton" onclick="enableEdit()">EDIT</button>
    <button type="button" id="updateButton" style="display: none;" onclick="showModal()">UPDATE</button>
    </div>

        <!-- Modal -->
<div id="confirmationModal" class="modal">
    <div class="modal-content">
        <p>Are you sure you want to update?</p>
        <button type="button" class="btn btn-no" onclick="closeModal()">No</button>
        <button type="submit" class="btn btn-yes" name="update_resident">Yes</button>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="modal">
    <div class="modal-content">
        <p>Resident details updated successfully!</p>
        <button class="btn btn-yes" onclick="refreshPage()">OK</button>
    </div>
</div>

<!-- DELETE MODAL -->

<div id="deleteModal" class="modal">
    <div class="modal-content">
        <p>Are you sure you want to delete this resident?</p>
        <form method="POST">
            <button type="button" onclick="closeDeleteModal()" class="btn btn-no">No</button>
            <button type="submit" name="delete_resident" class="btn btn-yes">Yes</button>
        </form>
    </div>
</div>

<!-- SUCCESS DELETE MODAL -->
<div id="successdeleteModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <span class="material-symbols-rounded" style="color: green;">check_circle</span>
            Resident Deleted Successfully
        </div>
        <p>The resident profile has been deleted.</p>
        <div class="modal-buttons">
        <a href="http://localhost/floodping/ADMIN/accountservices.php" class="btn btn-yes">OK</a>
        </div>
    </div>
</div>


</form>


        </div>
    </div>
</div>

</body>
</html>
