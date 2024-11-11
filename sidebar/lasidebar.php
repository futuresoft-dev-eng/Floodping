<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mejo Responsive</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Rounded" rel="stylesheet">
    <style>
        body {
            font-family: Poppins, sans-serif;
            background: var(--body-color);
            margin: 0;
            padding-top: 0; 
        }
        .sidebar {
            height: 100vh;
            background-color: #e8f3f8;
            padding-top: 20px;
            width: 250px;
            position: fixed;
            overflow: hidden;
            z-index: 1;
            transition: width 0.3s ease;
        }
        .sidebar-logo {
            background-color: #02476A;
            text-align: center;
            padding: 10px 0;
        }
        .sidebar-logo img {
            width: 50px;
            margin: 5px;
            margin: 0px 0px 0px -50px;
        }
        .sidebar-logo-text {
            color: white;
            font-size: 14px;
        }
        .sidebar-content {
            margin-top: 20px;
        }
  
        .sidebar .nav-link {
            color: #002D44;
            display: flex;
            align-items: center;
            padding: 10px 20px;
            border-radius: 0 10px 10px 0;
            transition: background-color 0.3s ease, padding 0.3s ease;
            margin: 0px 0px 10px 0px;
            font-size: 16px;
        }
        .sidebar .nav-link:hover {
            background-color: #4597C0;
            color: #fff;
            border-radius: 0 20px 20px 0;
            padding-left: 25px;
        }
        .sidebar .nav-link.active {
            background-color: #4597C0;
            color: #fff;
            border-radius: 0 50px 50px 0;
            padding: 20px 20px;
        }
        .material-symbols-rounded {
            margin-right: 10px;
            font-size: 20px;
        }

        .user-info {
            position: absolute;
            bottom: 0;
            width: 100%;
            background-color: #d6e7f0;
            padding: 10px;
            display: flex;
            align-items: center;
            border-top: 1px solid #e9ecef;
            gap: 10px;
        }
        .user-link {
            color: #002D44;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-grow: 1;
        }
        .user-link img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
        }
        .user-name {
            font-size: 16px;
            color: #000000;
        }
        .user-role {
            font-size: 12px;
            color: #0C517C;
        }
        .arrow-icon {
            background-color: #1a4a63;
            color: #fff;
            font-size: 40px;
            padding: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 62px;
            margin: 668px 0px 0px 200px;
            position: absolute;
        }

        /* nadagdag */
        .section-title {
            font-size: 14px;
            color: #4F8AA7;
            padding: 5px 20px;
            margin: 20px 0px 10px 0px;
        }

        h2 {
            font-size: 14px;
            color: #02476A;
            font-weight: 500px;
            margin: 68px 0px 0px 20px;
        }

        .header {
            position: fixed;
            top: 0;
            left: 250px;
            right: 0;
            height: 92px;
            background-color: #ffffff;
            display: flex;
            align-items: center;
            padding: 0 20px;
            z-index: 1000;
        }
        .header-title {
            font-size: 20px;
            font-weight: bold;
            color: #02476A;
        }
        
        .bgwaves-image {
            height: 80%;
            width: 2000px !important;
        }

        .header h2 {
            margin: 65px 0px 0px 0px;
            position: absolute;
            width: 96.9%;
            height: 30%;
            background-color: #2D82AD;
            color: #fff;
            padding: 5px 20px;
        }
      
        @media (max-width: 992px) and (min-width: 576px) {
            .sidebar {
                width: 80px;
            }
            .sidebar .nav-link {
                justify-content: center;
                font-size: 14px;
                padding-left: 0;
                text-align: center;
            }
            .sidebar-logo-text,
            .sidebar .nav-link span:not(.material-symbols-rounded),
            .user-info .user-name,
            .user-info .user-role {
                display: none;
            }
            .content {
                margin-left: 80px; 
            }
        }

        @media (max-width: 575px) {
            .sidebar {
                display: none;
            }
            .top-bar {
                display: flex;
                justify-content: space-between;
                align-items: center;
                position: fixed;
                top: 0;
                width: 100%;
                height: 56px;
                background-color: #e8f3f8;
                padding: 5px 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                z-index: 1000;
            }
            .top-bar .top-logo img {
                width: 30px;
            }
            .top-bar .nav-link {
                padding: 0;
                margin: 0 8px;
                font-size: 14px;
            }
            .top-bar .material-symbols-rounded {
                font-size: 24px;
                margin-right: 0;
            }
            .top-bar .nav-link span:not(.material-symbols-rounded) {
                display: none;
            }
            .content {
                margin-top: 56px;
                padding: 20px;
                width: 100%; 
            }
        }
        .content {
            padding: 20px;
            margin-left: 250px; 
            transition: margin-left 0.3s ease; 
        }
        @media (max-width: 992px) {
            .content {
                margin-left: 0; 
            }
        }
    </style>
</head>

<body>
    <div class="top-bar d-md-none">
        <div class="top-logo">
            <img src="images/Floodpinglogo.png" alt="FloodPing">
            <img src="images/brgy-logo.png" alt="Bagbag">
        </div>
        <div class="d-flex align-items-center">
            <a href="#" class="nav-link">
                <span class="material-symbols-rounded">dashboard</span>
            </a>
            <a href="#" class="nav-link">
                <span class="material-symbols-rounded">article</span>
            </a>
            <a href="#" class="nav-link">
                <span class="material-symbols-rounded">videocam</span>
            </a>
            <a href="#" class="nav-link">
                <span class="material-symbols-rounded">notifications_active</span>
            </a>
            <a href="#" class="nav-link">
                <span class="material-symbols-rounded">sms</span>
            </a>
            <a href="#" class="nav-link">
                <span class="material-symbols-rounded">history</span>
            </a>
            <a href="#" class="user-link">
                <span class="material-symbols-rounded">account_circle</span>
            </a>
            <div class="logout-container">
                <span class="material-symbols-rounded logout-icon">logout</span>
            </div>
        </div>
    </div>

    <nav class="sidebar d-none d-md-flex flex-column p-0">
        <div class="sidebar-logo">
            <img src="images/Floodpinglogo.png" alt="floodping">
            <img src="images/bagbaglogo.png" alt="bagbag">
            <div class="sidebar-logo-text">FLOODPING x BAGBAG</div>
        </div>
        <div class="sidebar-content">
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="#" class="nav-link active">
                        <span class="material-symbols-rounded">dashboard</span> <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <span class="material-symbols-rounded">article</span> <span>Reports</span>
                    </a>
                </li>

                <div class="section-title">Flood Monitoring</div>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <span class="material-symbols-rounded">videocam</span> <span>Live Camera</span>
                    </a>
                </li>

                <div class="section-title">Alerts Management</div>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <span class="material-symbols-rounded">notifications_active</span> <span>Flood Alerts</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <span class="material-symbols-rounded">sms</span> <span>SMS Alert</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <span class="material-symbols-rounded">history</span> <span>Flood Logs</span>
                    </a>
                </li>
            </ul>
        </div>

        <h2>Logged in as:</h2>
        <div class="user-info">
            <a href="#" class="user-link">
                <span class="material-symbols-rounded user-icon">account_circle</span>
                <div>
                    <div class="user-name">JUNGWON YANG</div>
                    <div class="user-role">Local Authority</div>
                </div>
            </a>
        </div>
        <span class="material-symbols-rounded arrow-icon">chevron_right</span>
    </nav>

    <header class="header">
        
        <img class="bgwaves-image" src="images/bgwaves.jpg" alt="Description of the image">  
        <h2>FLOODPING: FLOOD MONITORING AND ALERT SYSTEM</h2>
    </header>

    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
