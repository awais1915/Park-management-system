<?php
session_start();
if(!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

include('connection.php');

// Handle parking slots update
if(isset($_POST['update'])) {
    $query1 = "SELECT * FROM Parking_Slot";
    $result1 = $conn->query($query1);
    $count_Parking_Slots_Occ = 0;
    $count_Parking_Slots = 0;
    
    if($result1->num_rows > 0) {
        while($row = $result1->fetch_assoc()) {
            if($row['Slot_Status'] == 'Occupied') {
                $count_Parking_Slots_Occ++;
            }
            $count_Parking_Slots++;
        }
    }
    
    $parking_slots = $_POST['parking_slots'];
    
    if($parking_slots < $count_Parking_Slots_Occ) {
        $error_message = "Update Failed! Invalid Slot Number.";
    } else if($parking_slots >= $count_Parking_Slots_Occ) {
        $query = "INSERT INTO Parking_Slot(Slot_Status) VALUES('Vacant')";
        while($count_Parking_Slots < $parking_slots) {
            $count_Parking_Slots++;
            $result = $conn->query($query);
        }
        $success_message = "Parking Slots Successfully Updated!";
        
        if($parking_slots > $count_Parking_Slots_Occ) {
            $query_del_check = "SELECT * FROM parking_slot";
            $result_del_check = $conn->query($query_del_check);
            
            if($result_del_check->num_rows > 0) {
                $count_del = $count_Parking_Slots - $parking_slots;
                while(($row = $result_del_check->fetch_assoc()) && ($count_del > 0)) {
                    $slot_id = 0;
                    if($row['Slot_Status'] == 'Vacant') {
                        $slot_id = $row['Slot_ID'];
                        $query_del_slot = "DELETE FROM parking_slot WHERE Slot_ID = '$slot_id'";
                        $res_del_slot = $conn->query($query_del_slot);
                        $count_del--;
                    }
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parking Management System - Manage Parking</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
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
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .header-title {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .admin-circle {
            width: 40px;
            height: 40px;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
            animation: float 3s ease-in-out infinite;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .header-title h2 {
            margin: 0;
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

        .parking-card {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            animation: fadeIn 0.8s ease-out;
            text-align: center;
        }

        .parking-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .parking-card h3 {
            color: var(--accent);
            margin-bottom: 20px;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .parking-stats {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }

        .stat {
            text-align: center;
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 600;
            color: var(--light);
        }

        .stat-label {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
        }

        /* Update Form */
        .update-form {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 30px;
        }

        .update-form h3 {
            color: var(--accent);
            margin-bottom: 20px;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .form-label {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 10px;
        }

        .btn-primary {
            background: var(--primary);
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: var(--secondary);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }

        /* Alerts */
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            animation: fadeIn 0.5s ease-out;
        }

        .alert-success {
            background: rgba(75, 181, 67, 0.2);
            color: var(--success);
            border: 1px solid var(--success);
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.2);
            color: var(--danger);
            border: 1px solid var(--danger);
        }

        /* Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
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
            .parking-stats {
                flex-direction: column;
                gap: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2 class="floating">Admin</h2>
        </div>
        <ul class="nav-links">
            <li><a href="/Database Project/Admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="/Database Project/Admin_Users.php"><i class="fas fa-users"></i> Users</a></li>
            <li><a href="/Database Project/Admin_Manage_Parking.php" class="active"><i class="fas fa-cogs"></i> Manage Parking</a></li>
            <li><a href="/Database Project/Admin_Find_User.php"><i class="fas fa-user"></i> Find User</a></li>
            <li><a href="/Database Project/Admin_ParkingFee.php"><i class="fas fa-dollar-sign"></i> Rates</a></li>
            <li><a href="/Database Project/Admin_Find_Reports.php"><i class="fas fa-chart-bar"></i> Reports</a></li>
            <li><a href="/Database Project/login.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <div class="header-title">
                <div class="admin-circle">A</div>
                <h2>Parking Management System</h2>
            </div>
            <div class="breadcrumbs">
                <a href="#">Home</a> > <span>Manage Parking</span>
            </div>
        </header>

        <?php if(isset($success_message)): ?>
            <div class="alert alert-success">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <?php if(isset($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <div class="dashboard">
            <!-- Parking Stats Card -->
            <div class="parking-card">
                <h3>Parking Slots Overview</h3>
                <?php
                    $query_Find_Parking_Slots = "SELECT * FROM Parking_Slot";
                    $result = $conn->query($query_Find_Parking_Slots);
                    $count_Parking_Slots = 0;
                    $count_Available_Parking_Slots = 0;
                    
                    if($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $count_Parking_Slots++;
                            if($row['Slot_Status'] == 'Vacant') {
                                $count_Available_Parking_Slots++;
                            }
                        }
                    }
                ?>
                <div class="parking-stats">
                    <div class="stat">
                        <div class="stat-value"><?php echo $count_Parking_Slots; ?></div>
                        <div class="stat-label">Total Slots</div>
                    </div>
                    <div class="stat">
                        <div class="stat-value"><?php echo $count_Available_Parking_Slots; ?></div>
                        <div class="stat-label">Available Slots</div>
                    </div>
                    <div class="stat">
                        <div class="stat-value"><?php echo $count_Parking_Slots - $count_Available_Parking_Slots; ?></div>
                        <div class="stat-label">Occupied Slots</div>
                    </div>
                </div>
            </div>

            <!-- Update Form -->
            <div class="update-form">
                <h3>Update Parking Slots</h3>
                <form action="Admin_Manage_Parking.php" method="post">
                    <div class="mb-3">
                        <label for="parking_slots" class="form-label">Enter New Parking Slots</label>
                        <input type="number" class="form-control" id="parking_slots" name="parking_slots" required>
                    </div>
                    <button type="submit" name="update" class="btn btn-primary">Update Slots</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Add any interactive JavaScript here if needed
    </script>
</body>
</html>