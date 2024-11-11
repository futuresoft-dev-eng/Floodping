<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Rounded" rel="stylesheet">
    <style>
        body {
            font-family: Poppins, sans-serif;
            background: var(--body-color);
            margin: 0;
            padding-top: 0;  
            overflow: hidden;
        }

        .sidebar {
            display: flex;
            flex-direction: column; 
            height: 100vh;
            background-color: #E8F3F8;
            padding-top: 20px;
            width: 250px;
            position: fixed;
            z-index: 1;
            overflow: hidden;
            transition: width 0.9s ease;
            overflow-y: auto; 
            scroll-behavior: smooth; 
            
        }

        .sidebar-logo {
            text-align: center;
            padding: 20px 0;
            background-color: #02476A;
            color: white;
        }

        .sidebar-logo img {
            width: 40px;
            margin-bottom: 5px;
        }

        .sidebar-content {
            flex-grow: 1;
            margin-top: 20px;
        }

        .time-section {
            color: white;
            padding: 15px;
            text-align: center;
            background-color: #02476A;
        }

        .time-section .time {
            font-size: 29px;
            font-weight: bold;
        }

        .time-section .date {
            font-size: 15px;
            margin-top: 5px;
        }

        .station {
            background-color: #386A83;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
            margin: 10px 15px;
            border-radius: 5px;
            font-size: 14px;
            border: none;
            position: relative;
        }

        .station::before {
            content: "";
            position: absolute;
            left: 10px;
            width: 12px;
            height: 12px;
            background-color: #F2CA00;
            border-radius: 50%;
        }

        .section-title {
            font-size: 16px;
            color: #4F8AA7;
            padding: 10px 20px 5px;
            margin-top: 20px;
        }

        .nav-link {
            color: #002D44;
            display: flex;
            align-items: center;
            padding: 10px 20px;
            border-radius: 0 10px 10px 0;
            transition: background-color 0.3s ease, padding 0.3s ease;
            margin: 0px 0px 10px 0px;
            font-size: 16px;
        }

        .nav-link:hover {
            background-color: #4597C0;
            color: #fff;
            border-radius: 0 20px 20px 0;
            padding-left: 25px;
        }

        .material-symbols-rounded {
            margin-right: 10px;
            font-size: 20px;
        }

        .user-info-container {
            margin-top: auto;
            background-color: #d6e7f0;
            padding: 10px;
            display: flex;
            align-items: center;
            border-top: 1px solid #e9ecef;
            gap: 10px;
            justify-content: space-between;
        }

        .user-link {
            color: #002D44;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-grow: 1;
        }

        .user-icon {
            font-size: 35px;
            color: #002D44;
        }

        .user-name {
            font-size: 16px;
            color: #000000;
        }

        .user-role {
            font-size: 12px;
            color: #0C517C;
        }

        .logout-button {
            font-size: 24px;
            color: #02476A;
            cursor: pointer;
        }

        .logout-button:hover {
            color: #02476A;
        }

        h2 {
            font-size: 14px;
            color: #02476A;
            font-weight: 500px;
            margin: 100px 0px 0px 10px;
        }

        /* Responsive styling */
        @media (max-width: 992px) {
            .sidebar {
                display: none; 
            }

            .top-bar {
                display: flex; 
                position: fixed;
                top: 0;
                width: 100%;
                height: 50px;
                background-color: #02476A;
                color: white;
                justify-content: space-around;
                align-items: center;
                z-index: 2;
            }
        }

        @media (max-width: 575px) {
            .top-bar .icon-text {
                display: none; 
            }
        }


    </style>
</head>

<body>
    <!-- Top bar -->
    <div class="top-bar d-md-none">
        <span class="material-symbols-rounded">dashboard</span>
        <span class="material-symbols-rounded">article</span>
        <span class="material-symbols-rounded">videocam</span>
        <span class="material-symbols-rounded">notifications_active</span>
        <span class="material-symbols-rounded">sms</span>
        <span class="material-symbols-rounded">history</span>
        <span class="material-symbols-rounded">account_circle</span>
        <span class="material-symbols-rounded logout-icon">logout</span>

    </div>

    <!-- Sidebar -->
    <nav class="sidebar d-none d-md-flex flex-column p-0">
        <div class="sidebar-logo">
            <img src="images/Floodpinglogo.png" alt="floodping">
            <div class="sidebar-logo-text" style="font-size: 19px; font-weight: bold;">FLOODPING</div>
        </div>

        <div class="time-section">
            <div class="time" id="current-time"></div>
            <div class="date" id="current-date"></div>
       

        <div class="station">DARIUS STATION</div>
        </div>
        <div class="sidebar-content">
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <span class="material-symbols-rounded">dashboard</span> <span class="icon-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <span class="material-symbols-rounded">article</span> <span class="icon-text">Reports</span>
                    </a>
                </li>

                <div class="section-title">Flood Monitoring</div>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <span class="material-symbols-rounded">videocam</span> <span class="icon-text">Live Camera Feed</span>
                    </a>
                </li>

                <div class="section-title">Alerts Management</div>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <span class="material-symbols-rounded">notifications_active</span> <span class="icon-text">Flood Alerts</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <span class="material-symbols-rounded">sms</span> <span class="icon-text">SMS Alerts</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <span class="material-symbols-rounded">history</span> <span class="icon-text">Flood History Logs</span>
                    </a>
                </li>
            </ul>
        </div>

        <h2>Logged in as:</h2>
<div class="user-info-container">
    <a href="#" class="user-link">
        <span class="material-symbols-rounded user-icon">account_circle</span>
        <div>
            <div class="user-name">JUNGWON YANG</div>
            <div class="user-role">Local Authority</div>
        </div>
    </a>
    <span class="material-symbols-rounded logout-button">chevron_right</span>
</div>

        
        
    <script>
        function updateTimeAndDate() {
            const now = new Date();
            let hours = now.getHours();
            let minutes = now.getMinutes();
            const ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12 || 12;
            minutes = minutes < 10 ? '0' + minutes : minutes;
            document.getElementById('current-time').textContent = `${hours}:${minutes} ${ampm}`;
            document.getElementById('current-date').textContent = now.toLocaleDateString(undefined, { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        }
        setInterval(updateTimeAndDate, 1000);
        updateTimeAndDate();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
