<?php
session_start(); 
include_once('../db/db_conn.php');
include 'update_user.php'; 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User</title>
    <link rel="stylesheet" href="styles.css">
    <style type="text/css">
        .readonly-field {
            background-color: #f0f0f0;
            color: #333;
        }

        .modal {
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

        .modal-content {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
    </style>
</head>

<body>

    <button type="button" onclick="window.location.href='add_user.php';"><-</button>
    <button>VIEW ACTIVITY LOG</button>

   <form method="POST" enctype="multipart/form-data" action="update_user.php?user_id=<?= $user['user_id'] ?>">

    <p>Profile Photo</p>
    <?php if ($user['profile_photo']) : ?>
        <img id="profilePhotoPreview" src="<?= htmlspecialchars($user['profile_photo']) ?>" alt="Profile Photo" style="width:100px; height:100px;">
    <?php else : ?>
        <img id="profilePhotoPreview" src="default_image.jpg" alt="Profile Photo" style="width:100px; height:100px;">
    <?php endif; ?>
    <br>
    <input type="file" name="profile_photo" id="profilePhotoInput"><br><br>

    <label>User ID</label>
    <input type="text" value="<?= htmlspecialchars($user['user_id']) ?>" readonly class="readonly-field"><br>

    <p>Personal Information</p>
    <label>First Name:</label>
    <input type="text" name="first_name" oninput="capitalizeInput(event)" value="<?= htmlspecialchars($user['first_name']) ?>"><br>

    <label>Middle Name (Optional)</label>
    <input type="text" name="middle_name" oninput="capitalizeInput(event)" value="<?= htmlspecialchars($user['middle_name']) ?>"><br>

    <label>Last Name</label>
    <input type="text" name="last_name" oninput="capitalizeInput(event)" value="<?= htmlspecialchars($user['last_name']) ?>"><br>

    <label>Suffix (Optional)</label>
    <input type="text" name="suffix" oninput="capitalizeInput(event)" value="<?= htmlspecialchars($user['suffix']) ?>"><br>

    <label>Contact No</label>
    <input type="text" name="contact_no" value="<?= htmlspecialchars($user['contact_no']) ?>" id="contactNo" maxlength="11" oninput="validateContactNumber()"><br>

    <label>Sex</label><br>
    <input type="radio" name="sex" value="Male" <?= ($user['sex'] === 'Male') ? 'checked' : '' ?>> Male<br>
    <input type="radio" name="sex" value="Female" <?= ($user['sex'] === 'Female') ? 'checked' : '' ?>> Female<br><br>

    <label>Birthdate</label>
    <input type="date" name="birthdate" value="<?= htmlspecialchars($user['birthdate']) ?>"><br>

    <label>Email</label>
    <input type="text" name="email" value="<?= htmlspecialchars($user['email']) ?>"><br>

    <p>Address</p>
    <label>City</label>
    <input type="text" name="city" value="<?= htmlspecialchars($user['city']) ?>" readonly class="readonly-field"><br>

    <label>Barangay</label>
    <input type="text" name="barangay" value="<?= htmlspecialchars($user['barangay']) ?>" readonly class="readonly-field"><br>

    <label>House/Lot Number</label>
    <input type="text" name="house_lot_number" oninput="capitalizeInput(event)" value="<?= htmlspecialchars($user['house_lot_number']) ?>"><br>

    <label>Street/Subdivision Name</label>
    <input type="text" name="street_subdivision_name" oninput="capitalizeInput(event)" value="<?= htmlspecialchars($user['street_subdivision_name']) ?>"><br>

    <p>Job Description</p>
    <label>Role:</label>
    <select name="role">
        <option value="Admin" <?= ($user['role'] === 'Admin') ? 'selected' : '' ?>>Admin</option>
        <option value="Local Authority" <?= ($user['role'] === 'Local Authority') ? 'selected' : '' ?>>Local Authority</option>
    </select><br>

    <label>Position</label>
    <select name="position">
        <option value="Executive Officer" <?= ($user['position'] === 'Executive Officer') ? 'selected' : '' ?>>Executive Officer</option>
    </select><br>

    <label>Work Day Schedule</label>
    <input type="text" name="schedule" value="<?= htmlspecialchars($user['schedule']) ?>" readonly class="readonly-field"><br>

    <label>Work Time Schedule</label>
    <input type="text" name="shift" value="<?= htmlspecialchars($user['shift']) ?>" readonly class="readonly-field"><br>

    <button type="submit">UPDATE</button>
</form>



<form method="POST" action="change_status.php" id="statusForm">
    <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id) ?>">
    
    <label>Status</label>
    <input type="text" name="account_status" value="<?= htmlspecialchars($user['account_status'] ?? 'Active') ?>" readonly class="readonly-field">
    <?php if ($user['account_status'] == 'Inactive') : ?>
        <button type="button" id="reactivateButton">REACTIVATE</button>
    <?php elseif ($user['account_status'] == 'Active') : ?>
        <button type="button" id="deactivateButton">DEACTIVATE</button>
    <?php elseif ($user['account_status'] == 'Locked') : ?>
        <button type="button" id="unlockButton">UNLOCK</button>
    <?php endif; ?>
</form>

<div id="deactivateModal" class="modal" style="display: none;">
    <div class="modal-content">
        <p>Are you sure you want to deactivate this account?</p>
        <button onclick="confirmStatusChange('deactivate')">DEACTIVATE</button>
        <button onclick="closeModal('deactivateModal')">NO</button>
    </div>
</div>
<div id="reactivateModal" class="modal" style="display: none;">
    <div class="modal-content">
        <p>Are you sure you want to reactivate this account?</p>
        <button onclick="confirmStatusChange('reactivate')">REACTIVATE</button>
        <button onclick="closeModal('reactivateModal')">NO</button>
    </div>
</div>
<div id="unlockModal" class="modal" style="display: none;">
    <div class="modal-content">
        <p>Are you sure you want to unlock this account?</p>
        <button onclick="confirmStatusChange('unlock')">UNLOCK</button>
        <button onclick="closeModal('unlockModal')">NO</button>
    </div>
</div>









<form method="POST" action="archive_account.php" id="archiveForm">
    <button type="button" id="archiveButton" name="archive" value="<?= htmlspecialchars($user['user_id']) ?>">ARCHIVE</button>
</form>

<!-- Archive-->
<div id="archiveModal" class="modal" style="display: none;">
    <div class="modal-content">
        <p>Are you sure you want to archive this account? This action cannot be undone.</p>
        <button onclick="confirmArchive()">ARCHIVE</button>
        <button onclick="closeModal('archiveModal')">CANCEL</button>
    </div>
</div>




<script> // This script is for deactivating adn reactivating the account
document.getElementById('deactivateButton')?.addEventListener('click', function () {
    showModal('deactivateModal');
});

document.getElementById('reactivateButton')?.addEventListener('click', function () {
    showModal('reactivateModal');
});

document.getElementById('unlockButton')?.addEventListener('click', function () {
    showModal('unlockModal');
});

function showModal(modalId) {
    document.getElementById(modalId).style.display = 'flex';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

function confirmStatusChange(action) {
    var form = document.getElementById('statusForm');
    var actionInput = document.createElement('input');
    actionInput.type = 'hidden';
    actionInput.name = 'action';
    actionInput.value = action;
    form.appendChild(actionInput);
    form.submit();
}

</script>



<script>//This script is for the archive_account.php
    const archiveButton = document.getElementById('archiveButton');
    archiveButton.addEventListener('click', function () {
        document.getElementById('archiveModal').style.display = 'flex';
    });

    function confirmArchive() {
        const userId = archiveButton.value;
        let form = document.createElement("form");
        form.method = "POST";
        form.action = "archive_account.php";

        let input = document.createElement("input");
        input.type = "hidden";
        input.name = "user_id";
        input.value = userId;
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
        closeModal('archiveModal');
    }
    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }
</script>

<script>// This script is for the capitalization of the inputs
        function capitalizeInput(event) {
            let input = event.target;
            let value = input.value.toLowerCase().replace(/\b\w/g, (char) => char.toUpperCase());
            input.value = value;
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





</body>

</html>
