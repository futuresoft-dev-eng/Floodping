
<?php
session_start(); 
include 'update_user.php'; 
include 'db_conn.php';
include 'adminsidebar.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User</title>
    <link rel="stylesheet" href="styles.css">
    <style type="text/css">

        .container { 
            max-width: 100%; 
            height: 130vh !important;
            margin: 0 0px 0px -80px; 
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
            margin: 100px 0px 0px 10px;
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
            margin: -30px 0px 0px -270px !important;
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
            margin: 80px 0px 0px 930px;
            position: absolute;
            font-size: 14px;
        }

        .info-item-id input  {
            width: 144px;
            height: 35px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin: 80px 0px 0px 90px;
            position: absolute;
            font-size: 14px;
        }

        #userID-title {
            width: 100%;
            margin: 130px 0px 0px 120px;
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
            margin: -85px 0px 0px 1030px; 
            padding: 50px;
            position: absolute;
            min-width: 1000px !important;
        }   

        #profilePhotoPreview {
            text-align: center; 
            cursor: pointer;
            width: 140px; 
            height: 140px; 
            border: 2px solid #02476A; 
            overflow: hidden; 
            display: inline-block; 
            margin: 60px 0px 0px 1050px;
            position: absolute;
        }

        #upload-icon {
            margin: -40px 0px 0px 1040px;
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
            font-size: 14px; 
            color: black; 
            margin: 0px 60px 0px 0px;
           
        }

        .sex-option input[type="radio"] {
            margin: 0px 0px 0px 0px; 
            position: absolute;
           
        }
        
        #updateButton {
            font-size: 14px;
            width: 17%;
            height: 8.5%;
            padding: 8px auto;
            border: none;
            border-radius: 5px;
            color: #FFFFFF;
            background-color: #4597C0;
            text-transform: uppercase;
            margin: 600px 0px 0px 1000px;
            position: absolute;
        }

        #view-activity-btn {
            font-size: 14px;
            width: 17%;
            height: 5%;
            padding: 8px auto;
            border: none;
            border-radius: 5px;
            color: #FFFF;
            background-color: #0073AC;
            text-transform: uppercase;
            margin: 30px 0px 0px 905px;
            position: absolute;

        }

        #profilePhotoInput {
            display: none;
        }

        #job {
            margin-top: 45px;
            position: relative;
        }

        #brgy {
            margin-top: 25px;
            position: relative;
        }

        .readonly-field {
            background-color: #f0f0f0;
            color: #333;
        }

        #statuslbl {
            font-size: 16px;
            color: #02476A;
            margin: 500px 0px 0px 30px;
            
        }
         
        #acc-status {
            font-size: 14px;
            width: 13.5%;
            height: 5%;
            padding: 8px 15px;
            border: 1px solid black;
            border-radius: 5px;
            color: black;
            text-transform: uppercase;
            margin: 530px 0px 0px -50px;
            position: absolute;

        }

        #deactivateButton {
            background-color: #EA3323;
            font-size: 14px;
            width: 13.5%;
            height: 5%;
            padding: 8px auto;
            border: none;
            border-radius: 5px;
            color: #FFFFFF;
            text-transform: uppercase;
            margin: 530px 0px 0px 200px;
            position: absolute;
        }

        #archiveButton {
            font-size: 14px;
            width: 12%;
            height: 5%;
            padding: 8px auto;
            border: none;
            border-radius: 5px;
            color: #FFFFFF;
            background-color: #EA3323;
            text-transform: uppercase;
            margin: 74px 0px 0px 820px;
            position: absolute;
        }

        #resident-title {
            font-size: 20px;
            font-weight: bold;
            margin: 25px 0px 0px 80px;
            position: absolute;
            text-transform: uppercase;
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
    <div class="header">
        <p id="resident-title"> Resident's Details </p>
    <a href="http://localhost/floodping/ADMIN/accountservices.php" class="back-button">
<span class="material-symbols-rounded">arrow_back</span>
</a>    
<hr style="color: #ccc; width: 90%; position: absolute; margin: 90px 0px 0px -20px;">
    <button type="button" id="back-button" onclick="window.location.href='add_user.php';"><-</button>
    <button id="view-activity-btn">VIEW ACTIVITY LOG</button>
    </div>
 

    <div class="container">
    <div class="title-container">
        <h3>PROFILE</h3>

   <form method="POST" enctype="multipart/form-data" action="update_user.php?user_id=<?= $user['user_id'] ?>">
   
  
    </div>

    <!-- Custom Label as Button -->
    <label id="uploadLabel" for="profilePhotoInput">UPLOAD A PHOTO</label>
    <span class="material-symbols-rounded" id="upload-icon">file_upload</span>

<?php if ($user['profile_photo']) : ?>
    <!-- Display existing image if exists in the uploads folder -->
    <img id="profilePhotoPreview" src="./uploads/<?= htmlspecialchars($user['profile_photo']) ?>" alt="Profile Photo">
<?php else : ?>
    <!-- Default image if no profile photo exists -->
    <img id="profilePhotoPreview" src="default_image.jpg" alt="Profile Photo">
<?php endif; ?>
<br>
<input type="file" name="profile_photo" id="profilePhotoInput" onchange="previewImage(event)"><br><br>


<div class="profile-info">
    <br>
        <div class="info-group">
        <div class="info-item-id">
    <label id="userID-title">Resident ID</label>
    <input type="text" value="<?= htmlspecialchars($user['user_id']) ?>" readonly class="readonly-field"><br>
    <br>
    </div>
    

    <div class="info-item">
    <p id="personal-info-title">Personal Information</p>
    <label>First Name:</label>
    <input type="text" name="first_name" oninput="capitalizeInput(event)" value="<?= htmlspecialchars($user['first_name']) ?>"><br>
    </div>

    <div class="info-item">
    <label>Middle Name (Optional)</label>
    <input type="text" name="middle_name" oninput="capitalizeInput(event)" value="<?= htmlspecialchars($user['middle_name']) ?>"><br>
    </div>

    <div class="info-item">
    <label>Last Name</label>
    <input type="text" name="last_name" oninput="capitalizeInput(event)" value="<?= htmlspecialchars($user['last_name']) ?>"><br>
    </div>

    <div class="info-item">
    <label>Suffix (Optional)</label>
    <input type="text" name="suffix" oninput="capitalizeInput(event)" value="<?= htmlspecialchars($user['suffix']) ?>"><br>
    </div>

    <div class="info-item">
    <label>Contact No</label>
    <input type="text" name="contact_no" value="<?= htmlspecialchars($user['contact_no']) ?>" id="contactNo" maxlength="11" oninput="validateContactNumber()"><br>
    </div>

    <div class="info-item">
    <label>Sex</label><br>
    <div class="sex-option">
    <input type="radio" name="sex" id="male-sex" value="Male" 
       style="margin: -0px 0px 0px -455px; position: absolute;" 
       <?= ($user['sex'] === 'Male') ? 'checked' : '' ?>>
<label for="male-sex" style="margin-left: 40px; font-weight: 500; font-size: 14px;">Male</label><br>


    <input type="radio" name="sex" id="female-sex" value="Female" 
       style="margin: -0px 0px 0px -385px; position: absolute;" 
       <?= ($user['sex'] === 'Female') ? 'checked' : '' ?>>
<label for="female-sex" style="margin: -0px 0px 0px 110px; font-weight: 500; font-size: 14px; position: absolute;">Female</label><br><br>
</div>
    </div>

    <div class="info-item">
    <label>Birthdate</label>
    <input type="date" name="birthdate" value="<?= htmlspecialchars($user['birthdate']) ?>"><br>
    </div>

    <div class="info-item">
    <label>Email</label>
    <input type="text" name="email" value="<?= htmlspecialchars($user['email']) ?>"><br>
    </div>

    <div class="info-item">
    <p id="address-title">Address</p>
    <br>
    <label>City</label>
    <input type="text" name="city" value="<?= htmlspecialchars($user['city']) ?>" readonly class="readonly-field"><br>
    </div>

    <div class="info-item" id="brgy">
    <label>Barangay</label>
    <input type="text" name="barangay" value="<?= htmlspecialchars($user['barangay']) ?>" readonly class="readonly-field"><br>
    </div>

    <div class="info-item" id="brgy">
    <label>House/Lot Number</label>
    <input type="text" name="house_lot_number" oninput="capitalizeInput(event)" value="<?= htmlspecialchars($user['house_lot_number']) ?>"><br>
    </div>

    <div class="info-item" id="brgy">
    <label>Street/Subdivision Name</label>
    <input type="text" name="street_subdivision_name" oninput="capitalizeInput(event)" value="<?= htmlspecialchars($user['street_subdivision_name']) ?>"><br>
    </div>

    <div class="info-item">
    <p>Job Description</p>
    <label>Role:</label>
    <select name="role">
        <option value="Admin" <?= ($user['role'] === 'Admin') ? 'selected' : '' ?>>Admin</option>
        <option value="Local Authority" <?= ($user['role'] === 'Local Authority') ? 'selected' : '' ?>>Local Authority</option>
    </select><br>
    </div>

    <div class="info-item" id="job">
    <label>Position</label>
    <select name="position">
        <option value="Executive Officer" <?= ($user['position'] === 'Executive Officer') ? 'selected' : '' ?>>Executive Officer</option>
    </select><br>
    </div>

    <div class="info-item" id="job">
    <label>Work Day Schedule</label>
    <input type="text" name="schedule" value="<?= htmlspecialchars($user['schedule']) ?>" readonly class="readonly-field"><br>
    </div>

    <div class="info-item" id="job">
    <label>Work Time Schedule</label>
    <input type="text" name="shift" value="<?= htmlspecialchars($user['shift']) ?>" readonly class="readonly-field"><br>
    </div>

    <hr style="color: #ccc; width: 90%; position: absolute; margin: 70px 0px 0px -20px;">

    <button id="updateButton" type="submit">UPDATE</button>
</form>
 </div>
 </div>

<form method="POST" action="change_status.php" id="statusForm">
    <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id) ?>">
    
    <label id="statuslbl">Status</label>
    <input type="text" id="acc-status" name="account_status" value="<?= htmlspecialchars($user['account_status'] ?? 'Active') ?>" readonly class="readonly-field">
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

    <script>
    // Preview image before uploading
    function previewImage(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('profilePhotoPreview');

        // Check if a file is selected
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result; 
            };
            reader.readAsDataURL(file); 
        } else {
            preview.src = "default_image.jpg";  
        }
    }
</script>





</body>

</html>
