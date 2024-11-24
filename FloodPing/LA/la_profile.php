<?php
session_start();
include_once('../db/db_conn.php');
if (!isset($_SESSION['user_id'])) {
    header("Location: ../HOME/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


$stmt = $conn->prepare("
    SELECT 
        user_id, first_name, middle_name, last_name, suffix, sex, birthdate, contact_no, 
        email, house_lot_number, street_subdivision_name, barangay, city, role, position, 
        schedule, shift, profile_photo 
    FROM users 
    WHERE user_id = ?
");
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "Error: User not found.";
        exit();
    }
    $stmt->close();
} else {
    echo "Error preparing statement: " . $conn->error;
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
<script>
    function toggleEditMode(editMode) {
        const fields = document.querySelectorAll('.editable');
        fields.forEach(field => {
            field.readOnly = !editMode;
            field.style.backgroundColor = editMode ? 'white' : 'gray';
        });

        document.getElementById('editButton').style.display = editMode ? 'none' : 'inline';
        document.getElementById('saveButton').style.display = editMode ? 'inline' : 'none';
    }

    function showModal() {
        if (validateForm()) {
            document.getElementById('confirmModal').style.display = 'block';
        }
    }

    function closeModal() {
        document.getElementById('confirmModal').style.display = 'none';
    }

    function validateForm() {
        let isValid = true;
        const editableFields = document.querySelectorAll('.editable');

      
        document.querySelectorAll('.error-message').forEach(e => e.textContent = '');
        editableFields.forEach(field => field.style.border = '');

       
        editableFields.forEach(field => {
            if (field.value.trim() === "") {
                field.style.border = "2px solid red";
                isValid = false;
            } else {
                field.style.border = "";
            }
        });

   
        const mobileField = document.querySelector("input[name='contact_no']");
        const mobileValue = mobileField.value.trim();
        const mobileError = document.getElementById("contactError");
        const mobileRegex = /^\d{11}$/;

        if (!mobileRegex.test(mobileValue)) {
            mobileField.style.border = "2px solid red";
            mobileError.textContent = "Contact number must be 11 digits and should start with 09.";
            isValid = false;
        } else {
            mobileField.style.border = "";
            mobileError.textContent = "";
        }

  
        const emailField = document.querySelector("input[name='email']");
        const emailValue = emailField.value.trim();
        const emailError = document.getElementById("emailError");
        const emailRegex = /^[a-zA-Z0-9._%+-ñÑ]+@gmail\.com$/;

        if (!emailRegex.test(emailValue)) {
            emailField.style.border = "2px solid red";
            emailError.textContent = "Email must end with @gmail.com";
            isValid = false;
        } else {
            emailField.style.border = "";
            emailError.textContent = "";
        }

        return isValid;
    }


    function enforceNumericInput(event) {
        const keyCode = event.keyCode || event.which;
        const keyValue = String.fromCharCode(keyCode);

        if (!/^\d$/.test(keyValue) && keyCode !== 8 && keyCode !== 46) { 
            event.preventDefault();
        }
    }

  
document.addEventListener('DOMContentLoaded', () => {
    const fields = document.querySelectorAll('.editable');

    fields.forEach(field => {
        field.addEventListener('input', () => {
            field.style.border = ''; 
            const errorElement = field.nextElementSibling;
            if (errorElement && errorElement.classList.contains('error-message')) {
                errorElement.textContent = ''
            }
        });
    });
});


    function checkDuplicates(mobileValue, emailValue) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'check_duplicates.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                let hasDuplicate = false;

                if (response.duplicateContact) {
                    const mobileField = document.querySelector("input[name='contact_no']");
                    const mobileError = document.getElementById("contactError");
                    mobileField.style.border = "2px solid red";
                    mobileError.textContent = "This contact number is already registered.";
                    hasDuplicate = true;
                }
                if (response.duplicateEmail) {
                    const emailField = document.querySelector("input[name='email']");
                    const emailError = document.getElementById("emailError");
                    emailField.style.border = "2px solid red";
                    emailError.textContent = "This email address is already registered.";
                    hasDuplicate = true;
                }

                if (!hasDuplicate) {
                    document.getElementById('confirmModal').style.display = 'block';
                }
            }
        };
        xhr.send(`contact_no=${mobileValue}&email=${emailValue}`);
    }

    function saveChanges() {
        document.getElementById('editForm').submit();
    }
</script>


<style>
    input[readonly] {
        background-color: lightgray;
        color: #333;
    }
    .editable {
        background-color: white;
    }
    #confirmModal {
        display: none;
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }
    .modal-content {
        background: #fff; padding: 20px; border-radius: 8px;
        text-align: center; width: 300px; margin: auto;
    }
    .error-message {
        color: red;
        font-size: 0.9em;
    }
    .success-message {
    color: green;;
    margin-top: 20px;
}
.error { color: red; font-size: 12px; }
        .error-border { border: 1px solid red; }
        #confirmationModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        #confirmationModal div {
            background: white;
            padding: 20px;
            border-radius: 5px;
        }
    </style>

</style>

</head>
<body>
    <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
        <p class="success-message">Profile information has been updated successfully.</p>
    <?php endif; ?>
    <h3>My Account</h3>
    <p>My Profile</p>
    
   <form id="editForm" method="POST" action="admin_profile_save_changes.php">
    <?php if (!empty($user['profile_photo'])): ?>
        <img src="<?= htmlspecialchars($user['profile_photo']) ?>" alt="Profile Photo" width="150" height="150">
    <?php else: ?>
        <p>No profile photo available.</p>
    <?php endif; ?>
    
    <label>User ID: <input type="text" value="<?= htmlspecialchars($user['user_id']) ?>" readonly></label><br>
    <label>First Name: <input type="text" value="<?= htmlspecialchars($user['first_name']) ?>" readonly></label><br>
    <label>Middle Name: <input type="text" value="<?= htmlspecialchars($user['middle_name']) ?>" readonly></label><br>
    <label>Last Name: <input type="text" value="<?= htmlspecialchars($user['last_name']) ?>" readonly></label><br>
    <label>Suffix: <input type="text" value="<?= htmlspecialchars($user['suffix']) ?>" readonly></label><br>
    
    <label>Sex:</label><br>
    <label>
        <input type="radio" name="sex" value="Male" <?= $user['sex'] === 'Male' ? 'checked' : '' ?> disabled> Male
    </label>
    <label>
        <input type="radio" name="sex" value="Female" <?= $user['sex'] === 'Female' ? 'checked' : '' ?> disabled> Female
    </label><br>
    
    <label>Birthday: <input type="text" value="<?= htmlspecialchars($user['birthdate']) ?>" readonly></label><br>
    
    <label>Mobile Number: 
        <input type="text" class="editable" name="contact_no" onkeypress="enforceNumericInput(event)" pattern="\d{11}" title="11-digit number starting with 09" value="<?= htmlspecialchars($user['contact_no']) ?>" readonly>
        <div id="contactError" class="error-message"></div>
    </label><br>
    
    <label>Email Address: 
        <input type="email" class="editable" name="email" value="<?= htmlspecialchars($user['email']) ?>" readonly>
        <div id="emailError" class="error-message"></div>
    </label><br>
    
    <label>House/Lot Number: <input type="text" class="editable" name="house_lot_number" value="<?= htmlspecialchars($user['house_lot_number']) ?>" readonly></label><br>
    <label>Street/Subdivision: <input type="text" class="editable" name="street_subdivision_name" value="<?= htmlspecialchars($user['street_subdivision_name']) ?>" readonly></label><br>
    <label>Barangay: <input type="text" value="<?= htmlspecialchars($user['barangay']) ?>" readonly></label><br>
    <label>City: <input type="text" value="<?= htmlspecialchars($user['city']) ?>" readonly></label><br>
    <label>Role: <input type="text" value="<?= htmlspecialchars($user['role']) ?>" readonly></label><br>
    <label>Position: <input type="text" value="<?= htmlspecialchars($user['position']) ?>" readonly></label><br>

    <label>Schedule: <input type="text" value="<?= htmlspecialchars($user['schedule'] ?? 'Unassigned') ?>" readonly></label><br>
    <label>Shift: <input type="text" value="<?= htmlspecialchars($user['shift'] ?? 'Unassigned') ?>" readonly></label><br>

    <button type="button" id="editButton" onclick="toggleEditMode(true)">Edit</button>
    <button type="button" id="saveButton" style="display:none;" onclick="showModal()">Save Changes</button>

<div id="confirmModal">
    <div class="modal-content">
        <p>Are you sure you want to save changes?</p>
        <button type="button" onclick="saveChanges()">Yes</button>
        <button type="button" onclick="closeModal()">No</button>
    </div>
</div>





<!-- Every code below is for change password. Ayoko na! pumipitik na ugat ko sa ulo! -->
<h3>Change Your Password</h3>
<form method="POST" onsubmit="return confirmPasswordChange();">
    <label for="current_password">Current Password:</label>
    <input type="password" id="current_password" name="current_password" required oninput="validateForm()">
<?php if ($error): ?>
    <div id="current_password_error" class="error-message" style="color: red;">
        <?= $error ?>
    </div>
<?php endif; ?>

    <br><br>

    <label for="new_password">New Password:</label>
    <input type="password" id="new_password" name="new_password" required oninput="validateForm()">
    <div id="new_password_error" class="error-message"></div><br><br>

    <label for="confirm_password">Confirm New Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required oninput="validateForm()">
    <div id="confirm_password_error" class="error-message"></div><br><br>

    <button type="button" id="submit_button" onclick="showModal()" disabled>Save Changes</button>
</form>

<?php if ($success_message): ?>
    <p style="color: green;"><?= $success_message ?></p>
<?php endif; ?>

<div id="confirmationModal" style="display: none;">
    <div>
        <p>Are you sure you want to change your password? Changing your password will log you out of all active sessions. You will be redirected to the login page to sign in again with your new password.</p>
        <button onclick="hideModal()">Cancel</button>
        <button onclick="submitForm(); hideModal();">Yes</button>
    </div>
</div>

<script>
function validateForm() {
    var currentPassword = document.getElementById("current_password").value;
    var newPassword = document.getElementById("new_password").value;
    var confirmPassword = document.getElementById("confirm_password").value;
    var isValid = true;

    
    resetBorders();
    if (!currentPassword) {
        document.getElementById("current_password").classList.add("error-border");
        document.getElementById("current_password_error").innerText = "Current password is required.";
        isValid = false;
    }

    if (currentPassword && document.getElementById("current_password_error").innerText === "Your current password does not match.") {
        document.getElementById("current_password").classList.add("error-border");
        document.getElementById("current_password_error").innerText = "Your current password does not match.";
        isValid = false;
    }

    if (newPassword && confirmPassword && newPassword !== confirmPassword) {
        document.getElementById("confirm_password").classList.add("error-border");
        document.getElementById("confirm_password_error").innerText = "New password and confirm password do not match.";
        isValid = false;
    }

    if (newPassword && !/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^\w\d\s:]).{12,}$/.test(newPassword)) {
        document.getElementById("new_password").classList.add("error-border");
        document.getElementById("new_password_error").innerText = "Password must be at least 12 characters long containing uppercase, lowercase, number, and special character.";
        isValid = false;
    }
    document.getElementById("submit_button").disabled = !isValid;
}


function resetBorders() {
    document.getElementById("current_password").classList.remove("error-border");
    document.getElementById("new_password").classList.remove("error-border");
    document.getElementById("confirm_password").classList.remove("error-border");

    document.getElementById("current_password_error").innerText = "";
    document.getElementById("new_password_error").innerText = "";
    document.getElementById("confirm_password_error").innerText = "";
}

function showModal() {
    if (document.getElementById("submit_button").disabled || document.getElementById("current_password_error").innerText) {
        return; 
    }
    document.getElementById("confirmationModal").style.display = "block";
}

function hideModal() {
    document.getElementById("confirmationModal").style.display = "none";
}

function confirmPasswordChange() {
    return confirm('Are you sure you want to change your password? This will log you out of all active sessions.');
}

function submitForm() {
    document.forms[0].submit();
}
</script>




</body>
</html>
