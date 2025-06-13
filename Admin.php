<?php
session_start();
if ( ! isset($_SESSION['admin_logged_in']) ) {
    header('Location: login.php');
    exit();
}

include('connection.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parking Management System - Admin Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --accent: #4cc9f0;
            --dark: #1a1a2e;
            --light: #f8f9fa;
            --success: #4bb543;
            --warning: #f59e0b;
            --danger: #ef4444;
            --sidebar: #1e293b;
            --card-bg: rgba(255, 255, 255, 0.05);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, var(--dark), #16213e);
            color: var(--light);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            height: 100vh;
            background: var(--sidebar);
            position: fixed;
            left: 0;
            top: 0;
            z-index: 100;
            transition: all 0.3s ease;
            box-shadow: 5px 0 15px rgba(0, 0, 0, 0.1);
            transform: translateX(0);
        }

        .sidebar.collapsed {
            transform: translateX(-280px);
        }

        .sidebar-header {
            padding: 25px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h2 {
            color: var(--accent);
            font-size: 1.5rem;
            font-weight: 600;
            background: linear-gradient(to right, var(--accent), var(--primary));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-links {
            list-style: none;
            padding: 20px 0;
        }

        .nav-links li {
            position: relative;
            margin: 5px 15px;
            border-radius: 8px;
            overflow: hidden;
        }

        .nav-links li::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(76, 201, 240, 0.1), transparent);
            transition: 0.5s;
        }

        .nav-links li:hover::before {
            left: 100%;
        }

        .nav-links a {
            display: flex;
            align-items: center;
            text-decoration: none;
            padding: 15px 20px;
            color: rgba(255, 255, 255, 0.7);
            transition: all 0.3s ease;
            border-radius: 8px;
        }

        .nav-links a:hover,
        .nav-links a.active {
            background: rgba(76, 201, 240, 0.1);
            color: var(--accent);
            transform: translateX(5px);
        }

        .nav-links i {
            width: 25px;
            font-size: 1.1rem;
            margin-right: 15px;
            text-align: center;
        }

        .nav-links .active {
            background: rgba(76, 201, 240, 0.1);
            color: var(--accent);
            border-left: 3px solid var(--accent);
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            padding: 30px;
            transition: all 0.3s ease;
        }

        .main-content.expanded {
            margin-left: 0;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        header h2 {
            color: var(--light);
            font-size: 1.8rem;
            font-weight: 600;
        }

        .breadcrumbs {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.6);
        }

        .breadcrumbs a {
            color: var(--accent);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .breadcrumbs a:hover {
            text-decoration: underline;
        }

        /* Dashboard Cards */
        .dashboard {
            display: grid;
            grid-template-columns: 1fr;
            gap: 25px;
        }

        .profile-card {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            animation: fadeIn 0.8s ease-out;
        }

        .profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .profile-card h2 {
            color: var(--accent);
            margin-bottom: 20px;
            font-size: 1.5rem;
            font-weight: 600;
            position: relative;
            padding-bottom: 10px;
        }

        .profile-card h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: var(--accent);
            border-radius: 3px;
        }

        .profile-card h3 {
            color: rgba(255, 255, 255, 0.8);
            margin: 15px 0;
            font-weight: 400;
            font-size: 1rem;
        }

        .profile-card h3 span {
            color: var(--light);
            font-weight: 500;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 25px;
            margin-top: 25px;
        }

        .stat-card {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            text-align: center;
            animation: fadeInUp 0.8s ease-out;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--accent);
        }

        .stat-card:nth-child(1)::before { background: var(--primary); }
        .stat-card:nth-child(2)::before { background: var(--warning); }
        .stat-card:nth-child(3)::before { background: var(--success); }
        .stat-card:nth-child(4)::before { background: var(--danger); }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .stat-card h2 {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1rem;
            font-weight: 400;
            margin-bottom: 15px;
        }

        .stat-card h3 {
            color: var(--light);
            font-size: 2.5rem;
            font-weight: 600;
            margin: 10px 0;
        }

        .stat-card p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes fadeInUp {
            from { 
                opacity: 0;
                transform: translateY(20px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        /* Floating elements */
        .floating {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-280px);
            }
            .sidebar.collapsed {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
            .main-content.expanded {
                margin-left: 280px;
            }
            .stats-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .stats-container {
                grid-template-columns: 1fr;
            }
            header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }

       /* Toggle button */
.toggle-btn {
    position: fixed;
    left: 240px; 
    top: 25px; 
    background: var(--accent);
    color: white;
    border: none;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 101;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
    transform: translateX(0);
}

.toggle-btn:hover {
    transform: scale(1.1) translateX(0);
}

.toggle-btn.collapsed {
    left: 20px; 
    transform: translateX(0);
}
        /* Background animation */
        .bg-elements {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .bg-elements li {
            position: absolute;
            display: block;
            list-style: none;
            width: 20px;
            height: 20px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: animate 25s linear infinite;
            bottom: -150px;
            border-radius: 50%;
        }

        .bg-elements li:nth-child(1) {
            left: 25%;
            width: 80px;
            height: 80px;
            animation-delay: 0s;
        }

        .bg-elements li:nth-child(2) {
            left: 10%;
            width: 20px;
            height: 20px;
            animation-delay: 2s;
            animation-duration: 12s;
        }

        .bg-elements li:nth-child(3) {
            left: 70%;
            width: 20px;
            height: 20px;
            animation-delay: 4s;
        }

        .bg-elements li:nth-child(4) {
            left: 40%;
            width: 60px;
            height: 60px;
            animation-delay: 0s;
            animation-duration: 18s;
        }

        .bg-elements li:nth-child(5) {
            left: 65%;
            width: 20px;
            height: 20px;
            animation-delay: 0s;
        }

        @keyframes animate {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 1;
                border-radius: 0;
            }
            100% {
                transform: translateY(-1000px) rotate(720deg);
                opacity: 0;
                border-radius: 50%;
            }
        }
    </style>
</head>
<body>
    <!-- Background animation elements -->
    <ul class="bg-elements">
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
    </ul>
    <!-- Toggle Button -->
    <button class="toggle-btn" id="toggleBtn">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2 class="floating">Admin</h2>
        </div>
        <ul class="nav-links">
            <li><a href="/Database Project/Admin.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="/Database Project/Admin_Users.php"><i class="fas fa-users"></i> Users</a></li>
            <li><a href="/Database Project/Admin_Manage_Parking.php"><i class="fas fa-cogs"></i> Manage Parking</a></li>
            <li><a href="/Database Project/Admin_Find_User.php"><i class="fas fa-user"></i> Find User</a></li>
            <li><a href="/Database Project/Admin_ParkingFee.php"><i class="fas fa-dollar-sign"></i> Rates</a></li>
            <li><a href="/Database Project/Admin_Find_Reports.php"><i class="fas fa-chart-bar"></i> Reports</a></li>
            <li><a href="/Database Project/login.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <header>
            <h2>Parking Management System</h2>
            <div class="breadcrumbs">
                <a href="#">Home</a> > <span>Dashboard</span>
            </div>
        </header>

        <div class="dashboard">
            <!-- Profile Card -->
            <div class="profile-card">
                <h2>About</h2>
                <?php
                    $query = "SELECT * FROM Register_Admin WHERE ID = 1";
                    $result = $conn->query($query);
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        echo '<h3><span>Name:</span> ' . htmlspecialchars($row['Name']) . '</h3>';
                        echo '<h3><span>Email:</span> ' . htmlspecialchars($row['Email']) . '</h3>';
                        echo '<h3><span>Contact No:</span> ' . htmlspecialchars($row['Contact_No']) . '</h3>';
                    }
                ?> 
            </div>

            <!-- Stats Cards -->
            <div class="stats-container">
                <!-- Total Slots -->
                <div class="stat-card">
                    <h2>Total Slots</h2>
                    <?php
                        $query = "SELECT * FROM Parking_Slot";
                        $result = $conn->query($query);
                        $count = $result->num_rows;
                        echo '<h3>' . $count . '</h3>';
                        echo '<p>Available parking spaces</p>';
                    ?>
                </div>

                <!-- Booked Slots -->
                <div class="stat-card">
                    <h2>Booked Slots</h2>
                    <?php
                        $query = "SELECT * FROM Parking_Slot WHERE Slot_Status = 'Occupied'";
                        $result = $conn->query($query);
                        $count = $result->num_rows;
                        echo '<h3>' . $count . '</h3>';
                        echo '<p>Currently occupied</p>';
                    ?>
                </div>

                <!-- Active Users -->
                <div class="stat-card">
                    <h2>Active Users</h2>
                    <?php
                        $query = "SELECT * FROM user";
                        $result = $conn->query($query);
                        $count = $result->num_rows;
                        echo '<h3>' . $count . '</h3>';
                        echo '<p>Registered users</p>';
                    ?>
                </div>

                <!-- Total Payments -->
                <div class="stat-card">
                    <h2>Total Payments</h2>
                    <?php
                        $query = "SELECT SUM(Amount) as total FROM payment";
                        $result = $conn->query($query);
                        $row = $result->fetch_assoc();
                        $total = $row['total'] ? $row['total'] : 0;
                        echo '<h3>$' . number_format($total, 2) . '</h3>';
                        echo '<p>Total revenue</p>';
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle sidebar
        const toggleBtn = document.getElementById('toggleBtn');
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.getElementById('mainContent');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            toggleBtn.classList.toggle('collapsed');
            
            // Change icon
            const icon = toggleBtn.querySelector('i');
            if (sidebar.classList.contains('collapsed')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-chevron-right');
            } else {
                icon.classList.remove('fa-chevron-right');
                icon.classList.add('fa-bars');
            }
        });

        // Add more background elements dynamically
        function addBgElements() {
            const bgContainer = document.querySelector('.bg-elements');
            const colors = ['rgba(76, 201, 240, 0.1)', 'rgba(67, 97, 238, 0.1)', 'rgba(249, 168, 37, 0.1)'];
            
            for (let i = 0; i < 10; i++) {
                const element = document.createElement('li');
                element.style.left = Math.random() * 100 + '%';
                element.style.width = Math.random() * 30 + 10 + 'px';
                element.style.height = element.style.width;
                element.style.background = colors[Math.floor(Math.random() * colors.length)];
                element.style.animationDelay = Math.random() * 10 + 's';
                element.style.animationDuration = Math.random() * 20 + 10 + 's';
                element.style.borderRadius = Math.random() > 0.5 ? '50%' : '0';
                bgContainer.appendChild(element);
            }
        }

        // Initialize
        addBgElements();
    </script>
</body>
</html>