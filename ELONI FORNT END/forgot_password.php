
<?php
session_start();

include 'db_conn.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; 
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" href="/floodping/images/Floodpinglogo.png" type="image/png">
    <title>Forgot Password</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Poppins;
        }

        body {
            height: 110vh;
            width: 100%;
            background-image: url('images/bg.jpg');
            background-size: cover; 
            background-repeat: no-repeat; 
            background-position: center; 
            overflow-x: hidden; 
            
        }

        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color:#F5F5F5;
            border: 2px solid #ccc;
            box-shadow: 4px 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 100;
        }

        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1200px;
            height: auto;
            margin: 0 auto;
            padding: 13px 15px;
        }

        .logo {
            display: inline-block; 
            font-size: 22px !important;
            font-weight: bold;
            margin-right: 0px;
            margin-top: 0px;
        }

        .FPlogo-image {
            width: 3.3%;
            height: 83%;
            margin: 0px 0px 0px -59px;
            position: absolute;
        }

        .navbar .links {
            display: flex;
            align-items: center;
            list-style: none;
            gap: 35px;
            margin: 5px 0px 0px 440px !important;
            position: absolute;
        }

        .navbar .links a {
            font-weight: 400;
            text-decoration: none;
            color: black;
            padding: 10px 0;
            transition: 0.2s ease;
        }

        .navbar .links a:hover {
            color: #47b2e4;
        }

        .navbar .buttons a {
            background-color: #02476A; 
            color: #fff; 
            border: 1px solid black;
            cursor: pointer;
            font-size: 14px;
            padding: 6.5px 37px;
            text-decoration: none;
            border-radius: 10px;
            transition: 0.2s ease;
            margin: -16px 0px 0px -80px; 
            position: absolute;
        }

        .navbar .buttons a:hover {
            color: black;
            background-color: transparent;
            border: 1px solid black;
        }

        .form-container {
            background: rgba(245, 251, 255, 0.75); 
            border-radius: 20px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1); 
            backdrop-filter: blur(2px); 
            -webkit-backdrop-filter: blur(10px); 
            border: 1px solid rgba(255, 255, 255, 0.2);
            width: 600px;
            height: 500px;
            border-radius: 24px;
            margin: 130px 0px 0px 500px;
        }

        .form-container p {
            font-size: 16px;
            width: 90%;
            padding: 20px;
            text-align: center;
            margin-top: 150px;
            margin-left: 40px;
            position: absolute;
            color: #02476A;
        }

        .info-group { 
            display: grid; 
            grid-template-columns: repeat(2, 2fr); 
            gap: 0px; 
            margin-top: 160px;
        }

        .input-container input {
             width: 470px; 
             padding: 12px; 
             border: 1px solid #02476A; 
             border-radius: 8px; 
             font-size: 12px; 
             position: relative;
             margin-left: 70px;
             margin-top: 135px;
             gap: 0; 
             position: relative;
        }

        .input-container label { 
            font-size: 14px; 
            color: #02476A;  
            margin-left: 70px;
            margin-top: 110px !important; 
            position: absolute;
            width: 100%;
        }

        button[type="submit"] {
            width: 470px;
            height: 17%;
            background-color: #02476A;
            color: white;
            font-size: 16px;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.3s;
            margin: 50px 0px 0px 70px;
            text-transform: uppercase;
        }

        button[type="submit"]:hover {
            background-color: #01334a;
        }


        #current-time {
            font-size: 40px;
            font-weight: 500;
            color: #02476A; 
            text-align: center;
            margin: 30px 0px 0px -120px; 
            position: absolute;
        }

        #current-date {
            font-size: 18px;
            font-weight: 550;
            color: #02476A; 
            margin: 35px 0px 0px 175px;
            position: absolute;
        }

        #current-day {
            font-size: 18px;
            font-weight: 550;
            text-transform: uppercase;
            color: #02476A; 
            margin: 65px 0px 0px 145px; 
            position: absolute;
        }

        .footer {
            background-color: #1D4D7B; 
            color: #fff; 
            width: 100%;
            height: 7%;
            padding: 5px 55px;
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 260px 0px 0px 0px;
            position: absolute;
        }

        .footer-bottom {
            margin: 10px 0px -20px 630px;
            position: absolute;
        }

        #resetModal {
            position: fixed;
            z-index: 100;
            left: 0;
            top: 0;
            width:100%;
            height: 100%;
            overflow: auto;
            display: none;
            justify-content: center;
            align-items: center;
            margin-top: 50px;
            margin-left: 50px;
            position: fixed;
            background-color: transparent;
        }

        .modal-content {
            background-color: #fff;
            border-radius: 8px !important;
            padding: 20px;
            text-align: center;
            min-width: 460px !important;
            height: 250px;
            position: relative;
            font-size: 12px;
            text-align: justify;
            border: 2px solid #ccc;
        }

        .modal-content p {
            font-size: 14px;
            width: 55%;
            margin: 10px 0px 0px 135px;
        }

        .modal-content h2 {
            font-size: 18px;
            text-align: center;
            margin: 20px 0px 0px 100px;
        }

        .check-photo {
            width: 120px;
            height: auto;
            margin: 0px 0px 0px 0px !important;
            position: absolute;
        }

        .close {
            position: absolute;
            top: 10px;
            right: 15px;
            color: #aaa;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }

        /* ok button */
        button#okButton {
            background-color: #02476A;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin: 30px 0px 0px 170px;
            width: 170px;
            font-size: 12px;
        }

        button#okButton:hover {
            background-color: #02476A;
        }
        
    </style>

    <script>
        function showModal() {
            document.getElementById("resetModal").style.display = "block";
        }
    </script>
    
</head>

<header class="header">
        <nav class="navbar">
        <img class="FPlogo-image" src="images/FloodPingLogo.png" alt="Description of the image">  
            <h2 class="logo">Floodping</h2>
            <ul class="links">
                <li><a href="landingpage.php">HOME</a></li>
                <li><a href="#">LIVESTREAM</a></li>
                <li><a href="#emergency">ABOUT</a></li>
                <li><a href="#contact">CONTACT</a></li>
            </ul>
            <div class="buttons">
                <a href="login.php" class="signin" onclick="window.location.href='login'">LOG IN</a>
            </div>
        </nav>
    </header>

<body>

<div class="form-container">
<p id="current-time"></p>
    <p id="current-date"></p>
    <p id="current-day"></p>
    <p>No worries! Enter your registered email, and we’ll send you a link to reset your password. Let’s get you back in and stay safe with FloodPing!</p>
    <form action="" method="POST">
    <div class="info-group">
    <div class="input-container">
        <label>Email:</label>
        <input type="email" name="email" required placeholder="Email" required><br>
        <button type="submit">Enter</button>
    </form>
    </div>
</div>
    </div>

    <!-- footer -->
<footer class="footer">
    <div class="footer-bottom">
        <p>© 2024 Quezon City University</p>
    </div>
</footer>



    <?php if ($error): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>

    <div id="resetModal" class="modal" <?php if ($show_modal) echo 'style="display:block;"'; ?>>
        <div class="modal-content">
        <img class="email-photo" src="images/email.png">
            <h2>Password Reset Request Sent!</h2>
            <p>Please check your email for thelink to create a new password.</p>
            <button class="close-btn" id="okButton" onclick="window.location.href='login.php';">okay</button>
        </div>
    </div>

<script>
    // Function to update the current time, date, and day
    function updateDateTime() {
        const now = new Date();

        // Get the current time in 12-hour format
        let hours = now.getHours();
        const minutes = now.getMinutes().toString().padStart(2, '0');
        const ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        const currentTime = `${hours}:${minutes} ${ampm}`;

        // Get the current date in 'Month Day, Year' format
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        const currentDate = now.toLocaleDateString('en-US', options);

        // Get the current day in 'long' format
        const currentDay = now.toLocaleDateString('en-US', { weekday: 'long' });

        // Display the current time and date (with only one day)
        document.getElementById('current-time').textContent = `${currentTime}`;
        document.getElementById('current-date').textContent = `${currentDate}`;
        document.getElementById('current-day').textContent = `${currentDay}`;
    }

    // Update the date and time immediately and every minute
    updateDateTime();
    setInterval(updateDateTime, 60000);
</script>
</body>
</html>

