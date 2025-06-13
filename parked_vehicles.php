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
    <title>Parking Management System - Parked Vehicles</title>
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

        /* Parking Grid */
        .parking-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .parking-slot {
            background: rgba(30, 41, 59, 0.5);
            border-radius: 10px;
            padding: 15px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            position: relative;
        }

        .parking-slot:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .slot-number {
            font-weight: 600;
            color: var(--accent);
            margin-bottom: 5px;
        }

        .slot-status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-vacant {
            background-color: rgba(75, 181, 67, 0.2);
            color: var(--success);
        }

        .status-occupied {
            background-color: rgba(239, 68, 68, 0.2);
            color: var(--danger);
        }

        .vehicle-info {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px dashed rgba(255, 255, 255, 0.1);
        }

        /* Table Styling */
        .table-container {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table thead {
            background: rgba(67, 97, 238, 0.2);
            backdrop-filter: blur(5px);
        }

        .table th {
            padding: 15px;
            text-align: left;
            font-weight: 500;
            color: var(--accent);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .table td {
            padding: 12px 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            vertical-align: middle;
        }

        .table tr:hover {
            background-color: rgba(67, 97, 238, 0.05);
        }

        .btn-unpark {
            background: var(--danger);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 6px;
            transition: all 0.3s ease;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-unpark:hover {
            background: #dc3545;
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(220, 53, 69, 0.3);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 50px 20px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            margin-top: 30px;
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

        .btn-book {
            background: var(--primary);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-book:hover {
            background: var(--secondary);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
            color: white;
        }

        /* Vehicle Status Badges */
        .badge {
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .badge-parked {
            background-color: rgba(239, 68, 68, 0.2);
            color: var(--danger);
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
            .parking-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }
            .table td, .table th {
                padding: 8px 10px;
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
            <li><a href="/Database Project/parked_vehicles.php" class="active"><i class="fas fa-car-side"></i> Parked Vehicles</a></li>
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
                <a href="/Database Project/dashboard.php">Home</a> > <span>Parked Vehicles</span>
            </div>
        </header>

        <div class="content-container">
            <h1 class="page-title">
                <i class="fas fa-car-side"></i> Your Parked Vehicles
            </h1>
            
            <?php
                $user_id = $_SESSION['message'];
                include('connection.php');
                
                // Display Live Parking Status
                echo '<h3 class="mb-3"><i class="fas fa-map-marker-alt"></i> Live Parking Status</h3>';
                echo '<div class="parking-grid">';
                
                $query_slots = "SELECT * FROM parking_slot ORDER BY Slot_ID";
                $result_slots = $conn->query($query_slots);
                
                if($result_slots->num_rows > 0) {
                    while($slot = $result_slots->fetch_assoc()) {
                        $slot_id = htmlspecialchars($slot['Slot_ID']);
                        $status = htmlspecialchars($slot['Slot_Status']);
                        $vehicle_number = htmlspecialchars($slot['Vehicle_Number']);
                        
                        echo '<div class="parking-slot">';
                        echo '<div class="slot-number">Slot ' . $slot_id . '</div>';
                        echo '<span class="slot-status status-' . strtolower($status) . '">' . $status . '</span>';
                        
                        if($status == 'Occupied') {
                            echo '<div class="vehicle-info">';
                            echo '<small>Vehicle: ' . $vehicle_number . '</small>';
                            echo '</div>';
                        }
                        
                        echo '</div>';
                    }
                }
                echo '</div>';
                
                // Check if user has any parked vehicles
                $query = "SELECT v.*, s.Slot_ID 
                          FROM vehicle v
                          LEFT JOIN parking_slot s ON v.Slot_ID = s.Slot_ID
                          WHERE v.User_ID = ? AND v.Status = 'Parked'";
                $stmt = $conn->prepare($query);
                
                if (!$stmt) {
                    die("Error in SQL query: " . $conn->error);
                }
                
                $stmt->bind_param("s", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if($result->num_rows > 0): 
            ?>
            
            <h3 class="mb-3"><i class="fas fa-car"></i> Your Vehicles</h3>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Vehicle Details</th>
                            <th>Type</th>
                            <th>Slot ID</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $count = 1;
                            while($row = $result->fetch_assoc()):
                                $vehicle_color = htmlspecialchars($row['Color']);
                                $vehicle_make = htmlspecialchars($row['Make']);
                                $vehicle_number = htmlspecialchars($row['Vehicle_Number']);
                                $vehicle_type = htmlspecialchars($row['Vehicle_Type']);
                                $slot_id = htmlspecialchars($row['Slot_ID']);
                                $vehicle_id = $row['Vehicle_ID'];
                        ?>
                        <tr>
                            <td><?php echo $count; ?></td>
                            <td>
                                <strong><?php echo $vehicle_make; ?></strong><br>
                                <small style="color: var(--text-secondary)"><?php echo $vehicle_number; ?></small><br>
                                <small style="color: <?php echo $vehicle_color; ?>">
                                    <i class="fas fa-circle" style="font-size: 0.6rem;"></i> <?php echo $vehicle_color; ?>
                                </small>
                            </td>
                            <td><?php echo $vehicle_type; ?></td>
                            <td><?php echo $slot_id ? 'Slot '.$slot_id : 'N/A'; ?></td>
                            <td><span class="badge badge-parked">Parked</span></td>
                            <td>
                                <a href="/Database Project/unpark_vehicle.php?vehicle_id=<?php echo $vehicle_id; ?>" class="btn-unpark">
                                    <i class="fas fa-car-alt"></i> Un-Park
                                </a>
                            </td>
                        </tr>
                        <?php 
                            $count++;
                            endwhile;
                        ?>
                    </tbody>
                </table>
            </div>
            
            <?php else: ?>
            
            <div class="empty-state">
                <i class="fas fa-car"></i>
                <h3>No Parked Vehicles Found</h3>
                <p>You currently have no parked vehicles. Please book a parking slot to park your vehicle.</p>
                <a href="/Database Project/book_parking.php" class="btn-book">
                    <i class="fas fa-parking"></i> Book Parking Now
                </a>
            </div>
            
            <?php endif; ?>
        </div>
    </div>
</body>
</html>