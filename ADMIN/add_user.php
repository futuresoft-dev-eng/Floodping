<?php
session_start();

if (isset($_SESSION['full_name']) && isset($_SESSION['role'])) {
    echo "<p>" . htmlspecialchars($_SESSION['full_name']) . "</p>";
    echo "<p>" . htmlspecialchars($_SESSION['role']) . "</p>";
} else {
    echo "<h3>Welcome, Guest!</h3>";
}

include 'db_conn.php';
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
        $target_dir = "uploads/";
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

// Check for duplicate contact no
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
            echo "Duplicate email addresses are not allowed.";
        } else {
            echo "Error inserting user: " . $stmt->error;
        }
    }
    $stmt->close();
} else {
    echo "Error preparing statement: " . $conn->error;
}


}

$users = $conn->query("SELECT user_id, first_name, middle_name, last_name, suffix, COALESCE(schedule, 'Unassigned') AS schedule, COALESCE(shift, 'Unassigned') AS shift, role, account_status FROM users");
if (!$users) {
    die("Error fetching users: " . $conn->error);
}



$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style type="text/css">
        #userModal, #successModal {
            display: none;
        }

        #successModal, #userModal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        #userModal .modal-content, #successModal .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            width: 400px;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            float: right;
            margin-top: -10px;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        form label {
            text-transform: capitalize;
        }
    </style>
</head>
<body>

<button onclick="showModal()">+ Create New</button>
<input type="text" id="search" placeholder="Search" oninput="liveSearch()">

<!-- Modal for success message -->
<div id="successModal" class="modal">
    <div class="modal-content">
        <p id="successMessage"></p>
        <button onclick="closeSuccessModal()">Close</button>
    </div>
</div>

<!-- Modal for creating user -->
<div id="userModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="hideModal()">&times;</span>
        <form action="" method="POST" enctype="multipart/form-data">
            <label>Upload Photo:</label>
            <input type="file" name="profile_photo" accept="image/*" onchange="previewImage(event)" ><br>
            <img id="profilePreview" src="#" alt="Profile Preview" style="display:none; width:100px; height:100px;"><br>
            <label>User ID:</label>
            <input type="text" value="<?= $user_id ?>" readonly><br>

            <p>Personal Information </p>
            <label>First Name:</label>
            <input type="text" name="first_name" required onkeypress="return noNumbers(event)" placeholder="First Name" oninput="capitalizeInput(event)"><br>

            <label>Middle Name (Optional):</label>
            <input type="text" name="middle_name" onkeypress="return noNumbers(event)" placeholder="Middle Name" oninput="capitalizeInput(event)"><br>

            <label>Last Name:</label>
            <input type="text" name="last_name" required onkeypress="return noNumbers(event)" placeholder="Last Name" oninput="capitalizeInput(event)"><br>

            <label>Suffix (Optional):</label>
            <input type="text" name="suffix" onkeypress="return noNumbers(event)" placeholder="Jr Sr II III IV" oninput="capitalizeInput(event)"><br>

            <label>Contact No:</label>
            <input type="text" name="contact_no" pattern="^\d{11}$" title="Contact number must be exactly 11 digits" required maxlength="11" onkeypress="return onlyNumbers(event)" placeholder="Contact Number" oninput="capitalizeInput(event)"><br>

            <label>Sex:</label>
            <label><input type="radio" name="sex" value="Male" required> Male</label>
            <label><input type="radio" name="sex" value="Female" required> Female</label><br>

            <label>Birthdate:</label>
            <input type="date" name="birthdate" required><br>

            <label>Email:</label>
            <input type="text" name="email" pattern="^[a-zA-Z0-9]+@gmail\.com$" required placeholder="example@gmail.com"><br>

            <p>Addresss </p>
            <label for="city">Municipality:</label>
            <select name="city" required>
            <option value="Quezon City">Quezon City</option>
            </select><br>

            <label for="barangay">Barangay</label>
            <select name="barangay" required>
            <option value="Bagbag">Bagbag</option>
            </select><br>

            <label for="house_lot_number">House/Lot Number:</label>
            <input type="text" name="house_lot_number" required placeholder="House/Lot Number" oninput="capitalizeInput(event)"><br>

            <label for="street_subdivision_name">Street/Subdivision Name:</label>
            <input type="text" name="street_subdivision_name" required placeholder="Street/Subdivision Name" oninput="capitalizeInput(event)"><br>

            <p>Job Description</p>
            <label>Role:</label>
            <select name="role" required>
                <option value="" disabled selected>Select Role</option>
                <option value="Admin">Admin</option>
                <option value="Local Authority">Local Authority</option>
            </select><br>

            <label>Position:</label>
            <select name="position" required>
                <option value="" disabled selected>Select Position</option>
                <option value="Executive Officer">Executive Officer</option>
            </select><br>

            <button type="submit">Create User</button>
        </form>
    </div>
</div>

<!-- Table of registered accounts -->
<table id="userTable" border="1">
    <thead>
        <tr>
            <th>User ID</th>
            <th>First Name</th>
            <th>Middle Name</th>
            <th>Last Name</th>
            <th>Suffix</th>
            <th>Time Schedule</th>
            <th>Day Schedule</th>
            <th>Role</th>
            <th>Account Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while($user = $users->fetch_assoc()): ?>
            <tr>
                <td><?= $user['user_id'] ?></td>
                <td><?= $user['first_name'] ?></td>
                <td><?= $user['middle_name'] ?></td>
                <td><?= $user['last_name'] ?></td>
                <td><?= $user['suffix'] ?></td>
                <td><?= $user['schedule'] ?></td>
                <td><?= $user['shift'] ?></td>
                <td><?= $user['role'] ?></td>
                <td><?= $user['account_status'] ?></td>
                <td>
                    <a href="edit_user.php?user_id=<?= $user['user_id'] ?>"><button>Edit</button></a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<script> //This script is for the modals
// Show 
function showModal() {
    document.getElementById("userModal").style.display = "flex";
    document.querySelector("form").reset();
    document.getElementById('profilePreview').style.display = 'none';
}

// Hide 
function hideModal() {
    document.getElementById("userModal").style.display = "none";
}

// Preview 
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

// Close
function closeSuccessModal() {
    document.getElementById("successModal").style.display = "none";
}

// Search
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
<script>// This script is for the success modal
function showSuccessModal(message) {
    const modal = document.getElementById('successModal');
    const messageElement = document.getElementById('successMessage');
    messageElement.textContent = message; // Display the success message
    modal.style.display = 'flex'; // Show the modal
}

function closeSuccessModal() {
    document.getElementById('successModal').style.display = 'none'; // Hide the modal
}
<?php if ($successMessage): ?>
    showSuccessModal("<?= $successMessage ?>");
<?php endif; ?>
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
