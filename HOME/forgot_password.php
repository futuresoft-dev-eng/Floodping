<?php
session_start();
require '/xampp/htdocs/FloodPing/phpmailer/src/PHPMailer.php';
require '/xampp/htdocs/FloodPing/phpmailer/src/SMTP.php';
require '/xampp/htdocs/FloodPing/phpmailer/src/Exception.php';
include_once('../db/db_conn.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
date_default_timezone_set('Asia/Manila');


$show_modal = false;
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = strtolower(trim($_POST['email']));

    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $reset_token = bin2hex(random_bytes(32));  
            $expiry = date("Y-m-d H:i:s", strtotime('+1 day'));  

            $tokenStmt = $conn->prepare("INSERT INTO password_resets (email, reset_token, expiry) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE reset_token = ?, expiry = ?");
            $tokenStmt->bind_param("sssss", $email, $reset_token, $expiry, $reset_token, $expiry);

            if ($tokenStmt->execute()) {
                $reset_link = "http://localhost/Floodping/HOME/reset_password.php?token=$reset_token";  
                $mail = new PHPMailer(true);

                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'floodping.official@gmail.com';  
                    $mail->Password = 'vijk olie xyap yhhs';  
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    $mail->setFrom('floodping.official@gmail.com', 'FloodPing');
                    $mail->addAddress($email);
                    $mail->isHTML(true);
                    $mail->Subject = 'Password Reset Request';
                    $mail->Body = "
                        <p>Hi,</p>
                        <p>You requested to reset your password. Click the link below to proceed:</p>
                        <p><a href='$reset_link'>$reset_link</a></p>
                        <p>This link will expire in 1 hour. If you didn’t request this, please ignore this email.</p>
                        <p>Thank you,<br>FloodPing</p>
                    ";

                    $mail->send();
                    $show_modal = true;  
                } catch (Exception $e) {
                    $error = "Error sending email: {$mail->ErrorInfo}";
                }
            } else {
                $error = "Failed to generate reset link. Please try again later.";
            }
            $tokenStmt->close();
        } else {
            $error = "No account found with that email.";
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
    <title>Forgot Password</title>
    <style>
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); 
    justify-content: center;
    align-items: center;
    z-index: 1000;
}
.modal.show {
    display: flex;
}
.modal-content {
    background: #fff;
    padding: 20px;
    border-radius: 5px; 
    text-align: center;
    width: 300px; 
    max-width: 90%;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    position: relative;
}


    </style>
    <script>
        function showModal() {
            document.getElementById("resetModal").style.display = "block";
        }
    </script>
</head>
<body>
    <h3>No worries!</h3>
    <p>Enter your registered email, and we’ll send you a link to reset your password.</p>
    <form action="" method="POST">
        <label>Email:</label>
        <input type="email" name="email" required><br>
        <button type="submit">Send Reset Link</button>
    </form>
    <?php if ($error): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>

    <div id="resetModal" class="modal" <?php if ($show_modal) echo 'style="display:block;"'; ?>>
        <div class="modal-content">
            <h4>Password Reset Request Sent!</h4>
            <p>Please check your email for the link to create a new password.</p>
            <button class="close-btn" onclick="window.location.href='login.php';">Close</button>
        </div>
    </div>
</body>
</html>

