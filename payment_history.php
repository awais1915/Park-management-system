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
    <title>Parking Management System - Payment History</title>
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
            --success: #4bb543;
            --warning: #f59e0b;
            --danger: #ef4444;
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

        .header-title {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-circle {
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
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .header-title h2 {
            margin: 0;
            color: var(--text-primary);
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

        /* Content Container */
        .content-container {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .page-title {
            color: var(--accent);
            margin-bottom: 25px;
            font-weight: 600;
            position: relative;
            padding-bottom: 10px;
        }

        .page-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: var(--primary);
        }

        /* Table Styling */
        .table-container {
            overflow-x: auto;
            margin-top: 20px;
        }

        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
            color: var(--text-primary);
        }

        .table thead {
            background: rgba(67, 97, 238, 0.2);
            backdrop-filter: blur(5px);
        }

        .table th {
            padding: 15px;
            text-align: center;
            font-weight: 500;
            color: var(--accent);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Updated Table Row Styling */
        .table tbody tr {
            background: rgba(30, 41, 59, 0.5);
            margin: 10px 0;
            border-radius: 10px;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .table tbody tr td {
            padding: 15px;
            text-align: center;
            vertical-align: middle;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .table tbody tr td:first-child {
            border-left: 1px solid rgba(255, 255, 255, 0.05);
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }

        .table tbody tr td:last-child {
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        .table tbody tr:hover {
            background: rgba(67, 97, 238, 0.15);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        /* Color display styling */
        .color-display {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 5px 12px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.05);
        }

        /* Amount badge styling */
        .badge-amount {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            background: rgba(75, 181, 67, 0.2);
            color: var(--success);
            font-weight: 500;
            min-width: 80px;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 50px 20px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            margin-top: 30px;
            border: 1px dashed rgba(255, 255, 255, 0.1);
        }

        .empty-state i {
            font-size: 3rem;
            color: var(--text-secondary);
            margin-bottom: 20px;
        }

        .empty-state h3 {
            color: var(--text-primary);
            margin-bottom: 15px;
        }

        .empty-state p {
            color: var(--text-secondary);
            max-width: 500px;
            margin: 0 auto 20px;
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
            .content-container {
                padding: 20px;
            }
            .table td, .table th {
                padding: 10px 8px;
                font-size: 0.9rem;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
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
            <li><a href="/Database Project/dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="/Database Project/book_parking.php"><i class="fas fa-parking"></i> Book Parking</a></li>
            <li><a href="/Database Project/parked_vehicles.php"><i class="fas fa-car-side"></i> Parked Vehicles</a></li>
            <li><a href="/Database Project/unpark_vehicle.php"><i class="fas fa-car-alt"></i> Un-Park Vehicle</a></li>
            <li><a href="/Database Project/payment_history.php" class="active"><i class="fas fa-dollar-sign"></i> Payment History</a></li>
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
                <a href="/Database Project/dashboard.php">Home</a> > <span>Payment History</span>
            </div>
        </header>

        <div class="content-container">
            <h1 class="page-title">
                <i class="fas fa-history"></i> Payment History
            </h1>
            
            <?php
            if (isset($_SESSION['message'])) {
                $user_id = $_SESSION['message'];
                include('connection.php');
                
                $query = "SELECT v.Make AS Vehicle_Make, v.Vehicle_Type AS Vehicle_Type, 
                         v.Color AS Vehicle_Color, v.Vehicle_Number AS Vehicle_Number, 
                         p.Amount AS Payment_Amount, p.Payment_Date AS Payment_Date 
                         FROM vehicle v 
                         INNER JOIN payment p ON v.Vehicle_ID = p.Vehicle_ID 
                         WHERE v.User_ID = ? 
                         ORDER BY p.Payment_Date DESC";
                
                $stmt = $conn->prepare($query);
                $stmt->bind_param("s", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if($result->num_rows > 0): 
            ?>
            
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Vehicle Make</th>
                            <th>Vehicle Type</th>
                            <th>Color</th>
                            <th>Vehicle Number</th>
                            <th>Amount</th>
                            <th>Payment Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            while($row = $result->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['Vehicle_Make']); ?></td>
                            <td><?php echo htmlspecialchars($row['Vehicle_Type']); ?></td>
                            <td>
                                <span class="color-display">
                                    <i class="fas fa-circle" style="color: <?php echo htmlspecialchars($row['Vehicle_Color']); ?>"></i>
                                    <?php echo htmlspecialchars($row['Vehicle_Color']); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($row['Vehicle_Number']); ?></td>
                            <td>
                                <span class="badge-amount">
                                    Rs. <?php echo htmlspecialchars($row['Payment_Amount']); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($row['Payment_Date']); ?></td>
                        </tr>
                        <?php 
                            endwhile;
                        ?>
                    </tbody>
                </table>
            </div>
            
            <?php else: ?>
            
            <div class="empty-state">
                <i class="fas fa-wallet"></i>
                <h3>No Payment History Found</h3>
                <p>You haven't made any payments yet. Your payment history will appear here after you complete transactions.</p>
            </div>
            
            <?php 
                endif;
            }
            ?>
        </div>
    </div>
</body>
</html>