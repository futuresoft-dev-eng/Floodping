<?php
include_once('../db/db_conn.php');
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
            $profile_photo = $target_file;
        } else {
            echo "Error uploading profile photo.";
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
    <title>Create New User</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        #successModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        #successModal.show {
            display: flex;
        }

        /* Modal content */
        #successModal .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            width: 400px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
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
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>

<body>
    <form id="createUserForm" method="POST" action="" oninput="checkForm()">
        <label>Upload Photo:</label>
        <input type="file" name="profile_photo" accept="image/*" onchange="previewImage(event)">
        <br>

        <label>User ID:</label>
        <input type="text" value="<?= $user_id ?>" readonly>
        <br>

        <p>Personal Information</p>
        <label>First Name:</label>
        <input type="text" name="first_name" required onkeypress="return noNumbers(event)" placeholder="First Name" oninput="capitalizeInput(event)">
        <br>

        <label>Middle Name (Optional):</label>
        <input type="text" name="middle_name" onkeypress="return noNumbers(event)" placeholder="Middle Name" oninput="capitalizeInput(event)">
        <br>

        <label>Last Name:</label>
        <input type="text" name="last_name" required onkeypress="return noNumbers(event)" placeholder="Last Name" oninput="capitalizeInput(event)">
        <br>

        <label>Suffix (Optional):</label>
        <input type="text" name="suffix" onkeypress="return noNumbers(event)" placeholder="Jr Sr II III IV" oninput="capitalizeInput(event)">
        <br>

        <label>Contact No:</label>
        <input type="text" name="contact_no" pattern="^\d{11}$" title="Contact number must be exactly 11 digits" required maxlength="11" onkeypress="return onlyNumbers(event)" placeholder="Contact Number">
        <br>

        <label>Sex:</label>
        <label><input type="radio" name="sex" value="Male" required> Male</label>
        <label><input type="radio" name="sex" value="Female" required> Female</label>
        <br>

        <label>Birthdate:</label>
        <input type="date" name="birthdate" required>
        <br>

        <label>Email:</label>
        <input 
        type="email" name="email" pattern="^[a-zA-Z0-9._%+-]+@gmail\.com$" required placeholder="example@gmail.com" title="Please enter a valid Gmail address (e.g., example@gmail.com)" id="emailInput">
        <br>
        <span id="emailError" style="color:red; display:none;">Please follow the required format (e.g., example@gmail.com).</span>

        <p>Address</p>
        <label for="city">Municipality:</label>
        <input type="text" name="city" value="Quezon City" readonly>
        <br>

<label for="barangay">Barangay:</label>
<input type="text" name="barangay" value="Bagbag" readonly>
<br>

        <label for="house_lot_number">House/Lot Number:</label>
        <input type="text" name="house_lot_number" required placeholder="House/Lot Number" oninput="capitalizeInput(event)">
        <br>

        <label for="street_subdivision_name">Street/Subdivision Name:</label>
        <input type="text" name="street_subdivision_name" required placeholder="Street/Subdivision Name" oninput="capitalizeInput(event)">
        <br>

        <p>Job Description</p>
        <label>Role:</label>
        <select name="role" required>
            <option value="" disabled selected>Select Role</option>
            <option value="Admin">Admin</option>
            <option value="Local Authority">Local Authority</option>
        </select>
        <br>

        <label>Position:</label>
        <select name="position" required>
            <option value="" disabled selected>Select Position</option>
            <option value="Executive Officer">Executive Officer</option>
        </select>
        <br>

        <button id="createUserButton" type="button" onclick="showConfirmModal()" disabled>Create User</button>
    </form>

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="modal">
        <div class="modal-content">
            <p>Are you sure you want to create this user?</p>
            <button onclick="confirmYes()">Yes</button>
            <button onclick="confirmNo()">No</button>
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
        output.style.display = 'block';
        output.src = URL.createObjectURL(event.target.files[0]);
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
</script>


</body>
</html>
