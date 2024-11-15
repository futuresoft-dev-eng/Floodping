<?php
include_once('../db/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_residents'])) {
    $selectedResidents = json_decode($_POST['selected_residents'], true);

    if (!empty($selectedResidents)) {
        $escapedIds = array_map(function ($id) use ($conn) {
            return "'" . mysqli_real_escape_string($conn, $id) . "'";
        }, $selectedResidents);

        $ids = implode(",", $escapedIds);
        $query = "DELETE FROM residents WHERE resident_id IN ($ids)";

        if (mysqli_query($conn, $query)) {
            $responseMessage = "success=Residents deleted successfully";
        } else {
            $responseMessage = "error=Failed to delete residents";
        }
    } else {
        $responseMessage = "error=No residents selected";
    }

    $redirectUrl = $_SERVER['HTTP_REFERER'] ?? '/floodping/ADMIN/accountservice.php';
    header("Location: $redirectUrl?$responseMessage");
    exit;
} else {
    header('Location: /floodping/ADMIN/accountservice.php');
    exit;
}
?>
