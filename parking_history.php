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
    <title>Parking Management System - Parking History</title>
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
            --text-primary:rgb(0, 0, 0);
            --text-secondary: #94a3b8;
            --vehicle-type: #a78bfa;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(to right, #1a1a2e, #16213e);
            color: var(--text-primary);
            min-height: 100vh;
        }

        .sidebar {
            width: 280px;
            height: 100vh;
            background: var(--sidebar);
            position: fixed;
            left: 0;
            top: 0;
            z-index: 100;
            transition: all 0.3s ease;
            box-shadow: 5px 0 15px rgba(0, 0, 0, 0.3);
        }

        .sidebar-header {
            padding: 25px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h2 {
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
            margin: 5px 15px;
            border-radius: 8px;
            overflow: hidden;
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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .header-title {
            display: flex;
            align-items: center;
            gap: 15px;
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
        }

        .breadcrumbs a:hover {
            text-decoration: underline;
        }

        .content-container {
            background: var(--card-bg);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .page-title {
            color: var(--accent);
            margin-bottom: 25px;
            font-weight: 600;
            text-align: center;
            position: relative;
            padding-bottom: 10px;
        }

        .page-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: var(--accent);
        }

        /* Enhanced Table Styling */
        .table-container {
            overflow-x: auto;
            margin-top: 20px;
        }

        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
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

        .table tbody tr {
            background: rgba(30, 41, 59, 0.5);
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .table td {
            padding: 15px;
            text-align: center;
            vertical-align: middle;
            color: var(--text-primary);
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .table td:first-child {
            border-left: 1px solid rgba(255, 255, 255, 0.05);
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }

        .table td:last-child {
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        .table tr:hover {
            background: rgba(67, 97, 238, 0.15);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        /* Vehicle Detail Specific Styling */
        .vehicle-number {
            color: var(--accent);
            font-weight: 500;
        }

        .vehicle-type {
            color: var(--vehicle-type);
        }

        .vehicle-make {
            color: var(--warning);
        }

        .vehicle-color {
            padding: 0;
        }

        .color-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.1);
            padding: 4px 12px;
            border-radius: 20px;
        }

        .time-data {
            color: var(--text-secondary);
        }

        .slot-badge {
            background-color: rgba(76, 201, 240, 0.1);
            color: var(--accent);
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .status-active {
            background-color: rgba(75, 181, 67, 0.2);
            color: var(--success);
        }

        .status-completed {
            background-color: rgba(76, 201, 240, 0.2);
            color: var(--accent);
        }

        .amount {
            color: var(--success);
            font-weight: 500;
        }

        .empty-state {
            text-align: center;
            padding: 50px 20px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 10px;
            margin-top: 30px;
            color: var(--text-secondary);
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
            .table td, .table th {
                padding: 10px 8px;
                font-size: 0.9rem;
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
            <li><a href="/Database Project/dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="/Database Project/book_parking.php"><i class="fas fa-parking"></i> Book Parking</a></li>
            <li><a href="/Database Project/parked_vehicles.php"><i class="fas fa-car-side"></i> Parked Vehicles</a></li>
            <li><a href="/Database Project/unpark_vehicle.php"><i class="fas fa-car-alt"></i> Un-Park Vehicle</a></li>
            <li><a href="/Database Project/payment_history.php"><i class="fas fa-dollar-sign"></i> Payment History</a></li>
            <li><a href="/Database Project/parking_history.php" class="active"><i class="fas fa-list"></i> Parking History</a></li>
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
                <a href="/Database Project/dashboard.php">Home</a> > <span>Parking History</span>
            </div>
        </header>

        <div class="content-container">
            <h1 class="page-title">
                <i class="fas fa-list"></i> Parking History
            </h1>
            
            <?php
            if (isset($_SESSION['message'])) {
                $user_id = $_SESSION['message'];
                include('connection.php');
                
                $query = "SELECT 
                    v.Vehicle_Number, 
                    v.Vehicle_Type, 
                    v.Make, 
                    v.Color,
                    b.Slot_ID,
                    b.Entry_Time, 
                    b.Exit_Time,
                    CASE 
                        WHEN b.Exit_Time = '00:00:00' THEN 'Active'
                        ELSE 'Completed'
                    END AS Status,
                    p.Amount
                FROM booking b
                JOIN vehicle v ON b.Vehicle_ID = v.Vehicle_ID
                LEFT JOIN payment p ON b.Booking_ID = p.Booking_ID
                WHERE v.User_ID = ?
                ORDER BY b.Entry_Time DESC";
                
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
                            <th>Vehicle Number</th>
                            <th>Type</th>
                            <th>Make</th>
                            <th>Color</th>
                            <th>Slot</th>
                            <th>Entry Time</th>
                            <th>Exit Time</th>
                            <th>Status</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="vehicle-number"><?php echo htmlspecialchars($row['Vehicle_Number']); ?></td>
                            <td class="vehicle-type"><?php echo htmlspecialchars($row['Vehicle_Type']); ?></td>
                            <td class="vehicle-make"><?php echo htmlspecialchars($row['Make']); ?></td>
                            <td class="vehicle-color">
                                <span class="color-pill" style="color: <?php echo htmlspecialchars($row['Color']); ?>">
                                    <i class="fas fa-circle"></i>
                                    <?php echo htmlspecialchars($row['Color']); ?>
                                </span>
                            </td>
                            <td>
                                <span class="slot-badge"><?php echo htmlspecialchars($row['Slot_ID']); ?></span>
                            </td>
                            <td class="time-data"><?php echo htmlspecialchars($row['Entry_Time']); ?></td>
                            <td class="time-data"><?php echo ($row['Exit_Time'] == '00:00:00') ? '--' : htmlspecialchars($row['Exit_Time']); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo strtolower($row['Status']); ?>">
                                    <?php echo htmlspecialchars($row['Status']); ?>
                                </span>
                            </td>
                            <td class="amount"><?php echo isset($row['Amount']) ? 'Rs. ' . htmlspecialchars($row['Amount']) : '--'; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            
            <?php else: ?>
            
            <div class="empty-state">
                <i class="fas fa-car"></i>
                <h3>No Parking History Found</h3>
                <p>You haven't parked any vehicles yet. Your parking history will appear here after you book parking slots.</p>
            </div>
            
            <?php 
                endif;
            }
            ?>
        </div>
    </div>
</body>
</html>