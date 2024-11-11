<?php
include_once('../adminsidebar.php');
include_once('../db/connection.php');
?>
    <title>Residents List</title>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<!-- Material Symbols for icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Rounded" rel="stylesheet">
<!-- jQuery (required for DataTables) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS -->
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

.import-btn {
    display: flex;
    align-items: center;
    background-color: #59C447;
    ; 
    color: white;
    padding: 8px 16px;
    border: none;
    border-radius: 5px;
    font-size: 14px;
    cursor: pointer;
    margin-right: 10px;
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

/* Edit button styling */
.edit-btn {
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

.edit-btn .material-symbols-rounded {
    font-size: 18px;
    margin-right: 5px;
}

</style>

<main class="main-content">
    <div class="container">
        <!-- Import Form (Hidden) -->
        <form id="importForm" action="import_excel.php" method="post" enctype="multipart/form-data" style="display: none;">
            <input type="file" name="file" id="fileInput" accept=".xls, .xlsx" required onchange="document.getElementById('importForm').submit();">
        </form>


        <!-- Table container -->
        <div class="table-container">
            <table id="residentTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Resident ID</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Last Name</th>
                        <th>Suffix</th>
                        <th>Contact No.</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $residentQuery = "SELECT resident_id, first_name, middle_name, last_name, suffix, mobile_number, 'Active' AS status FROM residents";
                    $residentResult = mysqli_query($conn, $residentQuery);

                    if ($residentResult && mysqli_num_rows($residentResult) > 0) {
                        while ($residentRow = mysqli_fetch_assoc($residentResult)) {
                            echo "<tr>";
                            echo "<td>{$residentRow['resident_id']}</td>";
                            echo "<td>{$residentRow['first_name']}</td>";
                            echo "<td>{$residentRow['middle_name']}</td>";
                            echo "<td>{$residentRow['last_name']}</td>";
                            echo "<td>{$residentRow['suffix']}</td>";
                            echo "<td>{$residentRow['mobile_number']}</td>";
                            echo "<td>{$residentRow['status']}</td>";
                            echo "<td><button class='edit-btn'><span class='material-symbols-rounded'>edit</span>EDIT</button></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8' class='text-center'>No residents found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<script>
$(document).ready(function() {
    $('#residentTable').DataTable({
        language: {
            search: "", 
            searchPlaceholder: "     Search..." 
        }
    });

    $("div.dataTables_filter").prepend(`
        <button class="import-btn">
            <span class="material-symbols-rounded">upload</span> IMPORT DATA
        </button>
    `);
     // Click event for the IMPORT DATA button
     $('.import-btn').on('click', function() {
        $('#fileInput').click(); // Open the file input
    });

    // Event to automatically submit the form when a file is selected
    $('#fileInput').on('change', function() {
        $('#importForm').submit(); // Submit the form
    });
});

</script>
