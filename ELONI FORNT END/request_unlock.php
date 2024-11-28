<?php

$error = "";
$email = isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '';
$status = isset($_GET['status']) ? $_GET['status'] : ''; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/floodping/images/Floodpinglogo.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>Request Unlock</title>
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

        .modal {
            display: none;
            position: fixed; 
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            text-align: center;
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
            width: 800px;
            height: 560px;
            border-radius: 24px;
            margin: 120px 0px 0px 400px;
        }

        .form-container p {
            font-size: 14px;
            width: 90%;
            padding: 20px;
            text-align: center;
            margin-top: 110px;
            margin-left: 40px;
            position: absolute;
            color: #D80000;
        }
        .info-group { 
            display: grid; 
            grid-template-columns: repeat(2, 2fr); 
            gap: 0px; 
            margin-top: 180px;
        }

        .input-container input {
             width: 300px; 
             padding: 12px; 
             border: 1px solid #02476A; 
             border-radius: 8px; 
             font-size: 12px; 
             position: relative;
             margin-left: 50px;
             margin-top: 35px;
             gap: 0; 
             position: relative;
        }

        #email-input {
            width: 695px;
            position: absolute;
        }

        .input-container label { 
            font-size: 13px; 
            color: #02476A;  
            margin-left: 50px;
            margin-top: 15px !important; 
            position: absolute;
            width: 100%;
        }

        button[type="submit"] {
            width: 700px;
            height: 30%;
            background-color: #02476A;
            color: white;
            font-size: 16px;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.3s;
            margin: 120px 0px 0px -350px;
            text-transform: uppercase;
        }

        button[type="submit"]:hover {
            background-color: #01334a;
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

        #current-time {
            font-size: 40px;
            font-weight: 500;
            color: #02476A; 
            text-align: center;
            margin: 30px 0px 0px -200px; 
            position: absolute;
        }

        #current-date {
            font-size: 18px;
            font-weight: 550;
            color: #02476A; 
            margin: 35px 0px 0px 275px;
            position: absolute;
        }

        #current-day {
            font-size: 18px;
            font-weight: 550;
            text-transform: uppercase;
            color: #02476A; 
            margin: 65px 0px 0px 245px; 
            position: absolute;
        }

        .modal {
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
  
        /* modal content box */
        .modal-content {
            background-color: #fff;
            border-radius: 8px !important;
            padding: 20px;
            text-align: center;
            min-width: 500px !important;
            height: 250px;
            position: relative;
            font-size: 12px;
            text-align: justify;
            border: 2px solid #ccc;
        }

        .modal-content h2 {
            font-size: 18px;
            text-align: center;
            margin: 20px 0px 0px 120px;
        }

        .modal-content p {
            font-size: 14px;
            margin: 10px 0px 0px 135px;
        }

        /* email icon */
        .email-photo {
            width: 120px;
            height: auto;
            margin: 0px 0px 0px 0px !important;
            position: absolute;
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

<div class="form-container">
<p id="current-time"></p>
    <p id="current-date"></p>
    <p id="current-day"></p>

<p>Your account has been locked due to multiple unsuccessful login attempts. Please submit a request to resolve this issue.</p>

<form action="process_unlock_request.php" method="POST">
<div class="info-group">
<div class="input-container">
    <label>User ID</label>
    <input type="text" name="user_id" required placeholder="#####"><br>
</div>
    
<div class="input-container">
    <label>Mobile Number</label>
    <input type="text" name="mobile_number" required placeholder="09#########"><br>
</div>
    
<div class="input-container">
    <label>First Name</label>
    <input type="text" name="first_name" required placeholder="Enter first name"><br>
</div>
    
<div class="input-container">
    <label>Last Name</label>
    <input type="text" name="last_name" required placeholder="Enter your last name"><br>
</div>
     
<div class="input-container">
    <label>Email</label>
    <input type="email" name="email" id="email-input" equired placeholder="email@gmail.com" required value="<?= $email ?>" readonly><br>
</div>

    <button type="submit">Submit Request</button>
</form>
    </div>
</div>

<div id="responseModal" class="modal">
  <div class="modal-content">
  <img class="email-photo" src="images/email.png">
    <h2 id="modalTitle">Request Submitted Successfully!</h2>
    <p id="modalMessage">Your request has been received! Please monitor your email for further updates and instructions.</p>
    <div class="modal-footer">
      <button id="okButton" class="btn">Okay</button>
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
    document.addEventListener('DOMContentLoaded', function() {
        var status = "<?= $status ?>"; 

        if (status === 'success') {
            document.getElementById('modalMessage').innerText = 'Your request has been received! Please monitor your email for further updates and instructions';
            document.getElementById('responseModal').style.display = 'block';
        } else if (status === 'error') {
            document.getElementById('modalMessage').innerText = 'There was an error sending your request. Please try again later.';
            document.getElementById('responseModal').style.display = 'block';
        }
        document.getElementById('okButton').onclick = function() {
            window.location.href = 'login.php';  
        };
    });

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
