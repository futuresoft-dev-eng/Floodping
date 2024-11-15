<?php
include_once('../adminsidebar.php');
include_once('../db/connection.php');

// Fetch options from categories table
$sexQuery = "SELECT category_id, category_value FROM categories WHERE category_type = 'sex'";
$sexResult = mysqli_query($conn, $sexQuery);

$civilStatusQuery = "SELECT category_id, category_value FROM categories WHERE category_type = 'civil_status'";
$civilStatusResult = mysqli_query($conn, $civilStatusQuery);

$socioeconomicQuery = "SELECT category_id, category_value FROM categories WHERE category_type = 'socioeconomic_category'";
$socioeconomicResult = mysqli_query($conn, $socioeconomicQuery);

$healthStatusQuery = "SELECT category_id, category_value FROM categories WHERE category_type = 'health_status'";
$healthStatusResult = mysqli_query($conn, $healthStatusQuery);

$isResidentAdded = false; 

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $required_fields = ['first_name', 'last_name', 'sex', 'date_of_birth', 'mobile_number', 'email_address', 'civil_status', 'socioeconomic_category', 'health_status', 'house_lot_number', 'street_subdivision_name'];
    $missing_fields = [];

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $missing_fields[] = $field;
        }
    }

    if (empty($missing_fields)) {
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
        $account_status = 1;

        $profile_photo_path = '';
        if (!empty($_FILES['profile_photo']['name'])) {
            $photo_name = $_FILES['profile_photo']['name'];
            $target_dir = "../uploads/";
            $profile_photo_path = $target_dir . basename($photo_name);
            if (!move_uploaded_file($_FILES['profile_photo']['tmp_name'], $profile_photo_path)) {
                echo "Error uploading profile photo.";
            }
        }

        // Insert new resident's information into the residents table
        $sql = "INSERT INTO residents (first_name, middle_name, last_name, suffix, sex_id, date_of_birth, mobile_number, email_address, civil_status_id, socioeconomic_category_id, health_status_id, house_lot_number, street_subdivision_name, barangay, municipality, account_status_id, profile_photo_path)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssississssssiss", $first_name, $middle_name, $last_name, $suffix, $sex, $date_of_birth, $mobile_number, $email_address, $civil_status, $socioeconomic_category, $health_status, $house_lot_number, $street_subdivision_name, $barangay, $municipality, $account_status, $profile_photo_path);

        if ($stmt->execute()) {
            $isResidentAdded = true; // Set success flag
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Resident</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Symbols+Rounded">
    <style>
       <style>
        .main-content { 
            margin-left: 200px; 
            padding: 20px; }
        .container { 
            max-width: 100%; margin: 0 auto; 
            padding: 20px;
            font-family: Arial, sans-serif; }
        .header { 
            display: flex;  align-items: center; 
            margin-bottom: 20px;  padding: 10px; 
            background-color: white; color: white; 
            border-radius: 8px;  gap: 15px; }
        .back-button {
            background-color: #0073AC; color: white;
            padding: 8px  20px; border-radius: 15%;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; text-decoration: none; }            
        .header h2 { 
            margin: 0; 
            font-size: 18px;  font-weight: bold; }       
        .profile-container { 
            padding: 20px; border-radius: 8px;
            display: flex; flex-direction: row-reverse; 
            gap: 30px; }
        .title-container {
            display: flex; justify-content: space-between;
            align-items: center;
            background-color: #4597C0; color: white;
            padding: 10px; border-radius: 8px; }
        .title-container h3 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }
        .upload-photo-button {
            background-color: #4597C0; color: white;
            border: none;  border-radius: 5px;
            padding: 10px 20px; cursor: pointer;
            font-size: 14px;  gap: 5px;
            display: flex; align-items: center;
        }
        .profile-info, .profile-photo { flex: 1; color: #02476A; font-size: 17px; }
        .info-group { display: grid; grid-template-columns: repeat(4, 1fr); gap: 40px; }
        .info-item label { font-size: 14px; font-weight: bold; color: black;  margin-right: 50px;  }
        .info-item input, .info-group select {
             width: 100%; padding: 8px; 
             border: 1px solid #02476A; 
             border-radius: 4px; font-size: 14px; }  
        .radio-group {
            display: flex; gap: 40px; 
        }
        .radio-option {
            display: flex; align-items: center;
            font-size: 20px; color: #02476A; 
        }
        .radio-option input[type="radio"] {
            margin-right: 5px; 
        }
        .read-only {
            background-color: #C5C5C5;
            color: #525252; 
            border: 1px solid #C5C5C5; 
            pointer-events: none; 
        }

        hr {
            border: 1px solid #e0e0e0;
            margin: 20px 0;
        }   
        .profile-photo { 
            text-align: center; cursor: pointer;
            width: 250px; height: 200px; 
            border: 2px solid #02476A; 
            overflow: hidden; display: inline-block; }
        .profile-photo img { 
            width: 100%; height: 100%; object-fit: cover; }
        .resident-id-box {
            margin-top: 10px;}
        .resident-id-box input {
            width: 150px; text-align: center;
            border: 1px solid #ccc; border-radius: 5px;
            padding: 5px; font-size: 17px;
            color: #525252; }
        .resident-id-box p {
            font-size: 17px; color: ##000000;
            margin-top: 5px;font-weight: bold; }
        .submit-btn { 
            background-color: #59C447; color: white; 
            padding: 10px 20px; border: none; border-radius: 5px; 
            cursor: pointer; font-weight: bold; }

    
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
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            width: 500px;
            text-align: center;
            font-family: Arial, sans-serif;
        }
        .modal-header {
            font-size: 18px;
            font-weight: bold;
            color: #02476A;
        }
        .modal-buttons {
            margin-top: 20px;
            display: flex;
            justify-content: space-around;
        }
        .modal-button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .modal-button.no {
            background-color: #EA3323;
            color: white;
        }
        .modal-button.yes {
            background-color: #59C447;
            color: white;
        }
        .modal-content h3 {
            display: flex;
            align-items: center;
            font-size: 1.2em;
            }

        .help-icon {
            font-size: 125px; 
            margin-left: 8px; 
            color: #02476A; 
            cursor: pointer;
        }

    </style>
 <script>
        function uploadPhoto() {
            const fileInput = document.getElementById('profile_photo');
            fileInput.click();

            fileInput.onchange = function() {
                if (fileInput.files.length > 0) {
                    // Display the selected image without submitting the form
                    const previewImage = document.querySelector('.profile-photo img');
                    const fileReader = new FileReader();
                    fileReader.onload = function(event) {
                        previewImage.src = event.target.result;
                    };
                    fileReader.readAsDataURL(fileInput.files[0]);
                }
            };
        }

        function validateForm() {
            let isValid = true;
            const requiredFields = ['first_name', 'last_name', 'date_of_birth', 'mobile_number', 'email_address', 'civil_status', 'socioeconomic_category', 'health_status', 'house_lot_number', 'street_subdivision_name'];
            
            requiredFields.forEach((field) => {
                const input = document.getElementsByName(field)[0];
                if (input && input.value.trim() === '') {
                    input.style.borderColor = 'red';
                    isValid = false;
                } else if (input) {
                    input.style.borderColor = '';
                }
            });

            const sexGroup = document.getElementsByName('sex');
            const sexGroupContainer = document.querySelector('.radio-group');
            let isSexSelected = false;

            for (let i = 0; i < sexGroup.length; i++) {
                if (sexGroup[i].checked) {
                    isSexSelected = true;
                    break;
                }
            }

            if (!isSexSelected) {
                sexGroupContainer.style.border = '2px solid red';
                isValid = false;
            } else {
                sexGroupContainer.style.border = '';
            }

            const errorMessage = document.getElementById('error-message');
            if (!isValid) {
                errorMessage.style.display = 'inline';
            } else {
                errorMessage.style.display = 'none';
            }

            return isValid;
        }

        function confirmCreation(event) {
            if (validateForm()) {
                event.preventDefault();
                document.getElementById('confirmationModal').style.display = 'flex';
            } else {
                event.preventDefault();
            }
        }

        function closeModal() {
            document.getElementById('confirmationModal').style.display = 'none';
        }

        function submitForm() {
            document.querySelector('form[action="addresident.php"]').submit();
        }

        function showModalAndRedirect() {
            const modal = document.getElementById('successModal');
            modal.style.display = 'flex';

            setTimeout(() => {
                window.location.href = "http://localhost/floodping/ADMIN/accountservices.php";
            }, 5000);
        }
    </script>

</head>
<body>

<div class="container">
    <div class="header">
    <a href="http://localhost/floodping/ADMIN/accountservices.php" class="back-button">
    <span class="material-symbols-rounded">arrow_back</span>
</a>
        <h2>RESIDENT PROFILE CREATION</h2>
    </div>
    <hr>

    <div class="title-container">
        <h3>PROFILE</h3>
        <button type="button" class="upload-photo-button"  onclick="uploadPhoto()">
            <span class="material-symbols-rounded">file_upload</span> UPLOAD A PHOTO
        </button>
    </div>


    <div class="profile-container">
    <form action="addresident.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
        <input type="file" name="profile_photo" id="profile_photo" accept="image/*" style="display: none;">
           
            <div class="profile-photo">
                <img src="<?php echo htmlspecialchars($resident['profile_photo_path']); ?>" alt="Resident Photo">
            </div>
           
            <div class="resident-id-box">
            <input type="text" class="read-only" value="" readonly>
            <p>Resident ID</p>
        </div>

        <div class="profile-info">
        <h3>Personal Information</h3>
        <span id="error-message" style="color: red; display: none;">Please fill in all required fields.</span>

                <div class="info-group">
                    <div class="info-item">
                        <label for="first_name">First Name:</label>
                        <input type="text" name="first_name" placeholder="Enter first name" required>
                        </div>
                    <div class="info-item">
                        <label for="middle_name">Middle Name: (Optional) </label>
                        <input type="text" name="middle_name" placeholder="Enter middle name">

                    </div>
                    <div class="info-item">
                        <label for="last_name">Last Name:</label>
                        <input type="text" name="last_name" placeholder="Enter last name" required>
                    </div>
                    <div class="info-item">
                        <label for="suffix">Suffix: (Optional)</label>
                        <input type="text" name="suffix" placeholder="Enter suffix">
                    </div>

                    <div class="info-item">
                        <label for="sex">Sex:</label>
                        <div class="radio-group">
                            <?php while ($row = mysqli_fetch_assoc($sexResult)) : ?>
                                <label class="radio-option">
                                    <input type="radio" name="sex" value="<?php echo htmlspecialchars($row['category_id']); ?>" required>
                                    <?php echo htmlspecialchars($row['category_value']); ?>
                                </label>
                            <?php endwhile; ?>
                        </div>
                    </div>


                    <div class="info-item">
                        <label for="date_of_birth">Date of Birth:</label>
                        <input type="date" name="date_of_birth" required>
                    </div>
                    <div class="info-item">
                        <label for="mobile_number">Mobile Number:</label>
                        <input type="text" name="mobile_number"placeholder="Enter mobile number" required>
                    </div>
                    <div class="info-item">
                        <label for="email_address">Email Address:</label>
                        <input type="email" name="email_address"  placeholder="Enter email address" required>
                    </div>
                    <div class="info-item">
                        <label for="civil_status">Civil Status:</label>
                        <select name="civil_status" required>
                            <?php while ($row = mysqli_fetch_assoc($civilStatusResult)) : ?>
                                <option value="<?php echo htmlspecialchars($row['category_id']); ?>"><?php echo htmlspecialchars($row['category_value']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="info-item">
                        <label for="socioeconomic_category">Socioeconomic Category:</label>
                        <select name="socioeconomic_category" required>
                            <?php while ($row = mysqli_fetch_assoc($socioeconomicResult)) : ?>
                                <option value="<?php echo htmlspecialchars($row['category_id']); ?>"><?php echo htmlspecialchars($row['category_value']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="info-item">
                        <label for="health_status">Health Status:</label>
                        <select name="health_status" required>
                            <?php while ($row = mysqli_fetch_assoc($healthStatusResult)) : ?>
                                <option value="<?php echo htmlspecialchars($row['category_id']); ?>"><?php echo htmlspecialchars($row['category_value']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="info-item">
                        <label for="house_lot_number">House/Lot Number:</label>
                        <input type="text" name="house_lot_number"  placeholder="Enter house/lot number" required>
                    </div>
                    <div class="info-item">
                        <label for="street_subdivision_name">Street/Subdivision Name:</label>
                        <input type="text" name="street_subdivision_name" placeholder="Enter street/subdivision name" required>

                    </div>
                   <div class="info-item">
                        <label for="barangay">Barangay:</label>
                        <input type="text" name="barangay"  class="read-only"  value="Bagbag" readonly>
                    </div>
                    <div class="info-item">
                        <label for="municipality">Municipality:</label>
                        <input type="text" name="municipality"  class="read-only"  value="Quezon City" readonly>
                    </div>
        
                </div>
                <hr>
                <div>
                <button type="button" class="submit-btn" onclick="confirmCreation(event)">CREATE</button>
                </div>     
            </form>
        </div>     
    </div>

    <!-- Modal 1 -->
<div id="confirmationModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
        <span class="material-symbols-rounded help-icon" title="Need help?">
      help
    </span>
            Are you sure you want to create this resident?
        </div>
        <p>Creating this resident's profile will make them receive SMS alerts and notifications.</p>
        <div class="modal-buttons">
            <button class="modal-button no" onclick="closeModal()">No</button>
            <button class="modal-button yes" onclick="submitForm()">Yes</button>
        </div>
    </div>
</div>

 <!-- Modal 2 -->
 <div id="successModal" class="modal">
            <div class="modal-content">
                <h2>New resident added successfully!</h2>
                <p>You will be redirected shortly...</p>
            </div>
        </div>

        <?php if ($isResidentAdded): ?>
            <script>
                document.addEventListener('DOMContentLoaded', showModalAndRedirect);
            </script>
        <?php endif; ?>

</div>

<?php mysqli_close($conn); ?>
</body>
</html>