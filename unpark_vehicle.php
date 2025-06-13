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
    <title>Parking Management System - Un-Park Vehicle</title>
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
            max-width: 800px;
            margin: 0 auto;
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

        /* Form Styling */
        .unpark-form {
            margin-top: 20px;
        }

        .form-label {
            color: var(--text-primary);
            font-weight: 500;
            margin-bottom: 8px;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
            padding: 12px 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--accent);
            box-shadow: 0 0 0 0.2rem rgba(76, 201, 240, 0.25);
            color: var(--text-primary);
        }

        .btn-unpark {
            background: var(--danger);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-unpark:hover {
            background: #dc3545;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        }

        .btn-check-bill {
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-check-bill:hover {
            background: var(--secondary);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
            color: white;
        }

        /* Alerts */
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            animation: fadeIn 0.5s ease-out;
        }

        .alert-warning {
            background: rgba(245, 158, 11, 0.2);
            color: var(--warning);
            border: 1px solid var(--warning);
        }

        /* Animations */
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
            .content-container {
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
            <li><a href="/Database Project/dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="/Database Project/book_parking.php"><i class="fas fa-parking"></i> Book Parking</a></li>
            <li><a href="/Database Project/parked_vehicles.php"><i class="fas fa-car-side"></i> Parked Vehicles</a></li>
            <li><a href="/Database Project/unpark_vehicle.php" class="active"><i class="fas fa-car-alt"></i> Un-Park Vehicle</a></li>
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
                <a href="/Database Project/dashboard.php">Home</a> > <span>Un-Park Vehicle</span>
            </div>
        </header>

        <div class="content-container">
            <h1 class="page-title">
                <i class="fas fa-car-alt"></i> Un-Park Your Vehicle
            </h1>
            
            <form class="unpark-form row g-3" action="unpark_vehicle.php" method="post">
                <div class="col-md-6">
                    <label for="vehicle_no" class="form-label">Vehicle Number</label>
                    <input type="text" class="form-control" id="vehicle_no" name="vehicle_no" placeholder="ABC-123" required>
                </div>
                <div class="col-md-6">
                    <label for="slot_no" class="form-label">Slot Number</label>
                    <input type="text" class="form-control" id="slot_no" name="slot_no" placeholder="e.g., 101" required>
                </div>
                <div class="col-12">
                    <button type="submit" name="unpark" class="btn-unpark">
                        <i class="fas fa-car-alt"></i> Un-Park Vehicle
                    </button>
                </div>
            </form>

            <?php
            if (isset($_SESSION['message'])) {
                $user_id = $_SESSION['message'];
                include('connection.php');
                
                if(isset($_POST['unpark'])) {
                    $veh_no = $_POST['vehicle_no'];
                    $slot_id = $_POST['slot_no'];
                    
                    // Format vehicle number
                    $veh_num_array = str_split($veh_no);
                    if((strlen($veh_no) == 9) || (strlen($veh_no) == 10)) {
                        $index = 0;
                        $array_No = str_split($veh_no);
                        $new_veh_no = "";
                        while($index < strlen($veh_no)) {
                            if(($array_No[$index] == '-') || ($array_No[$index] == ' ')) {
                                // Skip dashes and spaces
                            } else {
                                $new_veh_no = $new_veh_no.$array_No[$index];
                            }
                            $index++;
                        }
                        $veh_no = $new_veh_no;
                    }
                    
                    if((strlen($veh_no) == 7) || (strlen($veh_no) == 6)) {
                        if(strlen($veh_no) == 7) {
                            $substring_Char = substr($veh_no, 0, 3);
                            $substring_Char = strtoupper($substring_Char);
                            $substring_Num = substr($veh_no, 3, 4);
                        } else {
                            $substring_Char = substr($veh_no, 0, 3);
                            $substring_Char = strtoupper($substring_Char);
                            $substring_Num = substr($veh_no, 3, 3);
                        }
                        $veh_no = $substring_Char." - ".$substring_Num;
                    }
                    
                    $query = "SELECT * FROM Vehicle";
                    $result = $conn->query($query);
                    $check = 1;
                    
                    if($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            if(($row['Status'] == 'Parked') && ($user_id == $row['User_ID']) && 
                               ($row['Vehicle_Number'] == $veh_no) && ($slot_id == $row['Slot_ID'])) {
                                $check = 0;
                                $slot_id = $row['Slot_ID'];
                                $veh_id = $row['Vehicle_ID'];
                                $veh_type = $row['Vehicle_Type'];
                                $veh_make = $row['Make'];
                                
                                // Update parking slot status
                                $query_Parking_slot = "UPDATE Parking_Slot SET Slot_Status = 'Vacant' WHERE Slot_ID = '$slot_id'";
                                $conn->query($query_Parking_slot);
                                
                                // Get booking information
                                $query_Booking = "SELECT * FROM booking";
                                $result_Booking = $conn->query($query_Booking);
                                
                                if($result_Booking->num_rows > 0) {
                                    while($row = $result_Booking->fetch_assoc()) {
                                        if(($row['Exit_Time'] == "00:00:00") && ($row['Vehicle_ID'] == $veh_id)) {
                                            $entry_time = $row['Entry_Time'];
                                            $exit_time = $row['Exit_Time'];
                                            $booking_id = $row['Booking_ID'];
                                            
                                            date_default_timezone_set('Asia/Karachi');
                                            $curr_time = date("H:i:s");
                                            
                                            // Update exit time
                                            $query_exit_time = "UPDATE Booking SET Exit_Time = '$curr_time' WHERE Booking_ID = '$booking_id'";
                                            $conn->query($query_exit_time);
                                            
                                            // Update vehicle status
                                            $query_update_vehicle = "UPDATE vehicle SET Status = 'Unparked' WHERE Vehicle_ID = '$veh_id'";
                                            $conn->query($query_update_vehicle);
                                            
                                            // Store session data for payment
                                            $_SESSION['booking_id'] = $booking_id;
                                            $_SESSION['slot_id'] = $slot_id;
                                            $_SESSION['entry_time'] = $entry_time;
                                            $_SESSION['veh_id'] = $veh_id;
                                            $_SESSION['veh_type'] = $veh_type;
                                            $_SESSION['veh_make'] = $veh_make;
                                            $_SESSION['veh_num'] = $veh_no;
                                            $_SESSION['user_id'] = $user_id;
                                            
                                            echo '<div class="alert alert-success mt-4">
                                                    <i class="fas fa-check-circle"></i> Vehicle successfully unparked!
                                                  </div>
                                                  <div class="text-center mt-3">
                                                    <a href="/Database Project/payment.php" class="btn-check-bill">
                                                        <i class="fas fa-receipt"></i> Check Bill & Make Payment
                                                    </a>
                                                  </div>';
                                        }
                                    }
                                }
                                break;
                            }
                        }
                    }
                    
                    if($check) {
                        echo '<div class="alert alert-warning mt-4">
                                <i class="fas fa-exclamation-triangle"></i> Invalid vehicle or slot number. Please check your details and try again.
                              </div>';
                    }
                }
            }
            ?>
        </div>
    </div>
</body>
</html>