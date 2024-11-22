<?php
session_start();
if (isset($_SESSION['full_name']) && isset($_SESSION['role'])) {
    echo "<p>" . htmlspecialchars($_SESSION['full_name']) . "</p>";
    echo "<p>" . htmlspecialchars($_SESSION['role']) . "</p>";

    include_once('../db/db_conn.php');
    
    $users = $conn->query("SELECT user_id, first_name, middle_name, last_name, suffix, COALESCE(schedule, 'Unassigned') AS schedule, COALESCE(shift, 'Unassigned') AS shift, role, account_status FROM users");

    if (!$users) {
        die("Error fetching users: " . $conn->error);
    }

    $users_data = [];
    while ($user = $users->fetch_assoc()) {
        $users_data[] = $user;
    }

    $conn->close(); 
} else {
    echo "Please log in first.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>

<button onclick="window.location.href='create_user.php'">+ Create New</button>

<input type="text" id="search" placeholder="Search" oninput="liveSearch()">

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
        <?php foreach ($users_data as $user): ?>
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
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
