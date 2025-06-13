<?php
session_start();
if(!isset($_SESSION['message'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parking Management System - User Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
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
            --sidebar: #1e293b;
            --card-bg: rgba(255, 255, 255, 0.05);
            --text-primary: #e2e8f0;
            --text-secondary: #94a3b8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, var(--dark), #16213e);
            color: var(--text-primary);
            min-height: 100vh;
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
        }

        .sidebar-header {
            padding: 25px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
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
            color: var(--text-secondary);
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

        /* Main Content */
        .main-content {
            margin-left: 280px;
            padding: 30px;
            transition: all 0.3s ease;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header-title h2 {
            margin: 0;
            color: white;
            font-size: 1.8rem;
            font-weight: 600;
        }

        .breadcrumbs {
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .breadcrumbs a {
            color: var(--accent);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .breadcrumbs a:hover {
            text-decoration: underline;
        }

        /* Enhanced Profile Card */
        .profile-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            max-width: 500px;
            margin: 0 auto;
        }

        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .profile-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }

        .profile-icon i {
            font-size: 24px;
            color: white;
        }

        .profile-header h4 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
            background: linear-gradient(to right, var(--accent), var(--primary));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .profile-details {
            display: grid;
            gap: 15px;
        }

        .detail-item {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .detail-item:hover {
            background: rgba(76, 201, 240, 0.1);
            transform: translateX(5px);
        }

        .detail-icon {
            width: 36px;
            height: 36px;
            background: rgba(76, 201, 240, 0.1);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }

        .detail-icon i {
            color: var(--accent);
            font-size: 16px;
        }

        .detail-content {
            display: flex;
            flex-direction: column;
        }

        .detail-label {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.6);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }

        .detail-value {
            font-size: 1rem;
            font-weight: 500;
            color: white;
        }

        .btn-edit-profile {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 20px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            margin-top: 25px;
            transition: all 0.3s ease;
        }

        .btn-edit-profile:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
            color: white;
        }

        .btn-edit-profile i {
            margin-right: 8px;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-280px);
            }
            .main-content {
                margin-left: 0;
            }
        }

        @media (max-width: 768px) {
            header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            .header-title h2 {
                font-size: 1.5rem;
            }
            .profile-card {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>User Panel</h2>
        </div>
        <ul class="nav-links">
            <li><a href="/Database Project/dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="/Database Project/book_parking.php"><i class="fas fa-parking"></i> Book Parking</a></li>
            <li><a href="/Database Project/parked_vehicles.php"><i class="fas fa-car-side"></i> Parked Vehicles</a></li>
            <li><a href="/Database Project/unpark_vehicle.php"><i class="fas fa-car-alt"></i> Un-Park Vehicle</a></li>
            <li><a href="/Database Project/payment_history.php"><i class="fas fa-dollar-sign"></i> Payment History</a></li>
            <li><a href="/Database Project/parking_history.php"><i class="fas fa-list"></i> Parking History</a></li>
            <li><a href="/Database Project/login.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <div class="header-title">
                <h2>Parking Management System</h2>
            </div>
            <div class="breadcrumbs">
                <a href="/Database Project/dashboard.php">Home</a> > <span>Dashboard</span>
            </div>
        </header>

        <div class="dashboard">
            <div class="profile-card">
                <div class="profile-header">
                    <div class="profile-icon">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <h4>User Profile</h4>
                </div>
                
                <div class="profile-details">
                    <?php
                    if (isset($_SESSION['message'])) {
                        $user_id = $_SESSION['message'];
                        include('connection.php');

                        $query = "SELECT * FROM user";
                        $result = $conn->query($query);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                if ($user_id == $row['User_ID']) {
                                    $user_name = $row['Name'];
                                    $email = $row['Email'];
                                    $age = $row['Age'];
                                    $contact_no = $row['Contact_Number'];
                                    ?>
                                    <div class="detail-item">
                                        <div class="detail-icon">
                                            <i class="fas fa-id-card"></i>
                                        </div>
                                        <div class="detail-content">
                                            <span class="detail-label">Name</span>
                                            <span class="detail-value"><?php echo htmlspecialchars($user_name); ?></span>
                                        </div>
                                    </div>
                                    
                                    <div class="detail-item">
                                        <div class="detail-icon">
                                            <i class="fas fa-birthday-cake"></i>
                                        </div>
                                        <div class="detail-content">
                                            <span class="detail-label">Age</span>
                                            <span class="detail-value"><?php echo htmlspecialchars($age); ?></span>
                                        </div>
                                    </div>
                                    
                                    <div class="detail-item">
                                        <div class="detail-icon">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <div class="detail-content">
                                            <span class="detail-label">Email</span>
                                            <span class="detail-value"><?php echo htmlspecialchars($email); ?></span>
                                        </div>
                                    </div>
                                    
                                    <div class="detail-item">
                                        <div class="detail-icon">
                                            <i class="fas fa-phone"></i>
                                        </div>
                                        <div class="detail-content">
                                            <span class="detail-label">Contact No</span>
                                            <span class="detail-value"><?php echo htmlspecialchars($contact_no); ?></span>
                                        </div>
                                    </div>
                                    <?php
                                }          
                            }
                        }
                    }
                    ?>
                </div>
                
                <a href="edit_profile.php" class="btn-edit-profile">
                    <i class="fas fa-pencil-alt"></i> Edit Profile
                </a>
            </div>
        </div>
    </div>
</body>
</html>