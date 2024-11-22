<?php
include_once('../db/db_conn.php');

if (isset($_GET['user_id']) || isset($_SESSION['user_id'])) {
    $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "User not found.";
        exit();
    }
} else {
    echo "No user ID provided.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0) {
        $upload_dir = '../uploads/';
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        $file_name = $_FILES['profile_photo']['name'];
        $file_tmp_name = $_FILES['profile_photo']['tmp_name'];
        $file_type = $_FILES['profile_photo']['type'];

        if (in_array($file_type, $allowed_types)) {
            $file_path = $upload_dir . uniqid() . '_' . basename($file_name);
            if (move_uploaded_file($file_tmp_name, $file_path)) {
                $update_photo_stmt = $conn->prepare("UPDATE users SET profile_photo = ? WHERE user_id = ?");
                $update_photo_stmt->bind_param("ss", $file_path, $user_id);
                if ($update_photo_stmt->execute()) {
                    echo "Profile photo updated successfully.";
                } else {
                    echo "Failed to update profile photo.";
                }
            } else {
                echo "Failed to upload photo.";
            }
        } else {
            echo "Invalid file type. Only JPEG, PNG, and JPG files are allowed.";
        }
    }

    $first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : $user['first_name'];
    $middle_name = isset($_POST['middle_name']) ? trim($_POST['middle_name']) : $user['middle_name'];
    $last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : $user['last_name'];
    $suffix = isset($_POST['suffix']) ? trim($_POST['suffix']) : $user['suffix'];
    $contact_no = isset($_POST['contact_no']) ? trim($_POST['contact_no']) : $user['contact_no'];

    $email = isset($_POST['email']) ? trim($_POST['email']) : $user['email'];
    $house_lot_number = isset($_POST['house_lot_number']) ? trim($_POST['house_lot_number']) : $user['house_lot_number'];
    $street_subdivision_name = isset($_POST['street_subdivision_name']) ? trim($_POST['street_subdivision_name']) : $user['street_subdivision_name'];
    
    if (!preg_match("/^\d{11}$/", $contact_no)) {
        echo "Contact number must be exactly 11 digits.";
        exit();
    }

    if (empty($first_name) || empty($last_name) || empty($contact_no) || empty($email) || empty($house_lot_number) || empty($street_subdivision_name)) {
        echo "All fields must be filled out.";
        exit();
    }

    // Check if the email already exists (except the current user's email)
    $email_check_stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND user_id != ?");
    $email_check_stmt->bind_param("ss", $email, $user_id);
    $email_check_stmt->execute();
    $email_check_result = $email_check_stmt->get_result();

    if ($email_check_result->num_rows > 0) {
        echo "The email address is already taken.";
        exit();
    }

        // Check if the contact number already exists (except the current user's contact number)
    $contact_check_stmt = $conn->prepare("SELECT * FROM users WHERE contact_no = ? AND user_id != ?");
    $contact_check_stmt->bind_param("ss", $contact_no, $user_id);
    $contact_check_stmt->execute();
    $contact_check_result = $contact_check_stmt->get_result();

    if ($contact_check_result->num_rows > 0) {
        echo "The contact number is already taken.";
        exit();
    }

    $update_stmt = $conn->prepare("UPDATE users SET first_name = ?, middle_name = ?, last_name = ?, suffix = ?, contact_no = ?, email = ?, house_lot_number = ?, street_subdivision_name = ? WHERE user_id = ?");
    $update_stmt->bind_param("sssssssss", $first_name, $middle_name, $last_name, $suffix, $contact_no, $email, $house_lot_number, $street_subdivision_name, $user_id);
    if ($update_stmt->execute()) {
        echo "User details updated successfully.";
    } else {
        echo "Failed to update user details.";
    }
}

$conn->close();
?>
