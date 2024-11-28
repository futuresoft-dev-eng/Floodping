
<?php
session_start();
include_once('../db/db_conn.php');
date_default_timezone_set('Asia/Manila');

$error = $success = "";
$token = $_GET['token'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $token = $_POST['token'];

    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^A-Za-z0-9])[\S]{8,}$/', $password)) {
        $error = "Password does not meet the requirements.";
    } else {
        $stmt = $conn->prepare("SELECT email, expiry FROM password_resets WHERE reset_token = ? AND expiry > NOW()");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $email = $row['email'];
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
            $updateStmt->bind_param("ss", $hashed_password, $email);
            if ($updateStmt->execute()) {
                $deleteStmt = $conn->prepare("DELETE FROM password_resets WHERE reset_token = ?");
                $deleteStmt->bind_param("s", $token);
                $deleteStmt->execute();

                $success = "Password reset successful. You can now log in.";
            } else {
                $error = "Failed to reset password. Please try again.";
            }
            $updateStmt->close();
        } else {
            $error = "Invalid or expired reset token.";
        }
        $stmt->close();
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
    <link rel="icon" href="/floodping/images/Floodpinglogo.png" type="image/png">
    <title>Reset Password</title>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const passwordInput = document.querySelector("input[name='password']");
            const confirmPasswordInput = document.querySelector("input[name='confirm_password']");
            const feedback = document.getElementById("feedback");
            const matchFeedback = document.getElementById("matchFeedback");

            passwordInput.addEventListener("input", function () {
                const password = passwordInput.value;
                const requirements = [
                    { regex: /.{8,}/, message: "At least 8 characters long" },
                    { regex: /[A-Z]/, message: "At least one uppercase letter" },
                    { regex: /[a-z]/, message: "At least one lowercase letter" },
                    { regex: /\d/, message: "At least one number" },
                    { regex: /[^A-Za-z0-9]/, message: "At least one special character" }
                ];

                const unmet = requirements.filter(req => !req.regex.test(password));
                feedback.innerHTML = unmet.length
                    ? "<ul>" + unmet.map(req => `<li>${req.message}</li>`).join("") + "</ul>"
                    : "Password meets all requirements!";
                feedback.style.color = unmet.length ? "red" : "green";
            });

            confirmPasswordInput.addEventListener("input", function () {
                matchFeedback.textContent = passwordInput.value === confirmPasswordInput.value
                    ? "Passwords match!"
                    : "Passwords do not match!";
                matchFeedback.style.color = passwordInput.value === confirmPasswordInput.value ? "green" : "red";
            });

            <?php if ($success): ?>
                const modal = document.getElementById('successModal');
                modal.style.display = 'block';
            <?php endif; ?>
        });
        

        function openModal() {
    document.getElementById('successModal').style.display = 'flex';
} 

function closeModal() {
    document.getElementById('successModal').style.display = 'none';
}
    </script>

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

        .container {
            background: #FFFFFF; 
            border-top-left-radius: 20px;
            border-bottom-left-radius: 20px;   
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1); 
            backdrop-filter: blur(2px); 
            -webkit-backdrop-filter: blur(10px); 
            border: 1px solid rgba(255, 255, 255, 0.2);
            width: 400px;
            height: 530px;
            margin: 0px 0px 0px 380px;
            position: absolute;
        }

        .reset-photo {
            width: 50%;
            margin: 80px 0px  0px 100px;
        }

        .container h3 {
            font-size: 23px;
            color: #02476A;
            margin: 30px 0px  0px 50px;
        }

        .container p {
            font-size: 16px;
            color: #02476A;
            text-align: justify;
            width: 60%;
            margin: 20px 0px  0px 70px;
        }

        .form-container {
            background: rgba(245, 251, 255, 0.75); 
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1); 
            backdrop-filter: blur(2px); 
            -webkit-backdrop-filter: blur(10px); 
            border: 1px solid rgba(255, 255, 255, 0.2);
            width: 400px;
            height: 530px;
            border-top-right-radius: 20px;
            border-bottom-right-radius: 20px;  
            margin: 140px 0px 0px 780px;
        }

        .info-group { 
            display: grid; 
            grid-template-columns: repeat(1, 1fr); 
            gap: 0px; 
            margin-top: 160px;
        }

        .input-container input {
             width: 345px; 
             padding: 12px; 
             border: 1px solid #02476A; 
             border-radius: 8px; 
             font-size: 12px; 
             position: relative;
             margin-left: 27px;
             margin-top: 55px;
             gap: 0; 
             position: relative;
        }

        .input-container label { 
            font-size: 13px; 
            color: #02476A;  
            margin-left: 35px;
            margin-top: 30px !important; 
            position: absolute;
            width: 100%;
        }

        button[type="submit"] {
            width: 350px;
            height: 25%;
            background-color: #02476A;
            color: white;
            font-size: 16px;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.3s;
            margin: 50px 0px 0px 30px;
            text-transform: uppercase;
        }

        button[type="submit"]:hover {
            background-color: #01334a;
        }


        #current-time {
            font-size: 35px;
            font-weight: 500;
            color: #02476A; 
            text-align: center;
            margin: 80px 0px 0px 30px; 
            position: absolute;
        }

        #current-date {
            font-size: 16px;
            font-weight: 550;
            color: #02476A; 
            margin: 85px 0px 0px 205px;
            position: absolute;
        }

        #current-day {
            font-size: 16px;
            font-weight: 550;
            text-transform: uppercase;
            color: #02476A; 
            margin: 115px 0px 0px 205px; 
            position: absolute;
        }


        #successModal {
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

        .modal-content img {
            display: block;
            margin: 0 auto;
        }

        .footer {
            background-color: #1D4D7B; 
            color: #fff; 
            width: 100%;
            height: 7%;
            padding: 5px 55px;
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 250px 0px 0px 0px;
            position: absolute;
        }

        .footer-bottom {
            margin: 10px 0px -20px 630px;
            position: absolute;
        }

    </style>
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

<div class="container">
  <img class="reset-photo" src="images/reset-icon.png">
    <h3>Set your new password</h3>
    <p>You're almost there! Create a strong, secure password to keep your account safe.</p>
</div>

<div class="form-container">
<p id="current-time"></p>
    <p id="current-date"></p>
    <p id="current-day"></p>
    
    <form action="" method="POST">
    <div class="info-group">
    <div class="input-container">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
        <label>New Password:</label>
        <input type="password" name="password" required placeholder="Create new password" required><br>
        <div id="feedback" style="color: red;"></div>
</div>

<div class="input-container">
        <label>Confirm New Password:</label>
        <input type="password" name="confirm_password" required placeholder="Confirm Password" required><br>
        <div id="matchFeedback" style="color: red;"></div>
        <button type="submit">Reset Password</button>
    </form>
    </div>
    </div>
    

    <?php if ($error): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>

<div id="successModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <img class="check-photo" src="images/check-circle.png">
        <h2>Password reset successful!</h2>
        <p>Your password has been reset. You can now log in with your new password.</p>
        <button id="okButton"onclick="window.location.href = 'login.php';">OK</button>
    </div>
</div>
</div>

<!-- footer -->
<footer class="footer">
    <div class="footer-bottom">
        <p>© 2024 Quezon City University</p>
    </div>
</footer>


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
