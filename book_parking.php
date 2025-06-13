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
    <title>Parking Management System - Book Parking</title>
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

        /* Pricing Cards */
        .pricing-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .pricing-card {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .pricing-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .pricing-card h4 {
            color: var(--accent);
            text-align: center;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .pricing-card h5 {
            margin: 15px 0;
            color: var(--text-primary);
            font-weight: 400;
        }

        .pricing-card.blue {
            border-top: 4px solid #007bff;
        }

        .pricing-card.orange {
            border-top: 4px solid #fd7e14;
        }

        .pricing-card.green {
            border-top: 4px solid #28a745;
        }

        /* Booking Form */
        .booking-form {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 30px;
        }

        .booking-form h2 {
            color: var(--accent);
            text-align: center;
            margin-bottom: 30px;
            font-weight: 500;
        }

        .form-label {
            color: var(--text-primary);
            font-weight: 400;
            margin-bottom: 8px;
        }

        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
            padding: 12px 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--accent);
            box-shadow: 0 0 0 0.2rem rgba(76, 201, 240, 0.25);
            color: var(--text-primary);
        }

        .btn-book {
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: block;
            margin: 30px auto 0;
            width: 200px;
        }

        .btn-book:hover {
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

        .alert-primary {
            background: rgba(67, 97, 238, 0.2);
            color: var(--primary);
            border: 1px solid var(--primary);
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.2);
            color: var(--danger);
            border: 1px solid var(--danger);
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
            .pricing-cards {
                grid-template-columns: 1fr;
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
            .booking-form {
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
            <li><a href="/Database Project/book_parking.php" class="active"><i class="fas fa-parking"></i> Book Parking</a></li>
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
                <a href="/Database Project/dashboard.php">Home</a> > <span>Book Parking Slot</span>
            </div>
        </header>

        <!-- Pricing Cards -->
        <div class="pricing-cards">
            <div class="pricing-card blue">
                <h4>Parking Fee</h4>
                <?php
                if (isset($_SESSION['message'])) {
                    $user_id = $_SESSION['message'];
                    include('connection.php');

                    $query = "SELECT * FROM parking_fee";
                    $result = $conn->query($query);
                    if ($result->num_rows > 0) {
                        $i = 0;
                        while ($row = $result->fetch_assoc()) {
                            $i++;
                            if($i == 1) {
                                $vehicle_type = $row['Vehicle_Type'];
                                $fee = $row['Fee'];
                                echo "<h5><i class='fas fa-car'></i> Vehicle Type: " . htmlspecialchars($vehicle_type) . "</h5>";
                                echo "<h5><i class='fas fa-money-bill-wave'></i> Fee: " . htmlspecialchars($fee) . " (Per Hour)</h5>";  
                            }
                        }
                    } else {
                        echo "<h5>Not Available!</h5>";
                    }
                }
                ?>
            </div>

            <div class="pricing-card orange">
                <h4>Parking Fee</h4>
                <?php
                if (isset($_SESSION['message'])) {
                    $user_id = $_SESSION['message'];
                    include('connection.php');

                    $query = "SELECT * FROM parking_fee";
                    $result = $conn->query($query);
                    if ($result->num_rows > 0) {
                        $i = 0;
                        while ($row = $result->fetch_assoc()) {
                            $i++;
                            if($i == 2) {
                                $vehicle_type = $row['Vehicle_Type'];
                                $fee = $row['Fee'];
                                echo "<h5><i class='fas fa-car'></i> Vehicle Type: " . htmlspecialchars($vehicle_type) . "</h5>";
                                echo "<h5><i class='fas fa-money-bill-wave'></i> Fee: " . htmlspecialchars($fee) . " (Per Hour)</h5>";  
                            }
                        }
                    } else {
                        echo "<h5>Not Available!</h5>";
                    }
                }
                ?>
            </div>

            <div class="pricing-card green">
                <h4>Parking Fee</h4>
                <?php
                if (isset($_SESSION['message'])) {
                    $user_id = $_SESSION['message'];
                    include('connection.php');

                    $query = "SELECT * FROM parking_fee";
                    $result = $conn->query($query);
                    if ($result->num_rows > 0) {
                        $i = 0;
                        while ($row = $result->fetch_assoc()) {
                            $i++;
                            if($i == 3) {
                                $vehicle_type = $row['Vehicle_Type'];
                                $fee = $row['Fee'];
                                echo "<h5><i class='fas fa-motorcycle'></i> Vehicle Type: " . htmlspecialchars($vehicle_type) . "</h5>";
                                echo "<h5><i class='fas fa-money-bill-wave'></i> Fee: " . htmlspecialchars($fee) . " (Per Hour)</h5>";  
                            }
                        }
                    } else {
                        echo "<h5>Not Available!</h5>";
                    }
                }
                ?>
            </div>
        </div>

        <!-- Booking Form -->
        <div class="booking-form">
            <h2><i class="fas fa-car"></i> Vehicle Entry</h2>
            <form action="book_parking.php" method="post">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="vehicle_make" class="form-label">Vehicle Make</label>
                        <input type="text" class="form-control" id="vehicle_make" name="vehicle_make" placeholder="Honda/Toyota/Mercedes" required>
                    </div>
                    <div class="col-md-6">
                        <label for="vehicle_color" class="form-label">Vehicle Color</label>
                        <input type="text" class="form-control" id="vehicle_color" name="vehicle_color" required>
                    </div>
                    <div class="col-md-6">
                        <label for="vehicle_type" class="form-label">Vehicle Type</label>
                        <select name="vehicle_type" class="form-select" required>
                            <option selected disabled>Select Vehicle Type</option>
                            <option value="Large">Large</option>
                            <option value="Compact">Compact</option>
                            <option value="Bike">Bike</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="vehicle_no" class="form-label">Vehicle Number</label>
                        <input type="text" class="form-control" id="vehicle_no" name="vehicle_no" required>
                    </div>
                    <div class="col-12">
                        <button type="submit" name="park_car" class="btn-book">
                            <i class="fas fa-parking"></i> Park Vehicle
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <?php
        if(isset($_POST['park_car'])) {
            $user_id = $_SESSION['message'];
            include('connection.php');
            
            $vehicle_make = $_POST['vehicle_make'];
            $vehicle_color = $_POST['vehicle_color'];
            $vehicle_type = $_POST['vehicle_type'];
            $vehicle_no = $_POST['vehicle_no'];
            $status = "Parked";
            $check = 1;
            
            $veh_num_array = str_split($vehicle_no);
            if((strlen($vehicle_no) == 9) || (strlen($vehicle_no) == 10)) {
                $index = 0;
                $array_No = str_split($vehicle_no);
                $new_veh_no = "";
                while($index < strlen($vehicle_no)) {
                    if(($array_No[$index] == '-') || ($array_No[$index] == ' ')) {
                        // Skip dashes and spaces
                    } else {
                        $new_veh_no = $new_veh_no.$array_No[$index];
                    }
                    $index++;
                }
                $vehicle_no = $new_veh_no;
            }
            
            if((strlen($vehicle_no) == 7) || (strlen($vehicle_no) == 6)) {
                if(strlen($vehicle_no) == 7) {
                    $substring_Char = substr($vehicle_no, 0, 3);
                    $substring_Char = strtoupper($substring_Char);
                    $substring_Num = substr($vehicle_no, 3, 4);
                } else {
                    $substring_Char = substr($vehicle_no, 0, 3);
                    $substring_Char = strtoupper($substring_Char);
                    $substring_Num = substr($vehicle_no, 3, 3);
                }
                $vehicle_no = $substring_Char." - ".$substring_Num;
                
                $query_check = "SELECT * FROM vehicle";
                $result = $conn->query($query_check);
                if($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        if(($vehicle_no == $row['Vehicle_Number']) && ($vehicle_make == $row['Make']) && 
                           ($status == $row['Status']) && ($vehicle_type == $row['Vehicle_Type'])) {
                            echo '<div class="alert alert-danger">This vehicle is already parked.</div>';
                            $check = 0;
                            break;
                        }
                    }
                }
                
                $check_slot = 0;
                if($check) {
                    $query_check_par = "SELECT * FROM parking_slot";
                    $result_check_par = $conn->query($query_check_par);
                    if($result_check_par->num_rows > 0) {
                        while($row = $result_check_par->fetch_assoc()) {
                            if($row['Slot_Status'] == "Vacant") {
                                $check_slot = 1;
                                $slot_id = $row['Slot_ID'];
                                break;
                            }
                        }
                    } else {
                        echo '<div class="alert alert-danger">Parking is not available.</div>';
                    }
                    
                    if($check_slot) {
                        $query_par_slot = "UPDATE Parking_Slot SET Slot_Status = 'Occupied', Vehicle_Number = '$vehicle_no' WHERE Slot_ID = '$slot_id'";
                        $result_par_slot = $conn->query($query_par_slot);
                        
                        $query_veh = "INSERT INTO VEHICLE(Make, Color, Vehicle_Number, Vehicle_Type, Status, User_ID, Slot_ID) 
                                     VALUES('$vehicle_make', '$vehicle_color', '$vehicle_no', '$vehicle_type', 'Parked', '$user_id', '$slot_id')"; 
                        $result_veh = $conn->query($query_veh);
                        
                        $veh_id = 0;
                        $query_Veh_ID = "SELECT * FROM vehicle";
                        $result_Veh_ID = $conn->query($query_Veh_ID);
                        if($result_Veh_ID->num_rows > 0) {
                            while($row = $result_Veh_ID->fetch_assoc()) {
                                if(($row['Vehicle_Number'] == $vehicle_no) && ($row['Make'] == $vehicle_make)) {
                                    $veh_id = $row['Vehicle_ID'];
                                }
                            }
                        }
                        
                        // Book Parking Query
                        date_default_timezone_set('Asia/Karachi');
                        $time_parked = date("H:i:s");
                        $query_book_parking = "INSERT INTO booking(User_ID, Slot_ID, Entry_Time, Exit_Time, Vehicle_ID)
                                             VALUES('$user_id', '$slot_id', '$time_parked', '', '$veh_id')";
                        $result_book = $conn->query($query_book_parking);
                        
                        if($result_par_slot && $result_veh) {
                            echo '<div class="alert alert-primary">Your vehicle has been parked successfully! Thank you for using our parking service.</div>';
                        } else {
                            echo '<div class="alert alert-danger">Action failed.</div>';
                        }
                    }
                }
            } else {
                echo '<div class="alert alert-danger">Invalid vehicle number format.</div>';
            }
        }
        ?>
    </div>
</body>
</html>