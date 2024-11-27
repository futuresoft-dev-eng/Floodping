
<?php
include 'db_conn.php';
include 'adminsidebar.php';
function generatePassword($len = 12) {
    $lowercase = "abcdefghijklmnopqrstuvwxyz";
    $uppercase = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $numbers = "0123456789";
    $special = "!@#$%^&*()-_=+[]{}|;:,.<>?";
    $all = $lowercase . $uppercase . $numbers . $special;
    $password = $lowercase[rand(0, strlen($lowercase) - 1)] .
                $uppercase[rand(0, strlen($uppercase) - 1)] .
                $numbers[rand(0, strlen($numbers) - 1)] .
                $special[rand(0, strlen($special) - 1)];

    for ($i = 4; $i < $len; $i++) {
        $password .= $all[rand(0, strlen($all) - 1)];
    }

    return str_shuffle($password);
}

$successMessage = "";
$errorMessage = "";
$result = $conn->query("SELECT COUNT(*) as count FROM users");
if ($result) {
    $row = $result->fetch_assoc();
    $user_id = str_pad($row['count'] + 1, 5, '0', STR_PAD_LEFT);
} else {
    die("Error counting users: " . $conn->error);
}

$password = generatePassword();
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $middle_name = $conn->real_escape_string($_POST['middle_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $suffix = $conn->real_escape_string($_POST['suffix']);
    $contact_no = $conn->real_escape_string($_POST['contact_no']);

    if (!preg_match('/^09\d{9}$/', $contact_no)) {
        die("Invalid mobile number. Must be 11 digits and start with 09.");
    }

    $sex = $conn->real_escape_string($_POST['sex']);
    $birthdate = $conn->real_escape_string($_POST['birthdate']);
    $email = strtolower(trim($conn->real_escape_string($_POST['email'])));
    $role = $conn->real_escape_string($_POST['role']);
    $account_status = "Active";
    $position = $conn->real_escape_string($_POST['position']);
    $house_lot_number = $conn->real_escape_string($_POST['house_lot_number']);
    $street_subdivision_name = $conn->real_escape_string($_POST['street_subdivision_name']);
    $city = $conn->real_escape_string($_POST['city']);
    $barangay = $conn->real_escape_string($_POST['barangay']);

    // LA and Admin user limit checks
    if ($role == "Local Authority") {
        $authority_count_result = $conn->query("SELECT COUNT(*) as authority_count FROM users WHERE role = 'Local Authority'");
        if ($authority_count_result) {
            $count_row = $authority_count_result->fetch_assoc();
            if ($count_row['authority_count'] >= 3) {
                echo "Cannot add more than 3 Local Authority users.";
                exit();
            }
        } else {
            die("Error checking authority count: " . $conn->error);
        }
    }

    if ($role == "Admin") {
        $admin_count_result = $conn->query("SELECT COUNT(*) as admin_count FROM users WHERE role = 'Admin'");
        if ($admin_count_result) {
            $count_row = $admin_count_result->fetch_assoc();
            if ($count_row['admin_count'] >= 2) {
                echo "Cannot add more than 2 Admin users.";
                exit();
            }
        } else {
            die("Error checking admin count: " . $conn->error);
        }
    }

    // Profile photo upload
        $profile_photo = "";
        if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0) {
        $target_dir = "../uploads/";
        $profile_photo_name = time() . "_" . basename($_FILES["profile_photo"]["name"]);
        $target_file = $target_dir . $profile_photo_name;

        if (move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $target_file)) {
        $profile_photo = $profile_photo_name; 
        } else {
        echo "Error uploading profile photo.";
        exit(); 
        }

        }

    // Check for duplicate email
    $email_check = $conn->prepare("SELECT 1 FROM users WHERE email = ?");
    $email_check->bind_param("s", $email);
    $email_check->execute();
    $email_check->store_result();

    if ($email_check->num_rows > 0) {
        echo "Email address already exists.";
        $email_check->close();
        exit();
    }

    $email_check->close();

    // Check for duplicate contact number
    $contact_check = $conn->prepare("SELECT 1 FROM users WHERE contact_no = ?");
    $contact_check->bind_param("s", $contact_no);
    $contact_check->execute();
    $contact_check->store_result();

    if ($contact_check->num_rows > 0) {
        echo "Contact number already exists.";
        $contact_check->close();
        exit();
    }

    $contact_check->close();

    $stmt = $conn->prepare("INSERT INTO users 
        (user_id, first_name, middle_name, last_name, suffix, contact_no, sex, birthdate, email, password, role, profile_photo, account_status, position, house_lot_number, street_subdivision_name, city, barangay) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

if ($stmt) {
    $stmt->bind_param(
 "ssssssssssssssssss",
 $user_id, $first_name, $middle_name, $last_name, $suffix, $contact_no, $sex, $birthdate, $email, $hashed_password,
 $role, $profile_photo, $account_status, $position, $house_lot_number, $street_subdivision_name, $city, $barangay
);

        if ($stmt->execute()) {
            $successMessage = "User created successfully. Password: $password";
        } else {
            if ($conn->errno === 1062) { 
                $errorMessage = "Duplicate email addresses are not allowed.";
            } else {
                $errorMessage = "Error inserting user: " . $stmt->error;
            }
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="/floodping/images/Floodpinglogo.png" type="image/png">
    <title>Create New User</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <style>
        .container { 
            max-width: 100%; 
            height: 130vh !important;
            margin: 0 0px 0px -40px; 
            padding: 20px;
            font-family: Poppins; 
            background-color: #FFFFFF !important; 
        }
     
        .title-container h3 {
            width: 1220px;
            height: 50px;
            background-color: #4597C0;
            color: #FFFFFF;
            font-size: 20px;
            margin: 80px 0px 0px 0px;
            position: a-zA-Z0-9;
            text-transform: uppercase;
            padding: 10px;
            border-radius: 5px;

        }

        .back-button {
            background-color: #0073AC;
            color: white;
            padding: 8px 20px; 
            border-radius: 9%;
            display: flex; 
            align-items: center; 
            justify-content: center;
            cursor: pointer;
            text-decoration: none; 
            z-index: 100;
            margin: 20px 0px 0px -17px;
            position: absolute;
        }   

        .profile-container { 
            padding: 20px; 
            border-radius: 8px;
            display: flex; 
            flex-direction: row-reverse; 
            gap: 30px; 

        }
        
        .profile-info {
            width: 70%;
            margin: 60px 0px 0px -290px !important;
            position: absolute;

        }

        #createUserForm {
            margin: -35px 0px 0px 850px !important;
            position: absolute;
            width: auto;
            height: auto;
            background-color: transparent;
            font-size: 12px;
        }

        .info-group {
            margin: 0px 0px 0px 300px;
            position: absolute;
            width: 90%;
        }
        .info-item-id  {
            width: 130px;
            margin: 80px 0px 0px 940px;
            position: absolute;
            font-size: 14px;
        }

        .info-item-id input  {
            width: 130px;
            height: 35px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin: 80px 0px 0px 100px;
            position: absolute;
            font-size: 14px;
        }

        #userID-title {
            width: 100%;
            margin: 122px 0px 0px 130px;
            position: absolute;
            font-size: 15px;
            color: black;
        }


        #address-title {
            position: absolute;
            margin-top: -20px;
        }

        #address {
            margin: 25px 0px 0px -0px;
            width: 100%; 
            padding: 0px; 
        }

        .profile-info h3 {
            margin: 0px 0px 0px 300px;
            font-size: 20px;
            width: auto;

        }

        #position {
            margin: 40px 0px 0px -0px;
        }

        .profile-info hr {
            width: 100%;
        }

        #createUserButton {
            font-size: 18px;
            width: 17%;
            height: 9%;
            padding: 8px auto;
            border-radius: 5px;
            color: #FFFF;
            background-color: #59C447;
            text-transform: uppercase;
            margin: 420px 0px 0px 500px;
            position: absolute;
        }

        #profile_photo {
            display: none;
        }

        #uploadLabel {
            font-size: 14px; 
            color: white; 
            cursor: pointer;
            font-weight: normal;
            margin: -65px 0px 0px 170px; 
            padding: 50px;
            position: absolute;
            min-width: 1000px !important;
        }   

        #profilePreview {
            text-align: center; 
            cursor: pointer;
            width: 200px; 
            height: 150px; 
            border: 2px solid #02476A; 
            overflow: hidden; 
            display: inline-block; 
            margin: 100px 0px 0px 160px;
            position: absolute;
        }

        #upload-icon {
            margin: -20px 0px 0px 180px;
            position: absolute;
            color: white;
            font-size: 30px;
        }

        .profile-info, .profile-photo { 
            flex: 1; 
            color: #02476A; 
            font-size: 17px; 
        }

        #personal-info-title {
            margin-top: -50px;
            width: 100%;
        }

        .info-group { 
            display: grid; 
            grid-template-columns: repeat(4, 1fr); 
            gap: 40px; 
        }

        .info-item label { 
            font-size: 12px; 
            font-weight: bold; 
            color: black;  
            margin-right: 50px; 
            width: 100%;
        }

        .info-item input, .info-group select {
             width: 100%; 
             padding: 8px; 
             border: 1px solid #02476A; 
             border-radius: 4px; 
             font-size: 12px; 
             position: relative;
        } 
        
        .info-item p {
            font-size: 20px;
        }

        .sex-option {
            display: flex; 
            align-items: center;
            font-size: 20px; 
            color: #02476A; 
            margin: 0px 80px 0px 20px;
           
        }

        .sex-option label {
            margin: 0px 0px 0px 40px;
            padding: 10px 0px;
            
        }

        .sex-option input[type="radio"] {
            margin: 0px 0px 0px -70px; 
           
        }
        
        #successModal {
            display: none;
            position: fixed;
            top: -100px;
            left: 0;
            width: 100%;
            height: 100%;
            justify-content: center;
            align-items: center;
        }

        #successModal.show {
            display: flex;
        }

        #successMessage {
            margin-left: -20px;
        }

        /* Modal content */
        #successModal .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            width: 400px;
            position: relative;
        }

        #successModal .close {
            position: absolute;
            top: 10px;
            right: 15px;
            color: #aaa;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
        }

        #successModal .close:hover,
        #successModal .close:focus {
            color: black;
            text-decoration: none;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 550px !important;
            height: 200px;
            justify-content: center;
            align-items: center;
            margin: 250px 0px 0px 550px;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            background-color: #fff;
            border-radius: 8px !important;
            padding: 20px;
            text-align: center;
            width: 485px !important;
            height: 190px;
        }

        .modal-content p {
            font-size: 15px;
            margin: 50px 0px 0px 130px;
}

        .add-photo {
            width: 120px;
            height: auto;
            margin: 0px 0px 0px 0px !important;
            position: absolute;
        }

        .confirmation-btns {
            padding: 15px 150px;
            display: flex;
            justify-content: space-around;
            cursor: pointer;
            border: none;
            margin: 20px 0px 0px -10px !important;
        }

        #cancel {
            min-width: 110px !important;
            min-height: 30px;
            border-radius: 5px;
            background-color: #4597C0;
            color: white;
            margin-left: 30px;
        }

        #create {
            min-width: 110px !important;
            min-height: 30px;
            border-radius: 5px;
            background-color: #59C447;
            color: white;
        }
    </style>
</head>

<body>
<div class="header">
<a href="http://localhost/floodping/ADMIN/accountservices.php" class="back-button">
<span class="material-symbols-rounded">arrow_back</span>
</a>    
<hr style="color: #ccc; width: 90%; position: absolute; margin: 70px 0px 0px -20px;">
    </div>
    <hr>

    <div class="container">
    <div class="title-container">
        <h3>PROFILE</h3>
        <form id="createUserForm" method="POST" action="" oninput="checkForm()" enctype="multipart/form-data">
        <input type="file" name="profile_photo" id="profile_photo" accept="image/*" onchange="previewImage(event)">
<br>

 <!-- Custom Label as Button -->
 <label id="uploadLabel" for="profile_photo">UPLOAD A PHOTO</label>
 <span class="material-symbols-rounded" id="upload-icon">file_upload</span>
<img id="profilePreview" alt="Photo">
    </div>

    
    <div class="profile-info">
    <br>
        <div class="info-group">
        <div class="info-item-id">
        <h3 id="userID-title">User ID</h3>
        <input type="text" value="<?= $user_id ?>" readonly>
        <br>
        </div>

        <div class="info-item">
        <p id="personal-info-title">Personal Information</p>
        <label>First Name</label>
        <input type="text" name="first_name" required onkeypress="return noNumbers(event)" placeholder="First Name" oninput="capitalizeInput(event)">
        <br>
    </div>

    <div class="info-item">
        <label>Middle Name (Optional)</label>
        <input type="text" name="middle_name" onkeypress="return noNumbers(event)" placeholder="Middle Name" oninput="capitalizeInput(event)">
        <br>
        </div>

        <div class="info-item">
        <label>Last Name</label>
        <input type="text" name="last_name" required onkeypress="return noNumbers(event)" placeholder="Last Name" oninput="capitalizeInput(event)">
        <br>
        </div>

        <div class="info-item">
        <label>Suffix (Optional)</label>
        <input type="text" name="suffix" onkeypress="return noNumbers(event)" placeholder="Jr Sr II III IV" oninput="capitalizeInput(event)">
        <br>
    </div>
    
    <div class="info-item">
        <label>Contact No</label>
        <input type="text" name="contact_no" pattern="^\d{11}$" title="Contact number must be exactly 11 digits" required maxlength="11" onkeypress="return onlyNumbers(event)" placeholder="Contact Number">
        <br>
    </div>

    <div class="info-item">
        <label>Sex</label>
        <div class="sex-option">
            <label><input type="radio" name="sex" value="Male" required> Male</label>
        <label><input type="radio" name="sex" value="Female" required> Female</label>
        </div>
        <br>
        </div>

        <div class="info-item">
        <label>Birthdate</label>
        <input type="date" name="birthdate" required>
        <br>
    </div>

    <div class="info-item">
        <label>Email</label>
        <input 
        type="email" name="email" pattern="^[a-zA-Z0-9._%+-]+@gmail\.com$" required placeholder="example@gmail.com" title="Please enter a valid Gmail address (e.g., example@gmail.com)" id="emailInput">
        <br>
        <span id="emailError" style="color:red; display:none; font-size: 10px; position: absolute;">Please follow the required format (e.g., example@gmail.com).</span>
    </div>

    <div class="info-item">
        <p id="address-title">Address</p>
        <br>
        <label for="city">Municipality</label>
        <select name="city" required>
            <option value="Quezon City">Quezon City</option>
        </select>
        <br>
    </div>

    <div class="info-item" id="address">
        <label for="barangay">Barangay</label>
        <select name="barangay" required>
            <option value="Bagbag">Bagbag</option>
        </select>
        <br>
    </div>

    <div class="info-item" id="address">
        <label for="house_lot_number">House/Lot Number:</label>
        <input type="text" name="house_lot_number" required placeholder="House/Lot Number" oninput="capitalizeInput(event)">
        <br>
    </div>
    
    <div class="info-item" id="address">
        <label for="street_subdivision_name">Street/Subdivision Name:</label>
        <input type="text" name="street_subdivision_name" required placeholder="Street/Subdivision Name" oninput="capitalizeInput(event)">
        </div>
    
        <div class="info-item">
        <p>Job Description</p>
        <label>Role</label>
        <select name="role" required>
            <option value="" disabled selected>Select Role</option>
            <option value="Admin">Admin</option>
            <option value="Local Authority">Local Authority</option>
        </select>
        <br>
        </div>

        <div class="info-item" id="position">
        <label>Position</label>
        <select name="position" required>
            <option value="" disabled selected>Select Position</option>
            <option value="Executive Officer">Executive Officer</option>
        </select>
        <br>
        </div>
        <button id="createUserButton" type="button" onclick="showConfirmModal()" disabled>Create</button>
        <br><br><br>
    </form>
    </div>     
    </div>
    </div>
    <!-- Confirmation Modal -->
    <div id="confirmModal" class="modal">
        <div class="modal-content">
        <img class="add-photo" src="images/add-icon.png">
            <p>Are you sure you want to create this user?</p>
        <div class="confirmation-btns">
            <button id="create" onclick="confirmYes()">CREATE</button>
            <button id="cancel" onclick="confirmNo()">CANCEL</button>
            </div>
        </div>
    </div>
    </div>
    </div>

    <!-- Modal for success message -->
    <div id="successModal" class="modal">
        <div class="modal-content">
            <p id="successMessage"></p>
            <button onclick="closeSuccessModal()">Close</button>
        </div>
    </div>

<script> // This script is for the modals
    // Show Modal
    function showModal() {
        document.getElementById("userModal").style.display = "flex";
        document.querySelector("form").reset();
        document.getElementById('profilePreview').style.display = 'none';
    }

    // Hide Modal
    function hideModal() {
        document.getElementById("userModal").style.display = "none";
    }

    // Preview Image
    function previewImage(event) {
    const output = document.getElementById('profilePreview');
    output.style.display = 'block'; // Make the image visible
    output.src = URL.createObjectURL(event.target.files[0]); // Set the preview image
    }


    // No numbers in string fields
    function noNumbers(event) {
        var char = event.key;
        if (/[0-9]/.test(char)) {
            event.preventDefault();
        }
    }

    // Only numbers in int fields
    function onlyNumbers(event) {
        var char = event.key;
        if (!/[0-9]/.test(char)) {
            event.preventDefault();
        }
    }

    // Close Success Modal
    function closeSuccessModal() {
        document.getElementById("successModal").style.display = "none";
    }

    // Live Search functionality
    function liveSearch() {
        const searchInput = document.getElementById("search").value.toLowerCase();
        const rows = document.querySelectorAll("#userTable tbody tr");
        rows.forEach(row => {
            const cells = row.querySelectorAll("td");
            const name = cells[1].textContent.toLowerCase();
            if (name.includes(searchInput)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }
</script>

<script>
    // This script is for the confirmation modal
    function showConfirmModal() {
        const modal = document.getElementById('confirmModal');
        modal.classList.add('show'); 
    }

    function confirmYes() {
        const form = document.getElementById('createUserForm');
        form.submit();
    }

    function confirmNo() {
        window.location.href = 'add_user.php';
    }

    function showSuccessModal(message) {
        const modal = document.getElementById('successModal');
        const messageElement = document.getElementById('successMessage');
        messageElement.textContent = message; 
        modal.classList.add('show');
    }

    function closeSuccessModal() {
        window.location.href = 'add_user.php';
    }

    <?php if (!empty($successMessage)): ?>
        document.addEventListener("DOMContentLoaded", function () {
            showSuccessModal("<?= $successMessage ?>");
        });
    <?php endif; ?>
    <?php if (!empty($errorMessage)): ?>
        document.addEventListener("DOMContentLoaded", function () {
            alert("<?= $errorMessage ?>");
        });
    <?php endif; ?>
</script>

<script>// This script is for the capitalization of the inputs
    function capitalizeInput(event) {
        let input = event.target;
        input.value = input.value.charAt(0).toUpperCase() + input.value.slice(1);
    }

    function capitalizePlaceholder() {
        let inputs = document.querySelectorAll('input[placeholder]');
        inputs.forEach(input => {
            let placeholder = input.getAttribute('placeholder');
            input.setAttribute('placeholder', placeholder.charAt(0).toUpperCase() + placeholder.slice(1));
        });
    }

    window.onload = capitalizePlaceholder;
</script>

<script>// This script is for the checking of required fields, email, and button enable and disable.
    function checkForm() {
        const form = document.getElementById('createUserForm');
        const button = document.getElementById('createUserButton');
        const requiredFields = form.querySelectorAll('[required]');
        const emailInput = document.getElementById('emailInput');
        const errorMessage = document.getElementById('emailError');
        
        let allFilled = true;
        let emailValid = true;
        requiredFields.forEach(function(field) {
            if (!field.value || (field.type === 'radio' && !form.querySelector('input[name="'+field.name+'"]:checked'))) {
                allFilled = false;
            }
        });

        const pattern = new RegExp(emailInput.pattern);
        if (!pattern.test(emailInput.value)) {
            emailValid = false;
            errorMessage.style.display = 'inline'; 
        } else {
            errorMessage.style.display = 'none'; 
        }
        button.disabled = !(allFilled && emailValid);
    }

    document.getElementById('emailInput').addEventListener('input', checkForm);
    document.querySelectorAll('input[required], select[required], textarea[required]').forEach(function(input) {
        input.addEventListener('input', checkForm);
    });

    function previewImage(event) {
    const fileInput = event.target;
    const preview = document.getElementById('profilePreview');
    
    if (fileInput.files && fileInput.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        };
        reader.readAsDataURL(fileInput.files[0]);
    } else {
        preview.src = ''; // Clear preview if no file is selected
    }
}
</script>


</body>
</html>
