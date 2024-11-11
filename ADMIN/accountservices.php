<?php
include_once('../adminsidebar.php');
include_once('../db/connection.php');
?>

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

/* Customize DataTables search bar */
.dataTables_filter input {
    width: 350px;          /* Set width */
    padding: 8px;          /* Add padding for better appearance */
    border-radius: 5px;    /* Rounded corners */
    border: 1px solid #e0e0e0;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
}

/* Optional: Adjust the search label styling */
.dataTables_filter label {
    color: #02476A;
    font-weight: bold;
}

.table th {
    color: #02476A;
    background-color: #E8F3F8;
    font-weight: bold;
}

.edit-btn {
    color: white;
    background-color: #4597C0;
    padding: 5px 10px;
    border-radius: 5px;
    border: none;
    font-size: 14px;
    display: flex;
    align-items: center;
}

.edit-btn .material-symbols-rounded {
    font-size: 18px;
    margin-right: 5px;
}
</style>

<main class="main-content">
    <div class="container">
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
    $('#residentTable').DataTable();
});
</script>
