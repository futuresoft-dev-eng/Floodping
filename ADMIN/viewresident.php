<?php
include_once('../adminsidebar.php');
include_once('../db/connection.php');

// Get the resident ID from the URL
$resident_id = isset($_GET['resident_id']) ? $_GET['resident_id'] : null;

if ($resident_id) {
    // Fetch resident details
    $sql = "SELECT r.*, 
                   c1.category_value AS sex, 
                   c2.category_value AS civil_status, 
                   c3.category_value AS socioeconomic_category, 
                   c4.category_value AS health_status, 
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

    // Handle case where resident is not found
    if (!$resident) {
        echo "<p>Resident not found.</p>";
        exit;
    }
} else {
    echo "<p>Resident ID not provided.</p>";
    exit;
}

// Update resident details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_resident'])) {
    // Collect data from the form
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

    // Update the database
    $update_sql = "UPDATE residents 
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
                       municipality = ?
                   WHERE resident_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param(
        "ssssisssssssssss",
        $first_name,
        $middle_name,
        $last_name,
        $suffix,
        $sex,
        $date_of_birth,
        $mobile_number,
        $email_address,
        $civil_status,
        $socioeconomic_category,
        $health_status,
        $house_lot_number,
        $street_subdivision_name,
        $barangay,
        $municipality,
        $resident_id
    );

    if ($stmt->execute()) {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    showSuccessUpdateModal();
                });
              </script>";
    } else {
        echo "Error updating record: " . $stmt->error;
    }    
    $stmt->close();
}

// Fetch dropdown values
$sexQuery = "SELECT category_id, category_value FROM categories WHERE category_type = 'sex'";
$sexResult = $conn->query($sexQuery);

$civilStatusQuery = "SELECT category_id, category_value FROM categories WHERE category_type = 'civil_status'";
$civilStatusResult = $conn->query($civilStatusQuery);

$socioeconomicCategoryQuery = "SELECT category_id, category_value FROM categories WHERE category_type = 'socioeconomic_category'";
$socioeconomicCategoryResult = $conn->query($socioeconomicCategoryQuery);

$healthStatusQuery = "SELECT category_id, category_value FROM categories WHERE category_type = 'health_status'";
$healthStatusResult = $conn->query($healthStatusQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Resident Details</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Symbols+Rounded">
    <link rel="stylesheet" href="styles.css">
    <script>
// Enable the fields for editing
function enableEdit() {
    document.querySelectorAll('.info-item input, .info-item select').forEach(input => {
         // Exclude barangay and municipality fields
         if (input.name !== 'barangay' && input.name !== 'municipality') {
            input.removeAttribute('readonly');
            input.removeAttribute('disabled');
            input.style.backgroundColor = '#ffffff'; 
          } // Make background white when editable
    });
    document.getElementById('editButton').style.display = 'none';
    document.getElementById('updateButton').style.display = 'inline-block';
}

// Show confirmation modal before updating
function showUpdateModal() {
    document.getElementById('confirmationModal').style.display = 'block';
}

// Close the confirmation modal
function closeUpdateModal() {
    document.getElementById('confirmationModal').style.display = 'none';
}

// Show success modal after the update is successful
function showSuccessUpdateModal() {
    const modal = document.getElementById('successUpdateModal');
    modal.style.display = 'flex';
}

// Handle confirmation of update from the modal
function confirmUpdate(event) {
    event.preventDefault();  // Prevent form submission before confirming
    document.getElementById('confirmationModal').style.display = 'none'; // Hide confirmation modal
    document.getElementById('residentUpdateForm').submit();  // Submit the form after confirmation
}

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
       
            <div class="resident-id-box">
                <input type="text" value="<?php echo htmlspecialchars($resident['resident_id']); ?>" readonly>
                <p>Resident ID</p>
            </div>
       

        <div class="profile-info">
            <h3>Personal Information</h3>
            <form method="POST" id="residentUpdateForm">
                <div class="info-group">
                    <!-- First row -->
                    <div class="info-item">
                        <label>First Name</label>
                        <input type="text" name="first_name" value="<?php echo htmlspecialchars($resident['first_name']); ?>" readonly style="background-color: #F5F5F5;">
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
                        <input type="date" name="date_of_birth" value="<?php echo htmlspecialchars($resident['date_of_birth']); ?>" readonly style="background-color: #F5F5F5;">
                    </div>
                    <div class="info-item">
                        <label>Mobile Number</label>
                        <input type="text" name="mobile_number" value="<?php echo htmlspecialchars($resident['mobile_number']); ?>" readonly style="background-color: #F5F5F5;">
                    </div>
                    <div class="info-item">
                        <label>Email Address</label>
                        <input type="email" name="email_address" value="<?php echo htmlspecialchars($resident['email_address']); ?>" readonly style="background-color: #F5F5F5;">
                    </div>
                </div>

                <div class="info-group">
                    <div class="info-item">
                        <label>Civil Status</label>
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
                        <label>Socioeconomic Category</label>
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
                        <label>Health Status</label>
                        <select name="health_status" disabled>
                            <?php while ($row = mysqli_fetch_assoc($healthStatusResult)) : ?>
                                <option value="<?php echo htmlspecialchars($row['category_id']); ?>"
                                    <?php echo $resident['health_status_id'] == $row['category_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($row['category_value']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="info-item">
                        <label>House Lot Number</label>
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
                <button type="button" id="editButton" class="btn" onclick="enableEdit()">Edit</button>
                <button type="submit" id="updateButton" name="update_resident" class="btn" style="display:none;">Update</button>
            </form>
        </div>
    </div>

    
<!-- Account Status -->
<div class="info-item">
    <label for="account_status">Account Status:</label>
    <input type="text" id="account_status" name="account_status" value="<?php echo htmlspecialchars($account_status); ?>" readonly style="background-color: #F5F5F5;">
    <form method="POST" style="display:inline;">
        <input type="hidden" name="resident_id" value="<?php echo htmlspecialchars($resident_id); ?>">
        <button type="submit" name="update_account_status" value="<?php echo $button_action; ?>">
            <?php echo $button_label; ?>
        </button>
    </form>
</div>
<br>


    <!-- Confirmation Modal -->
    <div id="confirmationModal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close" onclick="closeUpdateModal()">&times;</span>
            <h2>Confirm Update</h2>
            <p>Are you sure you want to update this resident's details?</p>
            <button class="btn btn-yes" onclick="confirmUpdate(event)">Yes</button>
            <button class="btn btn-no" onclick="closeUpdateModal()">No</button>
        </div>
    </div>

    <!-- Success Update Modal -->
    <div id="successUpdateModal" class="modal" style="display:none;">
        <div class="modal-content">
            <div class="modal-header">
                <span class="material-symbols-rounded" style="color:red;">check_circle</span>
                Resident Updated Successfully
            </div>
            <p>Resident details updated successfully!</p>
            <div class="modal-buttons">
                <a href='/floodping/ADMIN/viewresident.php?resident_id=<?php echo htmlspecialchars($resident_id); ?>' class="btn btn-yes">OK</a>
            </div>
        </div>
    </div>

</div>
</body>
</html>
