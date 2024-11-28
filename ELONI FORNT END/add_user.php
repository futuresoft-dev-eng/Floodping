<?php
session_start();
include 'db_conn.php';
include 'adminsidebar.php';

if (isset($_SESSION['full_name']) && isset($_SESSION['role'])) {
    // Commented out the name and role display
    // echo "<p class='session-info'>" . htmlspecialchars($_SESSION['full_name']) . "</p>";
    // echo "<p class='session-role'>" . htmlspecialchars($_SESSION['role']) . "</p>";

    // Check if the database connection is successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query users data
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


<title>User Management</title>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Rounded" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<style>
    .main-content {
        margin-left: 30px; padding: 20px;
    }
    .container {
        max-width: 100%;
    }
    .title h3 {
        font-size: 25px;
        font-weight: bold;
        color: #02476A;
        margin: 30px 0px 0px 160px !important;   
    }
    .table-container {
        background-color: white;
        padding: 20px;
        border-radius: 5px;
        border: 1px solid #F6F6F6;
        margin-top: 30px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        max-width: 90%;
        margin-left: 145px;
    }

    #residentTable {
        width: 100%;
        margin-left: 0px;
    }
    .dataTables_filter {
        position: relative;
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }
    .dataTables_filter input {
        width: 350px;
        padding: 8px !important;
        padding-left: 25px !important;
        border-radius: 5px;
        border: 1px solid #02476A;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        
    }
    .dataTables_filter::before {
        content: '\e8b6'; 
        font-family: 'Material Symbols Rounded';
        position: absolute;
        left: 210px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 18px;
        color: #02476A;
        pointer-events: none;
    }
    #statusFilter {
        margin-left: 10px;
        padding: 5px;
        border-radius: 5px;
        border: 1px solid #02476A;
        color: #02476A;
    }
    .dataTables_filter label {
        margin-right: 5px;
        font-weight: bold;
        font-size: 13px;
    }
    .table th {
        color: #02476A;
        background-color: #E8F3F8;
        font-weight: bold;
        font-size: 13px;
        width: 680px;
    }

    .table td {
        font-size: 13px;
    }

    .table tbody td:last-child {
        text-align: center;
        display: flex;
        justify-content: center;
    }
    .create-btn, .import-btn,
    .edit-btn, .export-btn, .delete-btn,
    .deactivate-btn, .reactivate-btn  {
        color: white;
        padding: 10px 16px;
        border: none;
        border-radius: 5px;
        font-size: 13px;
        cursor: pointer;
        margin-right: 10px;
        text-decoration: none;
        align-items: center;
        text-align: center;
        vertical-align: middle;
        display: inline-flex;
        transition: background-color 0.3s, box-shadow 0.3s;
    }
    .create-btn {
        background-color: #59C447;
        margin-left: -100px;
        position: absolute;
    }
    .import-btn {
        background-color: #4597C0;
        margin-left: 50px;
    }
    .edit-btn {
        background-color: #4597C0;
        padding: 5px 15px;
    }

    .export-btn .material-symbols-rounded,
    .delete-btn .material-symbols-rounded,
    .create-btn .material-symbols-rounded,
    .view-btn .material-symbols-rounded,
    .import-btn .material-symbols-rounded,
    .deactivate-btn .material-symbols-rounded,
    .reactivate-btn .material-symbols-rounded {      
        margin-right: 5px;
        font-size: 18px;
    }
   
    #selectedCount {
        font-size: 14px;
        margin: 20px 0px 0px 160px;
        position: absolute;
    }

    .dataTables_paginate .paginate_button {
        font-size: 13px; 
        margin-top: 20px;
    }

    .dataTables_info {
        font-size: 13px; 
        margin-top: 20px;
    }

    .dataTables_length select {
        font-size: 13px; 
    }

    .buttons-container {
        display: flex;
        margin: 20px 0;
        position: absolute;
        top: 87px;
        left: 275px;
        border-radius: none !important;
    }

    .navigation-btn {
        min-width: 615px;
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
        
    }

    #residentsBtn {
        border-top-right-radius: 10px;
        
        background-color: #FFFFFF;
        color: #02476A;
        border: 1px solid #ccc;
    }
    
    .edit-btn .material-symbols-rounded {
        margin-right: 5px;  
        font-size: 18px;  
    }
 /* Responsive */
 @media (max-width: 768px) {
            .main-content {
                margin-left: 0; 
            }
            .table-container {
                padding: 10px;
            }
            h1 {
                font-size: 1.5rem;
            }
        }
</style>

<main class="main-content">
<div class="title">
<h3>USER MANAGEMENT</h3>
</div>

<div class="buttons-container">
    <button class="navigation-btn active" id="userAccountsBtn" onclick="activateButton('userAccountsBtn', 'add_user.php')">User Accounts</button>
    <button class="navigation-btn" id="residentsBtn" onclick="activateButton('residentsBtn', 'accountservices.php')">Resident List</button>
</div>

    <div class="container">
        <form id="importForm" action="/floodping/ADMIN/import_excel.php" method="post" enctype="multipart/form-data" style="display: none;">
            <input type="file" name="file" id="fileInput" accept=".xls, .xlsx" required>
        </form>
        
        <!-- Filter -->
        <select id="statusFilter">
            <option value="">All</option>
            <?php
            $statusQuery = "SELECT DISTINCT category_value FROM categories WHERE category_type = 'account_status'";
            $statusResult = mysqli_query($conn, $statusQuery);
            if ($statusResult && mysqli_num_rows($statusResult) > 0) {
                while ($statusRow = mysqli_fetch_assoc($statusResult)) {
                    echo "<option value='{$statusRow['category_value']}'>{$statusRow['category_value']}</option>";
                }
            }
            ?>
        </select>

        <!-- Display success/error messages -->
        <?php
        // Display success message
        if (isset($_GET['message'])) {
            echo "
            <div id='temporaryMessage' style='display: flex; align-items: center; justify-content: center;
                margin: 20px auto; max-width: 20%; border: 2px solid #59C447; background-color: #F0FFF0;
                color: #59C447; padding: 5px 20px; border-radius: 8px; font-size: 16px;' >
                <span style='font-size: 24px; margin-right: 10px;'>✔</span>
                <span>" . htmlspecialchars($_GET['message']) . "</span>
            </div>
            <script>
                setTimeout(() => {
                    const messageDiv = document.getElementById('temporaryMessage');
                    if (messageDiv) {
                        messageDiv.style.transition = 'opacity 2s';
                        messageDiv.style.opacity = '0';
                    }
                }, 2000);
                setTimeout(() => {
                    const messageDiv = document.getElementById('temporaryMessage');
                    if (messageDiv) {
                        messageDiv.remove();
                    }
                }, 4000); 
            </script>
            ";
        }

        // Display error message
        if (isset($_GET['error'])) {
            echo "
            <div id='errorMessage' style='display: flex; align-items: center; justify-content: center;
                margin: 20px auto; max-width: 20%; border: 2px solid #E74C3C; background-color: #FDEDEC;
                color: #E74C3C; padding: 5px 20px; border-radius: 8px; font-size: 16px;'>
                <span style='font-size: 24px; margin-right: 10px;'>✖</span>
                <span>" . htmlspecialchars($_GET['error']) . "</span>
            </div>
            <script>
                setTimeout(() => {
                    const messageDiv = document.getElementById('errorMessage');
                    if (messageDiv) {
                        messageDiv.style.transition = 'opacity 2s';
                        messageDiv.style.opacity = '0';
                    }
                }, 2000);
                setTimeout(() => {
                    const messageDiv = document.getElementById('errorMessage');
                    if (messageDiv) {
                        messageDiv.remove();
                    }
                }, 4000);
            </script>
            ";
        }
        ?>

        <!-- Table -->
        <div class="table-container">
            <table id="residentTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
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
            <!-- Add a column for the checkbox -->
            <td><input type="checkbox" class="rowCheckbox" value="<?= $user['user_id'] ?>"></td>
            <td><?= htmlspecialchars($user['user_id']) ?></td>
            <td><?= htmlspecialchars($user['first_name']) ?></td>
            <td><?= htmlspecialchars($user['middle_name']) ?></td>
            <td><?= htmlspecialchars($user['last_name']) ?></td>
            <td><?= htmlspecialchars($user['suffix']) ?></td>
            <td><?= htmlspecialchars($user['schedule']) ?></td>
            <td><?= htmlspecialchars($user['shift']) ?></td>
            <td><?= htmlspecialchars($user['role']) ?></td>
            <td><?= htmlspecialchars($user['account_status']) ?></td>
            <td>
            <a href="edit_user.php?user_id=<?= $user['user_id'] ?>">
                <button class="edit-btn">
                <span class="material-symbols-rounded">edit</span> Edit
            </button>
            </a>
          </td>
        </tr>
    <?php endforeach; ?>
</tbody>
</table>
</div>

        <form id="statusUpdateForm" action="/floodping/ADMIN/updateresidents_status.php" method="POST" style="display: none;">
            <input type="hidden" name="selected_residents" id="statusResidentsInput">
            <input type="hidden" name="action" id="statusActionInput">
        </form>

        <form id="deleteForm" action="/floodping/ADMIN/delete_residents.php" method="POST" style="display: none;">
            <input type="hidden" name="selected_residents" id="selectedResidentsInput">
        </form>
    </div>
</main>

<script>
$(document).ready(function () {
    const table = $('#residentTable').DataTable({
        language: {
            search: "",
            searchPlaceholder: "     Search...",
        },
        stateSave: true
    });

    $('#deactivateSelectedBtn, #reactivateSelectedBtn, #deleteSelectedBtn, .export-btn').prop('disabled', true);
    $('.export-btn').css('background-color', '#C5C5C5');

    if (!$('.import-btn').length) {
        $("div.dataTables_filter").prepend(`
        <!-- Create New Resident -->
            <a href="/floodping/ADMIN/addresident.php" class="create-btn">
                <span class="material-symbols-rounded">add</span> CREATE NEW
            </a>
            <button type="button" class="import-btn">
                <span class="material-symbols-rounded">upload</span> IMPORT DATA
            </button>
        `);
    }
    $('.import-btn').off('click').on('click', function () {
        $('#fileInput').click();
    });

    $('#fileInput').off('change').on('change', function () {
        if (this.files.length > 0) {
            $('#importForm').submit(); 
        }
    });
    $('#deleteSelectedBtn').on('click', function () {
        const selectedResidents = [];
        $('.rowCheckbox:checked').each(function () {
            selectedResidents.push($(this).val());
        });
        if (selectedResidents.length > 0) {
            if (confirm('Are you sure you want to delete the selected residents?')) {
                $('#selectedResidentsInput').val(JSON.stringify(selectedResidents));
                $('#deleteForm').submit();
            }
        } else {
            alert('No residents selected for deletion.');
        }
    });
    $('#statusFilter').on('change', function () {
        const selectedStatus = $(this).val();
        table.column(7).search(selectedStatus).draw();
    });
    $('#selectAll').on('click', function () {
        $('.rowCheckbox').prop('checked', this.checked);
        toggleButtons();  
    });
    $('.rowCheckbox').on('click', function () {
        $('#selectAll').prop('checked', $('.rowCheckbox:checked').length === $('.rowCheckbox').length);
        toggleButtons();  
    });
    function toggleButtons() {
        const selectedResidents = $('.rowCheckbox:checked').length;  
        $('#deactivateSelectedBtn, #reactivateSelectedBtn, #deleteSelectedBtn').prop('disabled', selectedResidents === 0);
        
        if (selectedResidents > 0) {
            $('.export-btn').css('pointer-events', 'auto'); 
            $('.export-btn').css('background-color', ''); 
        } else {
            $('.export-btn').css('pointer-events', 'none'); 
            $('.export-btn').css('background-color', '#C5C5C5'); 
        }

        $('#selectedCount').text(selectedResidents + ' Selected');
    }
    $('#deactivateSelectedBtn').on('click', function () {
        updateStatus('deactivate');
    });
    $('#reactivateSelectedBtn').on('click', function () {
        updateStatus('reactivate');
    });
    function updateStatus(action) {
        const selectedResidents = [];
        $('.rowCheckbox:checked').each(function () {
            selectedResidents.push($(this).val());
        });
        if (selectedResidents.length > 0) {
            const confirmMessage = action === 'deactivate' ? 
                'Are you sure you want to deactivate the selected residents?' : 
                'Are you sure you want to reactivate the selected residents?';
            if (confirm(confirmMessage)) {
                $('#statusResidentsInput').val(JSON.stringify(selectedResidents));
                $('#statusActionInput').val(action);
                $('#statusUpdateForm').submit();
            }
        } else {
            alert('No residents selected for status update.');
        }
    }
    table.on('draw', function () {
        toggleButtons();  
    });
});

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
