<?php
include_once('../adminsidebar.php');
include_once('../db/connection.php');
?>
<title>Residents List</title>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Rounded" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<style>
    .main-content {
        margin-left: 0; padding: 20px;
    }
    .container {
        max-width: 100%;
    }
    .table-container {
        background-color: white;
        padding: 20px;
        border-radius: 5px;
        border: 1px solid #F6F6F6;
        margin-top: 30px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .dataTables_filter {
        position: relative;
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }
    .dataTables_filter input {
        width: 350px;
        padding: 80px 12px 8px 30px; 
        border-radius: 5px;
        border: 1px solid #02476A;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }
    .dataTables_filter::before {
        content: '\e8b6'; 
        font-family: 'Material Symbols Rounded';
        position: absolute;
        left: 325px;
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
    }
    .table th {
        color: #02476A;
        background-color: #E8F3F8;
        font-weight: bold;
    }
    .table tbody td:last-child {
        text-align: center;
        display: flex;
        justify-content: center;
    }
    .create-btn, .import-btn,
    .view-btn, .export-btn,  .delete-btn  {
        color: white;
        padding: 12px 16px;
        border: none;
        border-radius: 5px;
        font-size: 14px;
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
    }
    .import-btn {
        background-color: #4597C0;
    }
    .view-btn {
        background-color: #4597C0;
        padding: 5px 15px;
    }
    .export-btn {
        background-color: #0288D1;
        margin-left: 70%;
        margin-top:20px;
    }
    .delete-btn {
        background-color: #EA3323;
        margin-top:20px;
    }
    .export-btn .material-symbols-rounded,
    .delete-btn .material-symbols-rounded,
    .create-btn .material-symbols-rounded,
    .view-btn .material-symbols-rounded,
    .import-btn .material-symbols-rounded {
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
    <div class="container">
        <form id="importForm" action="/floodping/ADMIN/import_excel.php" method="post" enctype="multipart/form-data" style="display: none;">
            <input type="file" name="file" id="fileInput" accept=".xls, .xlsx" required>
        </form>
        
           <!-- Filter -->
            <label for="statusFilter">Filter by Status:</label>
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
        </div>
        <!-- Table -->
        <div class="table-container">
            <table id="residentTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>Resident ID</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Last Name</th>
                        <th>Suffix</th>
                        <th>Mobile Number</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $residentQuery = "
                    SELECT r.resident_id, r.first_name, r.middle_name, r.last_name, r.suffix, r.mobile_number, 
                    c.category_value AS account_status 
                    FROM residents r
                    LEFT JOIN categories c ON r.account_status_id = c.category_id";
                    $residentResult = mysqli_query($conn, $residentQuery);

                    if ($residentResult && mysqli_num_rows($residentResult) > 0) {
                        while ($residentRow = mysqli_fetch_assoc($residentResult)) {
                            echo "<tr>";
                            echo "<td><input type='checkbox' class='rowCheckbox' value='{$residentRow['resident_id']}'></td>";
                            echo "<td>{$residentRow['resident_id']}</td>";
                            echo "<td>{$residentRow['first_name']}</td>";
                            echo "<td>{$residentRow['middle_name']}</td>";
                            echo "<td>{$residentRow['last_name']}</td>";
                            echo "<td>{$residentRow['suffix']}</td>";
                            echo "<td>{$residentRow['mobile_number']}</td>";
                            echo "<td>" . ($residentRow['account_status'] ?? 'Unknown') . "</td>";
                            echo "<td><a href='/floodping/ADMIN/viewresident.php?resident_id={$residentRow['resident_id']}' class='view-btn'><span class='material-symbols-rounded'>visibility</span>VIEW</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9' class='text-center'>No residents found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="button-container">
             <!-- Export Data -->
            <a href="/floodping/ADMIN/export_residents.php" class="export-btn">
                <span class="material-symbols-rounded">download</span> EXPORT DATA
            </a>




            <<form method="POST" action="updateresidents_status.php">
    <table>
        <thead>
            <tr>
                <th>Select</th>
                <th>Resident Name</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch residents from the database
            include_once('../db/connection.php');
            $result = mysqli_query($conn, "SELECT id, first_name, last_name, account_status_id FROM residents");
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td><input type='checkbox' name='selected_residents[]' value='{$row['id']}'></td>";
                echo "<td>{$row['first_name']} {$row['last_name']}</td>";
                echo "<td>{$row['account_status_id']}</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <button type="submit" name="action" value="deactivate">Deactivate</button>
    <button type="submit" name="action" value="reactivate">Reactivate</button>
</form>




            <!-- Delete -->
            <button id="deleteSelectedBtn" class="delete-btn">
                <span class="material-symbols-rounded">delete</span> DELETE
            </button>
            </div>
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
    });
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
    });

    $('.rowCheckbox').on('click', function () {
        $('#selectAll').prop('checked', $('.rowCheckbox:checked').length === $('.rowCheckbox').length);
    });
    
    











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

});
</script>
