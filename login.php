<?php
session_start();
include 'db_conn.php';

// get current time, date, and day
date_default_timezone_set("Asia/Manila"); 
$current_time = date("h:i A"); 
$current_date = date("F d, Y");
$current_day = date("l"); // 

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
                        header("Location: add_user.php");
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
    <link rel="icon" href="/floodping/images/Floodpinglogo.png" type="image/png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <title>Login</title>
</head>

<style>
    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Poppins;
}

body {
    height: 100vh;
    width: 100%;
    background-image: url('images/bg.jpg');
    background-size: cover; 
    background-repeat: no-repeat; 
    background-position: center; 
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

.login-container {
    background: rgba(245, 251, 255, 0.75); 
    border-radius: 20px;
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1); 
    backdrop-filter: blur(2px); 
    -webkit-backdrop-filter: blur(10px); 
    border: 1px solid rgba(255, 255, 255, 0.2);
    width: 500px;
    height: 500px;
    border-radius: 24px;
    margin: 150px 0px 0px 850px;
}

.login-container h3 {
    color: #FFFFFF;
    font-size: 50px;
    font-weight: 500;
    margin: 100px 0px 0px -690px;
    position: absolute;
}

.login-container #welcome-text {
    color: #FFFFFF;
    font-size: 16px;
    width: 120%;
    text-align: justify;
    margin: 35px 0px 0px -690px;
    position: absolute;
}

.login-container #glad-text {
    color: #02476A;
    font-size: 16px;
    width: 120%;
    text-align: justify;
    margin: 150px 0px 0px 35px;
    font-weight: 500;
}

.login-container input[type="email"] {
    width: 90%;
    padding: 15px;
    margin-top: 20px;
    margin-bottom: -50px;
    margin-left: 23px;
    border: 1px solid #1D4D7B;
    border-radius: 5px;
    font-size: 14px;
    color: #333333;
    padding-left: 45px;
}

.login-container input[type="password"] {
    width: 90%;
    padding: 15px;
    margin: 50px 100px 0px 23px !important;
    border: 1px solid #1D4D7B;
    border-radius: 5px;
    font-size: 14px;
    color: #333333;
    padding-left: 45px;
    
}

.login-container .icon-wrapper {
    position: absolute;
    top: 50.5%;
    left: 30px;
    transform: translateY(-50%);
    width: 35px;
    height: 35px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.login-container .icon-wrapper i {
    color: white;
    font-size: 1.2rem;
    margin: 110px 0px 0px 0px !important;
    text-shadow: 1px 0 2px #1D4D7B,
                 1px 0 2px #1D4D7B,
                 1px 0 3px #1D4D7B;
}

button {
    width: 90%;
    padding: 10px;
    background-color: #02476A;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 1rem;
    cursor: pointer;
    margin: 50px 15px 0px 23px;
    border: 1px solid #02476A;
}

button:hover {
    background-color: #4597C0;
}

.fa-user {
    margin: 0px;
    position: absolute;
    color: white;
    font-size: 1.2rem;
    margin: 35px 0px 0px 40px;
    text-shadow: 1px 0 2px #1D4D7B, 
                 1px 0 2px #1D4D7B,
                 1px 0 3px #1D4D7B;
}

#current-time {
    font-size: 40px;
    font-weight: 500;
    color: #02476A; 
    text-align: center;
    margin: 50px 0px 0px 60px; 
    position: absolute;
}

#current-date {
    font-size: 18px;
    font-weight: 550;
    color: #02476A; 
    margin: 60px 0px 0px 275px;
    position: absolute;
}

#current-day {
    font-size: 18px;
    font-weight: 550;
    text-transform: uppercase;
    color: #02476A; 
    margin: 90px 0px 0px 325px; 
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
    margin: 200px 0px 0px 0px;
    position: absolute;
}

.footer-bottom {
    margin: 10px 0px -20px 630px;
    position: absolute;
}
/* Responsive breakpoints */
@media screen and (max-width: 768px) {
    .navbar {
        flex-direction: column;
        align-items: flex-start;
    }

    .FPlogo-image {
        width: 40px;
    }

    .navbar .links {
        margin-top: 10px;
    }

    .login-container {
        width: 95%;
    }
}

@media screen and (max-width: 480px) {
    .navbar .links {
        flex-direction: column;
        gap: 10px;
    }

    .login-container h3 {
        font-size: 1.5rem;
    }

    #current-time {
        font-size: 1.2rem;
    }
}
</style>

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
<div class="login-container">
    <h3>Welcome to FloodPing!</h3>
    <p id="current-time"> <?= $current_time; ?></p>
    <p id="current-date"> <?= $current_date; ?></p>
    <p id="current-day"><?= $current_day; ?></p> 
    <p id="glad-text">Glad to see you again! Login to your account below</p>
    <p id="welcome-text">Stay informed and stay safe as you monitor real-time water levels in your community. With FloodPing, you’re always one step ahead—receive timely alerts, check current flood status, and access essential updates to help protect you and those around you. 
        <br>
        <br>
        Let’s keep watch together. Your safety is our priority!</p>
    <form action="" method="POST">
    <div class="input-container">
        <i class="fas fa-user"></i> <!-- User Icon -->
        <input type="email" name="email" required placeholder="User ID">
    </div>
        
    <div class="icon-wrapper">
        <i class="fas fa-lock"></i> <!-- Lock icon -->
    </div>
        <input type="password" name="password" required placeholder="Password"><br>
        <button type="submit">Log in</button>
    </form>
    <?php if ($error): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>
</div>

  <!-- footer -->
<footer class="footer">
    <div class="footer-bottom">
        <p>© 2024 Quezon City University</p>
    </div>
</footer>

<!-- js for dynamic time -->
<script>
    function updateTime() {
        const timeElement = document.getElementById('current-time');
        const now = new Date();
        const hours = now.getHours() % 12 || 12;
        const minutes = now.getMinutes().toString().padStart(2, '0');
        const ampm = now.getHours() >= 12 ? 'PM' : 'AM';
        timeElement.textContent = `${hours}:${minutes} ${ampm}`;
    }
    setInterval(updateTime, 1000);
    updateTime(); 
</script>


</body>
</html>

