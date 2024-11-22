<?php
include_once('../db/db_conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedResidents = isset($_POST['selected_residents']) ? json_decode($_POST['selected_residents'], true) : [];
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if (empty($selectedResidents) || !is_array($selectedResidents)) {
        echo "No residents selected or invalid data format.";
        exit();
    }

    if (!in_array($action, ['deactivate', 'reactivate'])) {
        echo "Invalid action.";
        exit();
    }

    $newStatus = $action === 'deactivate' ? 'Deactivated' : 'Active';

    $residentIdsPlaceholders = implode(',', array_fill(0, count($selectedResidents), '?'));

    $residentIds = [];
    $residentLookupQuery = "SELECT id FROM residents WHERE resident_id IN ($residentIdsPlaceholders)";
    $lookupStmt = $conn->prepare($residentLookupQuery);
    if ($lookupStmt) {
        $lookupStmt->bind_param(str_repeat('s', count($selectedResidents)), ...$selectedResidents);
        $lookupStmt->execute();
        $result = $lookupStmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $residentIds[] = $row['id'];
        }
        $lookupStmt->close();
    }

    if (empty($residentIds)) {
        echo "No matching residents found.";
        exit();
    }

    $categoryQuery = "SELECT category_id FROM categories WHERE category_value = ? AND category_type = 'account_status'";
    $categoryStmt = $conn->prepare($categoryQuery);
    $categoryStmt->bind_param('s', $newStatus);
    $categoryStmt->execute();
    $categoryStmt->bind_result($categoryId);
    if (!$categoryStmt->fetch()) {
        echo "Category ID not found for status: $newStatus";
        $categoryStmt->close();
        exit();
    }
    $categoryStmt->close();

    $placeholders = implode(',', array_fill(0, count($residentIds), '?'));
    $updateQuery = "
        UPDATE residents
        SET account_status_id = ?
        WHERE id IN ($placeholders)
    ";
    $stmt = $conn->prepare($updateQuery);
    if ($stmt) {
        $paramTypes = 'i' . str_repeat('i', count($residentIds)); 
        $paramValues = array_merge([$categoryId], $residentIds);
        $stmt->bind_param($paramTypes, ...$paramValues);

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
