<?php
require '/xampp/htdocs/FloodPing/phpmailer/src/PHPMailer.php';
require '/xampp/htdocs/FloodPing/phpmailer/src/SMTP.php';
require '/xampp/htdocs/FloodPing/phpmailer/src/Exception.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include 'db_conn.php';
session_start();

if (isset($_SESSION['user_id'])) {
    $admin_user_id = $_SESSION['user_id'];
} else {
    echo "User ID is missing or session expired.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && isset($_POST['user_id'])) {
        $action = $_POST['action'];
        $user_id = $_POST['user_id'];

        if ($action === 'deactivate') {
            $account_status = "Inactive";
        } elseif ($action === 'reactivate') {
            $account_status = "Active";
        } elseif ($action === 'unlock') {
            $account_status = "Active"; 
        }

        if (isset($account_status)) {
            $update_status_stmt = $conn->prepare("UPDATE users SET account_status = ? WHERE user_id = ?");
            $update_status_stmt->bind_param("ss", $account_status, $user_id);

            if ($update_status_stmt->execute()) {
                if ($action === 'unlock') {
                    // will reset the failed attempts to 0 then make last attempt tima and date into null.
                    $reset_attempts_stmt = $conn->prepare("UPDATE users SET failed_attempts = 0, last_attempt_time = NULL, last_attempt_date = NULL WHERE user_id = ?");
                    $reset_attempts_stmt->bind_param("s", $user_id);
                    $reset_attempts_stmt->execute();
                    $reset_attempts_stmt->close();
                    $user_stmt = $conn->prepare("SELECT email FROM users WHERE user_id = ?");
                    $user_stmt->bind_param("s", $user_id);
                    $user_stmt->execute();
                    $user_result = $user_stmt->get_result();
                    $user_data = $user_result->fetch_assoc();
                    $user_email = $user_data['email'];
                    $user_stmt->close();

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
                        $mail->addAddress($user_email);
                        $mail->isHTML(true);
                        $mail->Subject = "Your Account Has Been Unlocked";
                        $mail->Body = "
                            <p>Hello,</p>
                            <p>Your account has been successfully unlocked. You can now log in to your account using your credentials.</p>
                            <p>Click the link below to login:</p>
                            <a href='http://localhost/Floodping/login.php'>Log in to your account</a>
                            <p>Thank you!</p>
                        ";

                        $mail->send();
                        echo "Account unlocked and email sent successfully.";
                    } catch (Exception $e) {
                        echo "Account unlocked, but email could not be sent. Error: {$mail->ErrorInfo}";
                    }
                } else {
                    echo $account_status === "Inactive" ? "Account has been deactivated." : "Account has been reactivated.";
                }
            } else {
                echo "Failed to update account status.";
            }
            $update_status_stmt->close();
        }
    } else {
        echo "Invalid request: Missing action or user_id.";
    }
} else {
    echo "Invalid request method.";
}

$conn->close();
?>
