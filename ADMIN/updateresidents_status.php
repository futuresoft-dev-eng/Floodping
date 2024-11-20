<?php
include_once('../db/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get selected residents and action from the form
    $selectedResidents = isset($_POST['selected_residents']) ? $_POST['selected_residents'] : [];
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    // Validate inputs
    if (empty($selectedResidents)) {
        echo "No residents selected.";
        exit();
    }
    if (!in_array($action, ['deactivate', 'reactivate'])) {
        echo "Invalid action.";
        exit();
    }

    // Determine the new status based on the action
    $newStatus = $action === 'deactivate' ? 'Deactivated' : 'Active';

    // Sanitize resident IDs
    $residentIds = implode(',', array_map('intval', $selectedResidents));

    // Prepare the query to update the selected residents
    $query = "
        UPDATE residents
        SET account_status_id = (
            SELECT category_id 
            FROM categories 
            WHERE category_value = ? AND category_type = 'account_status' 
            LIMIT 1
        )
        WHERE id IN ($residentIds)
    ";

    // Use prepared statements for security
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param('s', $newStatus);

        if ($stmt->execute()) {
            $affectedRows = $stmt->affected_rows;
            header("Location: /floodping/ADMIN/accountservices.php?status_updated=$newStatus&updated_count=$affectedRows");
            exit();
        } else {
            echo "Error updating status: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing query: " . $conn->error;
    }
} else {
    echo "Invalid request method.";
}

$conn->close();
?>
