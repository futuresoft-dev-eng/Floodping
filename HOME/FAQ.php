<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Floodping | Frequently Asked Questions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" />
    <link rel="icon" href="/floodping/images/Floodpinglogo.png" type="image/png">
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
    background: #F5F5F5;
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

.bg-img {
    min-width: 100%;
    height: 232px;
    filter: brightness(50%); 
}

#title {
    font-size: 40px;
    font-weight: 500;
    color: #FFFFFF;
    margin: -130px 0px 0px 250px;
    position: absolute;
}

.faq-container {
    max-width: 1300px;
    margin: 20px auto;
    padding: 20px;
    background: #F5F5F5;
    border: none;
    margin: -5px auto;
}

#welcome-text {
    margin-left: 0px;
}

.faq-container p {
    font-size: 14px;
    margin-bottom: 14px;
    color: #333;
    text-align: justify;
    margin-left: 55px;
}

.faq-section {
    margin-bottom: 30px;
}

.faq-section h2 {
    font-size: 16px;
    color: #000000;
    margin-bottom: 10px;
}

.faq-section ul {
    font-size: 14px;
    padding-left: 20px;
    margin: 10px 0;
}

.faq-section ul li {
    list-style-type: disc;
    margin-bottom: 5px;
    margin-left: 20px;
}

.highlight-text {
    font-size: 14px;
}

.email {
    font-style: italic;
}

.email:hover {
    color: rgb(44, 44, 192);
}

/* footer */
.footer {
    background-color: #1D4D7B; 
    color: #fff; 
    padding: 5px 15px;
    font-family: Arial, sans-serif;
    text-align: center;
    margin: 100px auto;
}

.footer-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.footer-logo img {
    width: 7%;
    height: auto;
    border-radius: 50%; 
    margin: -55px 0px 0px -90px;
    position: absolute;
}

.footer-links ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    gap: 55px;
    margin: -40px;
}

.footer-links a {
    color: #fff;
    text-decoration: none;
    font-size: 14px;
}

.footer-links a:hover {
    text-decoration: underline;
}

.footer-social p {
    margin: 0;
    font-size: 16px;
    font-weight: bold;
    margin-top: 20px;
}

.footer-social .social-icon {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    width: 50px; 
    height: 50px;
    margin: 20px 10px;
    border-radius: 50%;
    background-color: #fff; 
    color: #1D4D7B; 
    font-size: 18px;
    text-decoration: none;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.footer-bottom {
    margin: -36px 0px -20px 500px;
    position: absolute;
}
.footer-links .faq {
    margin: 30px 0px 0px 0px;
    position: absolute;
}
</style>

<header class="header">
        <nav class="navbar">
        <img class="FPlogo-image" src="../images/FloodPingLogo.png" alt="Description of the image">  
            <h2 class="logo">Floodping</h2>
            <ul class="links">
                <li><a href="index.php">HOME</a></li>
                <li><a href="#">LIVESTREAM</a></li>
                <li><a href="#emergency">ABOUT</a></li>
                <li><a href="#contact">CONTACT</a></li>
            </ul>
            <div class="buttons">
                <a href="login.php" class="signin" onclick="window.location.href='login'">LOG IN</a>
            </div>
        </nav>
    </header>

        <!-- faq bg section -->
  <section class="bg-section" id="bg">
    <div class = "bg-photo">
        <img class="bg-img" src="images/bg.jpg" alt="background">   
    </div>
    
    <div class="faq-container">
    <h2 id="title">FloodPing | Frequently Asked Questions</h2>
    <p id="welcome-text">
        Welcome to FloodPing, an advanced IoT-based flood monitoring and alert system designed to keep communities safe during flood events. By utilizing real-time water sensors and a web-based live stream, FloodPing helps Local Authorities and residents stay informed about changing water levels and receive timely alerts to guide evacuation actions. This system is structured to provide clear, early warnings and ensure a coordinated response, protecting lives and minimizing flood-related risks. <br>
    <br>
        Below are some frequently asked questions to help you understand how FloodPing works and how it can assist you during flood events.
    </p>

    <div class="faq-section">
        <h2>1. What is FloodPing, and how does it help during flood events?</h2>
        <p>
            FloodPing is an IoT-based flood monitoring and alert system designed to provide early warning and live updates during flood events. 
            It utilizes water sensors connected to a web platform that offers real-time streaming and status updates on water levels, 
            which helps Local Authorities and residents stay informed and act quickly to protect lives and property.
        </p>
    </div>

    <div class="faq-section">
        <h2>2. How does FloodPing monitor and classify water levels?</h2>
        <p>
            FloodPing classifies water levels into four categories:
            <ul>
                <li><b><i>Normal:</i></b> Safe conditions; no action needed.</li>
                <li><b><i>Low:</i></b> Self-evacuation is advised for residents.</li>
                <li><b><i>Moderate:</i></b> Forced evacuation is recommended.</li>
                <li><b><i>Critical:</i></b> Residents should stay at designated evacuation centers until further notice.</li>
            </ul>
            <span class="highlight-text">
            When water levels reach "Low," FloodPing starts sending alerts to Local Authorities, who then confirm and relay these alerts to residents if necessary.
        </span>

        </p>
    </div>

    <div class="faq-section">
        <h2>3. How are flood alerts communicated to Local Authorities and residents?</h2>
        <p>
            Once the water reaches "Low," FloodPing sends alerts to Local Authorities via the web platform and SMS. 
            Upon receiving these notifications, Local Authorities can confirm the alerts and send SMS notifications to registered residents. 
            These notifications include critical evacuation information, like when to leave and where to go.
        </p>
    </div>

    <div class="faq-section">
        <h2>4. Who can access and manage the FloodPing system?</h2>
        <p>
            FloodPing has different access levels for Admins, Local Authorities, and residents:
            <ul>
                <li><b><i>Admin:</i></b> Responsible for managing user accounts, including creating, registering, and archiving users.</li>
                <li><b><i>Local Authorities:</i></b> Primary responsibility to monitor water levels, confirm alerts, and notify residents during flood events.</li>
                <li><b><i>Residents:</i></b> Can view the live stream and flood information on the homepage without logging in.</li>
            </ul>
        </p>
    </div>

    <div class="faq-section">
        <h2>5. Can residents log into <span class="highlight">FloodPing</span>?</h2>
        <p>
            No, residents do not have login access. They can view the live stream and essential flood updates on the homepage without logging in, as access is restricted to only Admins and Local Authorities.
        </p>
    </div>
    <div class="faq-section">
        <h2>6. What role do Local Authorities play in the <span class="highlight">FloodPing</span> system?</h2>
        <p>
            Local Authorities are critical to FloodPing's operation:
        </p>
        <ul>
            <li>They monitor water levels in real time.</li>
            <li>They confirm flood alerts sent by the system.</li>
            <li>
                They communicate with residents by sending SMS notifications that include instructions for evacuation, shelter locations, and other important information to guide residents during flood events.
            </li>
        </ul>
    </div>

    <div class="faq-section">
        <h2>7. How does <span class="highlight">FloodPing</span> protect user information and maintain system security?</h2>
        <p>
            FloodPing uses strict user-level access controls:
        </p>
        <ul>
            <li><b><i>Admin-only account management:</i></b> Only Admins can create, archive, and modify user accounts, limiting access to sensitive information.</li>
            <li><b><i>Confidential information handling:</i></b> Only authorized Local Authorities and Admins have access to the system's internal data, ensuring that resident information remains secure.</li>
        </ul>
    </div>

    <div class="faq-section">
        <h2>8. What should I do if I receive a “Low” alert from <span class="highlight">FloodPing</span>?</h2>
        <p>
            A <b><i>“Low”</i></b> alert means residents are advised to start self-evacuation procedures. Monitor updates from FloodPing’s live stream and follow SMS instructions sent by Local Authorities. These messages include essential details, such as where to evacuate and the safest routes.
        </p>
    </div>

    <div class="faq-section">
        <h2>9. What actions are required if water levels reach “Moderate” or “Critical”?</h2>
        <p>
            <span class="highlight">FloodPing</span> advises the following actions based on alert levels:
        </p>
        <ul>
            <li><b><i>Moderate:</i></b> A forced evacuation is advised. Residents should leave their homes and proceed to designated safe zones as instructed by Local Authorities.</li>
            <li><b><i>Critical:</i></b> Residents must stay at designated evacuation centers and await further instructions. At this level, it’s crucial to follow guidance from Local Authorities to ensure safety.</li>
        </ul>
    </div>

    <div class="faq-section">
        <h2>10. How do residents receive SMS alerts from FloodPing?</h2>
        <p>
            Once Local Authorities confirm a flood alert, they can send SMS notifications to residents registered in the system. This ensures that residents receive timely and relevant evacuation details, such as nearby evacuation centers and safety instructions.
        </p>
    </div>

    <div class="faq-section">
        <h2>11. Why does my password expires every month?</h2>
        <p>
            To ensure the security of your account and protect sensitive information, our system enforces a monthly password expiration policy. This minimizes the risk of unauthorized access by requiring regular password updates.
        </p>
    </div>

    <div class="link">
        <p>
            For more information or technical support, please contact FloodPing's support team at 
            <a href="mailto:floodping.official@gmail.com" class="email">floodping.official@gmail.com</a>.
        </p>
    </div>
</div>

<!-- footer -->
<footer class="footer">
    <div class="footer-container">
        <div class="footer-logo">
            <img src="../images/FloodPingLogo.png" alt="FloodPing Logo">
        </div>
        <div class="footer-links">
            <ul>
                <li><a href="privacy-policy.php">Privacy Policy</a></li>
                <li><a href="terms-conditions.php">Terms & Conditions</a></li>
                <li class="faq"><a href="FAQ.php">FAQ</a></li>
            </ul>
        </div>
        <div class="footer-social">
            <p>For news and updates, follow us on:</p>
            <a href="https://www.facebook.com" target="_blank" class="social-icon">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="https://www.instagram.com" target="_blank" class="social-icon">
                <i class="fab fa-instagram"></i>
            </a>
        </div>
    </div>
    <div class="footer-bottom">
        <p>© 2024 Quezon City University</p>
    </div>
</footer>
</body>
</html>
