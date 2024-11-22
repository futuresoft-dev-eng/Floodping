<?php
include_once('../db/db_conn.php');


if (isset($_GET['user_id']) || isset($_POST['user_id'])) {
    $userId = isset($_GET['user_id']) ? trim($_GET['user_id']) : trim($_POST['user_id']);
    echo "Received user_id: " . htmlspecialchars($userId);

    $query = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die("Error preparing query: " . $conn->error);
    }
    $stmt->bind_param('s', $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $archiveQuery = "INSERT INTO archive_accounts 
            (user_id, first_name, middle_name, last_name, suffix, contact_no, sex, birthdate, email, city, barangay, house_lot_number, street_subdivision_name, role, position, schedule, shift, profile_photo, archived_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $archiveStmt = $conn->prepare($archiveQuery);
        if ($archiveStmt === false) {
            die("Error preparing archive query: " . $conn->error);
        }

        $archiveStmt->bind_param(
            'ssssssssssssssssss',
            $user['user_id'],
            $user['first_name'],
            $user['middle_name'],
            $user['last_name'],
            $user['suffix'],
            $user['contact_no'],
            $user['sex'],
            $user['birthdate'],
            $user['email'],
            $user['city'],
            $user['barangay'],
            $user['house_lot_number'],
            $user['street_subdivision_name'],
            $user['role'],
            $user['position'],
            $user['schedule'],
            $user['shift'],
            $user['profile_photo']
        );

        if ($archiveStmt->execute()) {
            $deleteQuery = "DELETE FROM users WHERE user_id = ?";
            $deleteStmt = $conn->prepare($deleteQuery);
            if ($deleteStmt === false) {
                die("Error preparing delete query: " . $conn->error);
            }

            $deleteStmt->bind_param('s', $userId);
            $deleteStmt->execute();

            if ($deleteStmt->affected_rows > 0) {
                header("Location: archive_account.php?success=User archived successfully.");
                exit();
            } else {
                echo "Failed to delete the user from the users table.";
            }
        } else {
            echo "Failed to archive the user.";
        }

        $archiveStmt->close();
        $deleteStmt->close();

    } else {
        echo "User not found.";
    }
    $stmt->close();

} else {
    echo "Account archived successfully.";
}
$conn->close();
?>
