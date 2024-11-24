<?php
session_start();
include_once('../db/db_conn.php');


if (!isset($_SESSION['user_id'])) {
    header("Location: ../HOME/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the updated form values
    $contact_no = $_POST['contact_no'];
    $email = $_POST['email'];
    $house_lot_number = $_POST['house_lot_number'];
    $street_subdivision_name = $_POST['street_subdivision_name'];

  
    $contact_no = $conn->real_escape_string($contact_no);
    $email = $conn->real_escape_string($email);
    $house_lot_number = $conn->real_escape_string($house_lot_number);
    $street_subdivision_name = $conn->real_escape_string($street_subdivision_name);

    
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE contact_no = ? AND user_id != ?");
    $stmt->bind_param("si", $contact_no, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        
        echo "This contact number is already registered.";
        $stmt->close();
        exit();
    }
    $stmt->close();

   
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ? AND user_id != ?");
    $stmt->bind_param("si", $email, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "This email address is already registered.";
        $stmt->close();
        exit();
    }
    $stmt->close();

    $stmt = $conn->prepare("
        UPDATE users 
        SET 
            contact_no = ?, 
            email = ?, 
            house_lot_number = ?, 
            street_subdivision_name = ? 
        WHERE user_id = ?
    ");
    $stmt->bind_param("ssssi", $contact_no, $email, $house_lot_number, $street_subdivision_name, $user_id);

    if ($stmt->execute()) {
        header("Location: admin_profile.php?status=success");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
