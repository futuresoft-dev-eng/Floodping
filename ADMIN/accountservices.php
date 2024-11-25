<?php
include_once('../adminsidebar.php');
include_once('../db/db_conn.php');
?>
<title>Residents List</title>
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
    .view-btn, .export-btn, .delete-btn,
    .deactivate-btn, .reactivate-btn  {
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
        margin-left: 60%;
        margin-top:20px;
    }
    .delete-btn {
        background-color: #EA3323;
        margin-top:20px;
    }
    .deactivate-btn{
        background-color: #EB8817;
    }
    .reactivate-btn{
        background-color: #59C447;
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
    button:disabled, .export-btn:disabled {
        background-color: #C5C5C5;
        pointer-events: none; 
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

// Display delete status message
if (isset($_GET['delete_status'])) {
    echo "
    <div id='deleteMessage' style='display: flex; align-items: center; justify-content: center;
        margin: 20px auto; max-width: 20%; border: 2px solid #E74C3C; background-color: #FDEDEC;
        color: #E74C3C; padding: 5px 20px; border-radius: 8px; font-size: 16px;'>
        <span style='font-size: 24px; margin-right: 10px;'>✔</span>
        <span>" . htmlspecialchars($_GET['delete_status']) . "</span>
    </div>
    <script>
        setTimeout(() => {
            const messageDiv = document.getElementById('deleteMessage');
            if (messageDiv) {
                messageDiv.style.transition = 'opacity 2s';
                messageDiv.style.opacity = '0';
            }
        }, 2000);
        setTimeout(() => {
            const messageDiv = document.getElementById('deleteMessage');
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
                   LEFT JOIN categories c ON r.account_status_id = c.category_id
                   ORDER BY r.id DESC";                   
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
             <!-- Display number of selected rows -->
            <span id="selectedCount" class="selected-count">0 Selected</span>

             <!-- Export Data -->
             <a href="/floodping/ADMIN/export_residents.php" class="export-btn disabled">
             <span class="material-symbols-rounded">download</span> EXPORT
            </a>

              <!-- Deactivate -->
        <button id="deactivateSelectedBtn" class="status-btn deactivate-btn">
            <span class="material-symbols-rounded">person_off</span> DEACTIVATE
        </button>

        <!-- Reactivate -->
        <button id="reactivateSelectedBtn" class="status-btn reactivate-btn">
            <span class="material-symbols-rounded">notifications_active</span> REACTIVATE
        </button>
            <form id="statusUpdateForm" action="/floodping/ADMIN/updateresidents_status.php" method="POST" style="display: none;">
                <input type="hidden" name="selected_residents" id="statusResidentsInput">
                <input type="hidden" name="action" id="statusActionInput">
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
</script>
