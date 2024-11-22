<?php
include_once('db/db_conn.php');

require '/xampp/htdocs/FloodPing/phpmailer/src/PHPMailer.php';
require '/xampp/htdocs/FloodPing/phpmailer/src/SMTP.php';
require '/xampp/htdocs/FloodPing/phpmailer/src/Exception.php';


$response = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = htmlspecialchars(trim($_POST['user_id'])); 
    $mobile_number = htmlspecialchars(trim($_POST['mobile_number'])); 
    $first_name = htmlspecialchars(trim($_POST['first_name'])); 
    $last_name = htmlspecialchars(trim($_POST['last_name'])); 
    $email = htmlspecialchars(trim($_POST['email'])); 

    $mail_admin = new PHPMailer\PHPMailer\PHPMailer();
    $mail_user = new PHPMailer\PHPMailer\PHPMailer();

    try {

        $mail_admin->isSMTP();
        $mail_admin->Host = 'smtp.gmail.com';
        $mail_admin->SMTPAuth = true;
        $mail_admin->Username = 'floodping.official@gmail.com';
        $mail_admin->Password = 'vijk olie xyap yhhs';
        $mail_admin->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail_admin->Port = 587;
        $mail_admin->setFrom('floodping.official@gmail.com', 'FloodPing');
        $mail_admin->addAddress('floodping.official@gmail.com');
        $mail_admin->Subject = "Account Unlock Request for $user_id";
        $mail_admin->Body = "
        Hello Admin,

        A user has requested to unlock their account. Here are the details:

        User ID: $user_id
        First Name: $first_name
        Last Name: $last_name
        Email: $email
        Mobile Number: $mobile_number

        Thank you,
        FloodPing Team
        ";

        if ($mail_admin->send()) {
            $mail_user->isSMTP();
            $mail_user->Host = 'smtp.gmail.com';
            $mail_user->SMTPAuth = true;
            $mail_user->Username = 'floodping.official@gmail.com';
            $mail_user->Password = 'vijk olie xyap yhhs';
            $mail_user->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail_user->Port = 587;

            $mail_user->setFrom('floodping.official@gmail.com', 'FloodPing');
            $mail_user->addAddress($email); 
            $mail_user->Subject = "Your Account Unlock Request";
            $mail_user->Body = "
            Dear $first_name $last_name,

            We have received your request to unlock your account. Our team will review your request and contact you shortly.

            If you have any questions, feel free to reply to this email.

            Thank you,
            FloodPing Team
            ";

            if ($mail_user->send()) {
                $response = 'success';
            } else {
                $response = 'error_user_email';
            }
        } else {
            $response = 'error_admin_email'; 
        }
    } catch (Exception $e) {
        $response = 'error_exception'; 
    }

    header("Location: request_unlock.php?status=$response");
    exit();
}
?>
