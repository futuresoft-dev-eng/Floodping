
<?php
session_start();
include_once('../db/db_conn.php');
include_once('../adminsidebar.php');

if (isset($_SESSION['full_name']) && isset($_SESSION['role'])) {
    echo "<p class='session-info'>" . htmlspecialchars($_SESSION['full_name']) . "</p>";
    echo "<p class='session-role'>" . htmlspecialchars($_SESSION['role']) . "</p>";
    
    
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

<style>
      body {
        font-family: Poppins;
        background-color: #ffff;
        margin: 0;
        padding: 0;
    }
        
    .main-container {
        width: 100%;
        margin-left: -150px !important;
        position: absolute;
        background-color: #ffff;
        border: 1px solid black;
    }

    .title h3 {
        font-size: 25px;
        font-weight: bold;
        color: #02476A;
        margin: 30px 0px 0px -1250px !important;   
    }

    #create-btn {
        width: 12%;
        height: 5%;
        border-radius: 5px;
        position: absolute;
        padding 5px;
        margin: 200px 0px 0px 950px;
        background-color: #59C447;
        font-size: 14px;
        text-transform: uppercase;    
    }

    #search {
        width: 20%;
        height: 5%;
        font-size: 14px;
        border-radius: 5px;
        position: absolute;
        padding: 5px 20px !important;
        margin: 200px 0px 0px 1150px;

    }

    #userTable {
        width: 75% !important;
        height: 50%;
        margin-top: 300px;
        margin-left: 300px;
        position: absolute;
        border-collapse: collapse;
        border-radius: 10px; 
        font-size: 12px;
           
    }

    #userTable, th, td {
        border: none;
        height: 12%;
        border-top: 1px solid #ccc;
        border-bottom: 1px solid #ccc;
        border-radius: 5px;
    }

    #userTable th:first-child, #userTable td:first-child {
        border-left: 1px solid #ccc; 
    }

    #userTable th:last-child, #userTable td:last-child {
        border-right: 1px solid #ccc; 
    }

    th, td {
        text-align: left;
        padding: 8px;
    }

    th {
        background-color: #f4f4f9;
        font-weight: bold;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tr:hover {
        background-color: #f1f1f1;
    }

    button {
        padding: 5px 30px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    button:hover {
        background-color: #0056b3;
    }

    .session-info {
        display: none;
    }

    .session-role {
        display: none;
    
    }

    .button-container {
        display: flex;
        margin: 20px 0;
        position: absolute;
        top: 90px;
        left: 300px;
        border-radius: none !important;
}

    .navigation-btn {
        min-width: 580px;
        height: 40px;
        background-color: #FFFFFF;
        color: #02476A;
        border: 1px solid #ccc;
        border-radius: none !important;
        border-top-left-radius: 0px;
        border-bottom-left-radius: 0px;
        border-top-right-radius: 0px;
        border-bottom-right-radius: 0px;
        font-size: 14px;
        text-transform: uppercase;
        cursor: pointer;
        text-transform: uppercase;
        transition: background-color 0.3s ease;
    }

    .navigation-btn.active {
        background-color: #4597C0; 
        color: white;
    }

    #userAccountsBtn {
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
    }

    #residentsBtn {
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
        background-color: #FFFFFF;
        color: #02476A;
        border: 1px solid #ccc;
    }
</style>
    
<body>
    <div class="title">
<h3>USER MANAGEMENT</h3>
</div>
<hr style="color: #ccc; width: 90%; position: absolute; margin: 70px 0px 0px -20px;">4

<div class="button-container">
    <button class="navigation-btn active" id="userAccountsBtn" onclick="activateButton('userAccountsBtn', 'add_user.php')">User Accounts</button>
    <button class="navigation-btn" id="residentsBtn" onclick="activateButton('residentsBtn', 'create_user.php')">Resident List</button>
</div>


<button id="create-btn" onclick="window.location.href='create_user.php'">+ Create New</button>

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
</div>

</body>
</html>

<script>
    function activateButton(buttonId, redirectUrl) {
        // Remove the active class from all buttons
        const buttons = document.querySelectorAll('.navigation-btn');
        buttons.forEach((btn) => {
            btn.classList.remove('active');
        });

        // Add the active class to the clicked button
        const activeButton = document.getElementById(buttonId);
        activeButton.classList.add('active');

        // Redirect to the provided URL
        if (redirectUrl) {
            window.location.href = redirectUrl;
        }
    }
</script>


