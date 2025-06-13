<?php
session_start();
if(!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

include('connection.php');

// Handle user deletion
if(isset($_GET['User_ID'])) {
    $user_id = intval($_GET['User_ID']);
    $query_check = "SELECT COUNT(*) as Parked_Cars FROM booking WHERE User_ID = '$user_id' AND Exit_Time = '00:00:00'";
    $res_check = $conn->query($query_check);
    $count_par = $res_check->fetch_assoc();
    
    if($count_par['Parked_Cars'] == 0) {
        $query_user = "DELETE FROM user WHERE User_ID = '$user_id'";
        $result_user_del = $conn->query($query_user);
        $query_register = "DELETE FROM register WHERE Reg_ID = '$user_id'";
        $result_reg_del = $conn->query($query_register);
        
        if($result_reg_del && $result_user_del) {
            $success_message = "Account deleted successfully";
        } else {
            $error_message = "Error deleting account";
        }
    } else {
        $error_message = "⚠️ This user has parked cars. Please contact them to unpark before deletion.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parking Management System - Users</title>
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

        /* Table Styles */
        .table-container {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            color: var(--light);
        }

        th {
            background: rgba(76, 201, 240, 0.1);
            color: var(--accent);
            padding: 15px;
            text-align: left;
        }

        td {
            padding: 12px 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        tr:hover {
            background: rgba(255, 255, 255, 0.03);
        }

        .btn-delete {
            background: var(--danger);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-delete:hover {
            background: darkred;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3);
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
            <li><a href="/Database Project/Admin_Users.php" class="active"><i class="fas fa-users"></i> Users</a></li>
            <li><a href="/Database Project/Admin_Manage_Parking.php"><i class="fas fa-cogs"></i> Manage Parking</a></li>
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
                <a href="#">Home</a> > <span>Users</span>
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

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Contact No</th>
                        <th>User ID</th>
                        <th>Parked Vehicles</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT * FROM User";
                    $result = $conn->query($query);
                    if($result->num_rows > 0) {
                        $count = 1;
                        while($row = $result->fetch_assoc()) {
                            $count_parked = 0;
                            $count_parked_veh = "SELECT * FROM booking";
                            $result_count_parked_veh = $conn->query($count_parked_veh);
                            while($row1 = $result_count_parked_veh->fetch_assoc()) {
                                if(($row['User_ID'] == $row1['User_ID']) && ($row1['Status'] == 'UnPaid')) {
                                    $count_parked++;
                                }
                            }
                            ?>
                            <tr>
                                <td><?php echo $count++; ?></td>
                                <td><?php echo htmlspecialchars($row['Name']); ?></td>
                                <td><?php echo htmlspecialchars($row['Contact_Number']); ?></td>
                                <td><?php echo htmlspecialchars($row['User_ID']); ?></td>
                                <td><?php echo $count_parked; ?></td>
                                <td>
                                    <a href="/Database Project/Admin_Users.php?User_ID=<?php echo $row['User_ID']; ?>">
                                        <button class="btn-delete">Delete</button>
                                    </a>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo '<tr><td colspan="6" style="text-align: center;">No users found</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>