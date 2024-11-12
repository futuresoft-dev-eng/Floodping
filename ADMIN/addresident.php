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

$accountStatusQuery = "SELECT category_id, category_value FROM categories WHERE category_type = 'account_status'";
$accountStatusResult = mysqli_query($conn, $accountStatusQuery);


// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
    $account_status = $_POST['account_status'];


    $profile_photo_path = '';
    if (!empty($_FILES['profile_photo']['name'])) {
        $photo_name = $_FILES['profile_photo']['name'];
        $target_dir = "../uploads/";
        $profile_photo_path = $target_dir . basename($photo_name);
        if (!move_uploaded_file($_FILES['profile_photo']['tmp_name'], $profile_photo_path)) {
            echo "Error uploading profile photo.";
        }
    }

    // Insert new resident's 
    $sql = "INSERT INTO residents (first_name, middle_name, last_name, suffix, sex_id, date_of_birth, mobile_number, email_address, civil_status_id, socioeconomic_category_id, health_status_id, house_lot_number, street_subdivision_name, barangay, municipality, account_status_id, profile_photo_path)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param( "ssssississssssiss", $first_name, $middle_name, $last_name, $suffix, $sex, $date_of_birth, $mobile_number, $email_address, $civil_status,               
        $socioeconomic_category, $health_status, $house_lot_number, $street_subdivision_name, $barangay, $municipality, $account_status, $profile_photo_path        
    );
    
    if ($stmt->execute()) {
        echo "New resident added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Resident</title>
    <style>
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .submit-btn {
            background-color: #4597C0;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .submit-btn:hover {
            background-color: #357a99;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Add New Resident</h2>
    <form action="addresident.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" required>
        </div>
        <div class="form-group">
            <label for="middle_name">Middle Name:</label>
            <input type="text" name="middle_name">
        </div>
        <div class="form-group">
            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" required>
        </div>
        <div class="form-group">
            <label for="suffix">Suffix:</label>
            <input type="text" name="suffix">
        </div>
        <div class="form-group">
            <label for="sex">Sex:</label>
            <select name="sex" required>
                <?php while ($row = mysqli_fetch_assoc($sexResult)) : ?>
                    <option value="<?php echo htmlspecialchars($row['category_id']); ?>"><?php echo htmlspecialchars($row['category_value']); ?></option>
                    <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="date_of_birth">Date of Birth:</label>
            <input type="date" name="date_of_birth" required>
        </div>
        <div class="form-group">
            <label for="mobile_number">Mobile Number:</label>
            <input type="text" name="mobile_number">
        </div>
        <div class="form-group">
            <label for="email_address">Email Address:</label>
            <input type="email" name="email_address">
        </div>
        <div class="form-group">
            <label for="civil_status">Civil Status:</label>
            <select name="civil_status" required>
                <?php while ($row = mysqli_fetch_assoc($civilStatusResult)) : ?>
                    <option value="<?php echo htmlspecialchars($row['category_id']); ?>"><?php echo htmlspecialchars($row['category_value']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="socioeconomic_category">Socioeconomic Category:</label>
            <select name="socioeconomic_category">
                <?php while ($row = mysqli_fetch_assoc($socioeconomicResult)) : ?>
                    <option value="<?php echo htmlspecialchars($row['category_id']); ?>"><?php echo htmlspecialchars($row['category_value']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="health_status">Health Status:</label>
            <select name="health_status">
                <?php while ($row = mysqli_fetch_assoc($healthStatusResult)) : ?>
                    <option value="<?php echo htmlspecialchars($row['category_id']); ?>"><?php echo htmlspecialchars($row['category_value']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="house_lot_number">House/Lot Number:</label>
            <input type="text" name="house_lot_number">
        </div>
        <div class="form-group">
            <label for="street_subdivision_name">Street/Subdivision Name:</label>
            <input type="text" name="street_subdivision_name">
        </div>
        <div class="form-group">
            <label for="barangay">Barangay:</label>
            <input type="text" name="barangay" value="Bagbag">
        </div>
        <div class="form-group">
            <label for="municipality">Municipality:</label>
            <input type="text" name="municipality" value="Quezon City">
        </div>
        <div class="form-group">
        <label for="account_status">Account Status:</label>
        <select name="account_status">
            <?php while ($row = mysqli_fetch_assoc($accountStatusResult)) : ?>
                <option value="<?php echo htmlspecialchars($row['category_id']); ?>"><?php echo htmlspecialchars($row['category_value']); ?></option>
            <?php endwhile; ?>
        </select>
        </div>

        <div class="form-group">
            <label for="profile_photo">Upload Profile Photo:</label>
            <input type="file" name="profile_photo" accept="image/*">
        </div>
        <button type="submit" class="submit-btn">Add Resident</button>
    </form>
</div>

<?php mysqli_close($conn); ?>
</body>
</html>
