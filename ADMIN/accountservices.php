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
        margin-left: 0;
        padding: 20px;
    }
    .container {
        max-width: 100%;
    }
    .table-container {
        background-color: white;
        padding: 20px;
        border-radius: 5px;
        border: 1px solid #e0e0e0;
        margin-top: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .create-btn {
        background-color: #59C447;
        color: white;
        padding: 13px 16px;
        border: none;
        border-radius: 5px;
        font-size: 14px;
        cursor: pointer;
        margin-right: 10px;
        text-decoration: none;
    }
    .import-btn {
        display: flex;
        align-items: center;
        background-color: #4597C0;
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 5px;
        font-size: 14px;
        cursor: pointer;
        margin-right: 10px;
        text-decoration: none;
    }
    .import-btn .material-symbols-rounded {
        margin-right: 5px;
        font-size: 18px;
    }
    .dataTables_filter {
        position: relative;
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }
    .dataTables_filter input {
        width: 350px;
        padding: 8px 12px 8px 30px; 
        border-radius: 5px;
        border: 1px solid #e0e0e0;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }
    .dataTables_filter::before {
        content: '\e8b6'; 
        font-family: 'Material Symbols Rounded';
        position: absolute;
        left: 170px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 18px;
        color: #aaa;
        pointer-events: none;
    }
    #statusFilter {
        margin-left: 10px;
        padding: 5px;
        border-radius: 5px;
        border: 1px solid #e0e0e0;
    }
    .dataTables_filter label {
        margin-right: 10px;
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
    .view-btn {
        text-decoration: none;
        color: white;
        background-color: #4597C0;
        padding: 5px 10px;
        border-radius: 5px;
        border: none;
        font-size: 14px;
        display: flex;
        align-items: center;
        text-align: center;
        vertical-align: middle;
    }
    .view-btn .material-symbols-rounded {
        font-size: 18px;
        margin-right: 5px;
    }
</style>

<main class="main-content">
    <div class="container">
        <form id="importForm" action="/floodping/ADMIN/import_excel.php" method="post" enctype="multipart/form-data" style="display: none;">
            <input type="file" name="file" id="fileInput" accept=".xls, .xlsx" required>
        </form>

        <div class="button-container">
            <!-- Create New Resident -->
            <a href="/floodping/ADMIN/addresident.php" class="create-btn">
                <span class="material-symbols-rounded">add</span> CREATE NEW
            </a>

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

        <!-- Table container -->
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
    </div>
</main>

<script>
$(document).ready(function() {
    const table = $('#residentTable').DataTable({
        language: {
            search: "",
            searchPlaceholder: "     Search..."
        }
    });

    if (!$('.import-btn').length) {
        $("div.dataTables_filter").prepend(`
            <button class="import-btn">
                <span class="material-symbols-rounded">upload</span> IMPORT DATA
            </button>
        `);
    }
    
    $(document).ready(function() {
    $('.import-btn').on('click', function() {
        $('#fileInput').click(); // Trigger file input
    });

    $('#fileInput').on('change', function() {
        $('#importForm').submit(); // Submit the form
    });
});


    $('.import-btn').off('click').on('click', function() {
        $('#fileInput').click();
    });

    $('#fileInput').off('change').on('change', function() {
        $('#importForm').submit();
    });

    $('#statusFilter').on('change', function() {
        const selectedStatus = $(this).val();
        table.column(7).search(selectedStatus).draw();
    });

    $('#selectAll').on('click', function() {
        $('.rowCheckbox').prop('checked', this.checked);
    });

    $('.rowCheckbox').on('click', function() {
        $('#selectAll').prop('checked', $('.rowCheckbox:checked').length === $('.rowCheckbox').length);
    });
});
</script>
