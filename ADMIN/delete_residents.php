<?php
include_once('../db/db_conn.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_residents'])) {
    $selectedResidents = json_decode($_POST['selected_residents'], true);

    if (!empty($selectedResidents)) {
        $escapedIds = array_map(function ($id) use ($conn) {
            return "'" . mysqli_real_escape_string($conn, $id) . "'";
        }, $selectedResidents);

        $ids = implode(",", $escapedIds);
        $query = "DELETE FROM residents WHERE resident_id IN ($ids)";

        if (mysqli_query($conn, $query)) {
            $responseMessage = "delete_status=Residents deleted successfully";
        } else {
            $responseMessage = "delete_status=Failed to delete residents";
        }
    } else {
        $responseMessage = "delete_status=No residents selected";
    }

    $baseUrl = strtok($_SERVER['HTTP_REFERER'], '?'); 
    $redirectUrl = $baseUrl . "?" . $responseMessage; 
    header("Location: $redirectUrl");
    exit;
} else {
    header('Location: /floodping/ADMIN/accountservice.php');
    exit;
}
?>
