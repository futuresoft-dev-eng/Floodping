<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Floodping | Privacy and Policy</title>
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

.privacy-policy {
    max-width: 1300px;
    margin: -40px auto;
    padding: 20px;
    background: #F5F5F5;;
}

#welcome-text {
    margin-left: 0px;
}

.privacy-policy h1 {
    font-size: 40px;
    font-weight: 500;
    color: #fff;
    margin: -130px 0px 0px 390px;
    position: absolute;
}

.privacy-policy h2 {
    font-size: 16px;
    margin-top: 20px;
    color: #000000;
}

.privacy-policy p {
    font-size: 14px;
    margin-bottom: 10px;
    color: #333;
    margin-left: 55px;
}

.privacy-policy ul {
    font-size: 14px;
    margin: 10px 0;
    padding-left: 20px;
}

.privacy-policy li {
    margin-bottom: 5px;
    margin-left: 20px;
}

.privacy-policy a {
    color: #1d4d7b;
    text-decoration: none;
}

.privacy-policy a:hover {
    text-decoration: underline;
}

.privacy-policy .last-updated {
    font-size: 14px;
    color: #888;
    margin-bottom: 20px;
    text-align: right;
}

/* footer */
.footer {
    background-color: #1D4D7B; 
    color: #fff; 
    padding: 5px 15px;
    font-family: Arial, sans-serif;
    text-align: center;
    margin: 200px auto;
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

     <!-- privacy-policy bg section -->
  <section class="bg-section" id="bg">
    <div class = "bg-photo">
        <img class="bg-img" src="images/bg.jpg" alt="background">   
    </div>

    <div class="privacy-policy">
        <p class="last-updated">Last Updated: Friday, November 1, 2024</p>
        <h1 class="title">FloodPing | Privacy and Policy</h1>
        <p id="welcome-text">
        <b><i>FloodPing</i></b> values your privacy and is committed to protecting your personal information. This Privacy Policy explains how we collect, use, and protect your data when you interact with our website, IoT-based flood monitoring system, and services.
        </p>

        <h2>1. Information We Collect</h2>
        <p>We collect limited personal information necessary to provide flood alerts and notifications effectively. The types of information we collect include:</p>
        <ul>
            <li><b><i>Personal Identification Information:</i></b> Name, phone number, and address (for registered residents and Local Authorities) to ensure accurate notifications during flood events.</li>
            <li><b><i>Usage Data:</i></b> Information on how users interact with the FloodPing platform, including the date and time of usage and IP addresses, to monitor and improve our services.</li>
        </ul>

        <h2>2. How We Use Your Information</h2>
        <p>FloodPing uses your data strictly to:</p>
        <ul>
            <li>Provide Alerts and Notifications: Send timely SMS and web alerts to Local Authorities and residents during flood situations based on water levels detected by our sensors.</li>
            <li>Manage User Accounts: Allow Admins to register and archive users for authorized access to the platform.</li>
            <li>Improve Services: Analyze usage data to optimize our platform and enhance system reliability, especially during critical events.</li>
        </ul>
        <p><b>FloodPing does not use your personal information for marketing purposes or share it with third parties for advertising.</b></p>

        <h2>3. How We Protect Your Information</h2>
        <p>FloodPing employs strict access control measures and technical safeguards to protect user data:</p>
        <ul>
            <li><b><i>Restricted Access:</i></b> Only Admins have full access to manage accounts and user data, ensuring sensitive information remains confidential.</li>
            <li><b><i>Data Encryption:</i></b> All sensitive data is encrypted during transmission and storage to prevent unauthorized access.</li>
            <li><b><i>Regular Security Audits:</i></b> We regularly review and update our security practices to comply with industry standards and maintain data protection.</li>
        </ul>

        <h2>4. Information Sharing and Disclosure</h2>
        <p>FloodPing only shares information as needed to ensure public safety:</p>
        <ul>
            <li><b><i>Local Authorities:</i></b> Necessary resident information, such as phone numbers, is shared with Local Authorities to issue evacuation alerts and safety instructions.</li>
            <li><b><i>Legal Compliance:</i></b> FloodPing may disclose information when required by law or to protect the rights, safety, and security of our users or the public.</li>
        </ul>
        <p><b>We do not sell or rent your information to any third parties.</b></p>

        <h2>5. Data Retention</h2>
        <p>
            FloodPing retains personal data for as long as necessary to fulfill the purposes outlined in this Privacy Policy, including during flood events and as required by law. Residents may request account deactivation at any time, and data will be securely archived or deleted according to our data retention policy.
        </p>

        <h2>6. User Rights and Choices</h2>
        <p>As a user, you have the right to:</p>
        <ul>
            <li><b><i>Access Your Data:</i></b> Request access to the personal data FloodPing holds about you.</li>
            <li><b><i>Update or Correct Information:</i></b> Request update any personal information if it is inaccurate or outdated.</li>
            <li><b><i>Delete Data:</i></b> Request deletion of your data, provided it is not required for ongoing safety monitoring.</li>
        </ul>

        <h2>7. Changes to This Privacy Policy</h2>
        <p>
            FloodPing may update this Privacy Policy periodically to reflect changes in our practices or for other operational, legal, or regulatory reasons. We will notify users of any significant updates by posting the revised policy on our website.
        </p>

        <h2>8. Contact Us</h2>
        <p>
            For requests, please contact our support team at 
            <a href="mailto:floodping.official@gmail.com"><i>floodping.official@gmail.com</i></a>
        </p>
    </div>


    <!-- footer -->
<footer class="footer">
    <div class="footer-container">
        <div class="footer-logo">
            <img src="images/FloodPingLogo.png" alt="FloodPing Logo">
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



