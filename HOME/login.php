<?php
session_start();
include_once('../db/db_conn.php');


$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = strtolower(trim($_POST['email']));
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT user_id, first_name, last_name, email, password, role, account_status, failed_attempts, last_attempt_date, last_attempt_time FROM users WHERE email = ?");
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            $current_date = date('Y-m-d'); 
            $current_time = date('H:i:s'); 

            if ($user['account_status'] == 'Locked') {
                error_log("Account is locked, redirecting to request_unlock.php");
                header("Location: request_unlock.php?email=" . urlencode($email));
                exit();
            } else if ($user['account_status'] == 'Inactive') {
                $error = "Your account is inactive. Please contact the admin for further assistance.";
            } else {
                if (password_verify($password, $user['password'])) {
                    $resetStmt = $conn->prepare("UPDATE users SET failed_attempts = 0, last_attempt_date = NULL, last_attempt_time = NULL WHERE email = ?");
                    $resetStmt->bind_param("s", $email);
                    $resetStmt->execute();
                    $resetStmt->close();
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['full_name'] = $user['first_name'] . ' ' . $user['last_name'];
                    $_SESSION['role'] = $user['role'];
                    if ($user['role'] === 'Admin') {
                        header("Location: ../ADMIN/add_user.php");
                    } else if ($user['role'] === 'Local Authority') {
                        header("Location: authority_dashboard.php");
                    }
                    exit();
                } else {
                    $failedAttempts = $user['failed_attempts'] + 1;

                    if ($failedAttempts >= 4) {
                        $updateStmt = $conn->prepare("UPDATE users SET failed_attempts = ?, account_status = 'Locked', last_attempt_date = ?, last_attempt_time = ? WHERE email = ?");
                        $updateStmt->bind_param("isss", $failedAttempts, $current_date, $current_time, $email);
                        $error = "Too many failed attempts. Your account is now locked. Contact the admin to unlock it.";
                    } else {
                        $updateStmt = $conn->prepare("UPDATE users SET failed_attempts = ?, last_attempt_date = ?, last_attempt_time = ? WHERE email = ?");
                        $updateStmt->bind_param("isss", $failedAttempts, $current_date, $current_time, $email);
                        $error = "Incorrect credentials. " . (4 - $failedAttempts) . " attempts left before account lock. Please check your info.";
                    }

                    $updateStmt->execute();
                    $updateStmt->close();
                }
            }
        } else {
            $error = "User not found.";
        }
        $stmt->close();
    } else {
        $error = "Error preparing statement: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h3>Welcome to FloodPing!</h3>
    <p>Glad to see you again! Login to your account below</p>
    <form action="" method="POST">
        <label>Email:</label>
        <input type="email" name="email" required placeholder="User ID"><br>
        <label>Password:</label>
        <input type="password" name="password" required placeholder="Password"><br>
        <a href="forgot_password.php">Forgot Password</a>
        <button type="submit">Log in</button>
    </form>
    <?php if ($error): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>
</body>
</html>
