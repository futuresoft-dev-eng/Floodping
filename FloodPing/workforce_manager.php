<?php
include 'db_conn.php';
$userData = [];
if (isset($_GET['edit_user_id'])) {
    $userId = $_GET['edit_user_id'];
    $sql = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign_schedule'])) {
    $userId = $_POST['user_id'];
    $schedule = [];
    $shift = [];

    foreach (["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"] as $day) {
        if (isset($_POST[$day])) {
            $schedule[] = strtoupper(substr($day, 0, 3));
            $time = $_POST[$day . "_time"];

            switch ($time) {
                case "08:00 AM - 5:00 PM":
                    if (!in_array("Morning Shift", $shift)) $shift[] = "Morning Shift";
                    break;
                case "2:00 PM - 11:00 PM":
                    if (!in_array("Mid Shift", $shift)) $shift[] = "Mid Shift";
                    break;
                case "11:00 PM - 8:00 AM":
                    if (!in_array("Night Shift", $shift)) $shift[] = "Night Shift";
                    break;
            }
        }
    }
    $scheduleStr = implode(", ", $schedule);
    $shiftStr = implode(", ", $shift);

    $sql = "UPDATE users SET schedule = ?, shift = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $scheduleStr, $shiftStr, $userId);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Schedule and shift updated successfully!'); window.location.href = 'workforce_manager.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workforce Management</title>
</head>
<body>

<h2>Workforce Management</h2>

<!-- Personal Information -->
<h3>Personal Information</h3>
<form method="post">
    <input type="hidden" name="user_id" value="<?= $userData['user_id'] ?? '' ?>">
    <label>Profile Photo:</label><br>
    <img id="profilePhotoPreview" src="<?= $userData['profile_photo'] ?? '#' ?>" alt="Profile Photo" width="100"><br>
    <label>User ID: <input type="text" name="user_id" value="<?= $userData['user_id'] ?? '' ?>" readonly></label><br>
    <label>First Name: <input type="text" name="first_name" value="<?= $userData['first_name'] ?? '' ?>" readonly></label><br>
    <label>Last Name: <input type="text" name="last_name" value="<?= $userData['last_name'] ?? '' ?>" readonly></label><br>
    <label>Suffix: <input type="text" name="suffix" value="<?= $userData['suffix'] ?? '' ?>" readonly></label><br>
    <label>Sex: <input type="text" name="sex" value="<?= $userData['sex'] ?? '' ?>" readonly></label><br>
    <label>Mobile Number: <input type="text" name="contact_no" value="<?= $userData['contact_no'] ?? '' ?>" readonly></label><br>
    <label>Email: <input type="text" name="email" value="<?= $userData['email'] ?? '' ?>" readonly></label><br>
    <label>Role: <input type="text" name="role" value="<?= $userData['role'] ?? '' ?>" readonly></label><br>
    <label>Position: <input type="text" name="position" value="<?= $userData['position'] ?? '' ?>" readonly></label><br>

    <h3>Work Day Shift</h3>
<?php
$days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
$existingSchedule = explode(", ", $userData['schedule'] ?? '');
$existingShifts = json_decode($userData['shift'] ?? '{}', true);

foreach ($days as $day) {
    $dayShort = strtoupper(substr($day, 0, 3)); 
    $checked = in_array($dayShort, $existingSchedule) ? "checked" : "";
    $selectedShift = $existingShifts[$day] ?? ""; 
    
    echo "<label>
            <input type='checkbox' name='$day' $checked> $day
            <select name='${day}_time'>
                <option value='08:00 AM - 5:00 PM' " . ($selectedShift == "08:00 AM - 5:00 PM" ? "selected" : "") . ">Morning Shift - 08:00 AM - 5:00 PM</option>
                <option value='2:00 PM - 11:00 PM' " . ($selectedShift == "2:00 PM - 11:00 PM" ? "selected" : "") . ">Mid Shift - 2:00 PM - 11:00 PM</option>
                <option value='11:00 PM - 8:00 AM' " . ($selectedShift == "11:00 PM - 8:00 AM" ? "selected" : "") . ">Night Shift - 11:00 PM - 8:00 AM</option>
            </select>
          </label><br>";
}
?>


    <button type="submit" name="assign_schedule">ASSIGN</button>
</form>

<!-- Unassigned Accounts -->
<h3>Unassigned Accounts</h3>
<table border="1">
    <tr>
        <th>User ID</th>
        <th>First Name</th>
        <th>Middle Name</th>
        <th>Last Name</th>
        <th>Suffix</th>
        <th>Day Schedule</th>
        <th>Time Schedule</th>
        <th>Role</th>
        <th>Account Status</th>
        <th>Action</th>
    </tr>
    <?php
    $result = $conn->query("SELECT user_id, first_name, middle_name, last_name, suffix, schedule, shift, role, account_status FROM users WHERE schedule IS NULL OR shift IS NULL");
    while ($row = $result->fetch_assoc()) {
        $schedule = $row['schedule'] ?: "Unassigned";
        $shift = $row['shift'] ?: "Unassigned";

        echo "<tr>
                <td>{$row['user_id']}</td>
                <td>{$row['first_name']}</td>
                <td>{$row['middle_name']}</td>
                <td>{$row['last_name']}</td>
                <td>{$row['suffix']}</td>
                <td>{$schedule}</td>
                <td>{$shift}</td>
                <td>{$row['role']}</td>
                <td>{$row['account_status']}</td>
                <td><a href='?edit_user_id={$row['user_id']}'>Edit</a></td>
              </tr>";
    }
    ?>
</table>



<!-- User Accounts -->
<h3>User Accounts</h3>
<table border="1">
    <tr>
        <th>User ID</th>
        <th>First Name</th>
        <th>Middle Name</th>
        <th>Last Name</th>
        <th>Suffix</th>
        <th>Day Schedule</th>
        <th>Time Schedule</th>
        <th>Role</th>
        <th>Account Status</th>
        <th>Action</th>
    </tr>
    <?php
    $result = $conn->query("SELECT user_id, first_name, middle_name, last_name, suffix, schedule, shift, role, account_status FROM users WHERE schedule IS NOT NULL AND shift IS NOT NULL");
    while ($row = $result->fetch_assoc()) {
        $schedule = $row['schedule'] ?: "Unassigned";
        $shift = $row['shift'] ?: "Unassigned";

        echo "<tr>
                <td>{$row['user_id']}</td>
                <td>{$row['first_name']}</td>
                <td>{$row['middle_name']}</td>
                <td>{$row['last_name']}</td>
                <td>{$row['suffix']}</td>
                <td>{$schedule}</td>
                <td>{$shift}</td>
                <td>{$row['role']}</td>
                <td>{$row['account_status']}</td>
                <td><a href='?edit_user_id={$row['user_id']}'>Edit</a></td>
              </tr>";
    }
    ?>
</table>


</body>
</html>
