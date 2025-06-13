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
    <title>Parking Management System - Payment</title>
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

        /* Receipt Container */
        .receipt-container {
            max-width: 500px;
            margin: 0 auto;
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            padding: 30px;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 25px;
            position: relative;
        }

        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 15px;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            box-shadow: 0 4px 10px rgba(67, 97, 238, 0.3);
        }

        .receipt-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--accent);
            margin-bottom: 5px;
        }

        .receipt-subtitle {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: 20px;
        }

        .receipt-divider {
            height: 2px;
            background: linear-gradient(to right, transparent, var(--primary), transparent);
            margin: 20px 0;
            border: none;
        }

        .receipt-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px dashed rgba(255, 255, 255, 0.1);
        }

        .receipt-label {
            font-weight: 500;
            color: var(--text-secondary);
        }

        .receipt-value {
            font-weight: 600;
        }

        .receipt-total {
            display: flex;
            justify-content: space-between;
            margin: 25px 0;
            padding: 15px;
            background: rgba(67, 97, 238, 0.1);
            border-radius: 8px;
            font-size: 1.1rem;
        }

        .receipt-total-label {
            font-weight: 700;
            color: var(--accent);
        }

        .receipt-total-value {
            font-weight: 700;
            color: var(--accent);
        }

        .payment-form {
            margin-top: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-secondary);
        }

        .form-input {
            width: 100%;
            padding: 12px 15px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            color: var(--text-primary);
        }

        .form-input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(76, 201, 240, 0.1);
            outline: none;
            background: rgba(255, 255, 255, 0.1);
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-submit:hover {
            background: var(--secondary);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }

        .return-amount {
            margin-top: 15px;
            padding: 12px;
            background: rgba(75, 181, 67, 0.1);
            border-radius: 8px;
            text-align: center;
            font-weight: 600;
            color: var(--success);
        }

        .paid-stamp {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-15deg);
            font-size: 3rem;
            font-weight: 700;
            color: rgba(75, 181, 67, 0.2);
            border: 5px solid rgba(75, 181, 67, 0.3);
            padding: 20px 40px;
            border-radius: 10px;
            display: none;
            z-index: 10;
            text-align: center;
        }

        .paid-stamp::after {
            content: 'Awais Awan';
            display: block;
            font-size: 1rem;
            font-weight: 400;
            margin-top: 5px;
            color: rgba(75, 181, 67, 0.5);
        }

        /* Alerts */
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            animation: fadeIn 0.5s ease-out;
            display: flex;
            align-items: center;
            background: var(--card-bg);
            backdrop-filter: blur(5px);
            border: 1px solid;
        }

        .alert-success {
            color: var(--success);
            border-color: var(--success);
        }

        .alert-danger {
            color: var(--danger);
            border-color: var(--danger);
        }

        .alert-warning {
            color: var(--warning);
            border-color: var(--warning);
        }

        .alert i {
            margin-right: 10px;
            font-size: 1.2rem;
        }

        .alert a {
            margin-left: 15px;
            color: var(--accent);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .alert a:hover {
            text-decoration: underline;
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
            .receipt-container {
                padding: 20px;
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
            <li><a href="/Database Project/payment_history.php"><i class="fas fa-dollar-sign"></i> Payment History</a></li>
            <li><a href="/Database Project/parking_history.php"><i class="fas fa-list"></i> Parking History</a></li>
            <li><a href="/Database Project/login.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <div class="header-title">
                <div class="user-circle">U</div>
                <h2>Parking Management System</h2>
            </div>
            <div class="breadcrumbs">
                <a href="/Database Project/dashboard.php">Home</a> > <span>Payment</span>
            </div>
        </header>

        <?php
        if ((isset($_SESSION['slot_id'])) && (isset($_SESSION['entry_time'])) && (isset($_SESSION['veh_id'])) && 
            (isset($_SESSION['veh_type'])) && (isset($_SESSION['veh_make'])) && (isset($_SESSION['veh_num'])) && 
            (isset($_SESSION['user_id'])) && (isset($_SESSION['booking_id']))) {
            
            $booking_id = $_SESSION['booking_id'];
            $user_id = $_SESSION['user_id'];
            $slot_id = $_SESSION['slot_id'];
            $entry_time = $_SESSION['entry_time'];
            $veh_id = $_SESSION['veh_id'];
            $veh_type = $_SESSION['veh_type'];
            $veh_make = $_SESSION['veh_make'];
            $veh_no = $_SESSION['veh_num'];
            
            date_default_timezone_set('Asia/Karachi');
            $exit_time = date('H:i:s');

            $entry = new DateTime($entry_time);
            $exit = new DateTime($exit_time);
            $interval = $entry->diff($exit);

            // Convert the difference to total hours
            $hours = $interval->h + ($interval->days * 24);
            $minutes = $interval->i / 60;
            $total_hours = $hours + $minutes;

            // Checking Parking Fee
            include('connection.php');
            $veh_fee = 0;
            $query_check_Fee = "SELECT * FROM parking_fee";
            $result_check_fee = $conn->query($query_check_Fee);
            if($result_check_fee->num_rows > 0) {
                while($row = $result_check_fee->fetch_assoc()) {
                    if($veh_type == $row['Vehicle_Type']) {
                        $veh_fee = $row['Fee'];
                        break;
                    }
                }
            }
            
            // Calculating Total Amount
            $total_amount = ceil($veh_fee * $total_hours);
            $entered_amount = isset($_POST['entered_amount']) ? (float)$_POST['entered_amount'] : 0;
            $return_amount = $entered_amount - $total_amount;

            // Process payment if amount is entered
            if($entered_amount != 0) {
                if(($entered_amount - $total_amount) >= 0) {
                    // Update Slot Status
                    $query_Parking_slot = "UPDATE parking_slot SET Slot_Status = 'Vacant', Vehicle_Number = NULL WHERE Slot_ID = '$slot_id'";
                    $res_Parking_slot = $conn->query($query_Parking_slot);
                    
                    // Update Exit Time
                    $query_Booking = "UPDATE Booking SET Exit_Time = '$exit_time', Status = 'Paid' WHERE Booking_ID = '$booking_id'";
                    $result_Booking_Update = $conn->query($query_Booking);
                    
                    // Update Vehicle Status
                    $query_update_vehicle = "UPDATE vehicle SET Status = 'Unparked' WHERE Vehicle_ID = '$veh_id'";
                    $result_Update_Vehicle = $conn->query($query_update_vehicle);
                    
                    // Insert Payment Record
                    $date = date("Y-m-d");
                    $query_payment = "INSERT INTO payment(Booking_ID, User_ID, Amount, Payment_Date, Vehicle_ID) 
                                     VALUES('$booking_id', '$user_id', '$total_amount', '$date', '$veh_id')";
                    $result_payment = $conn->query($query_payment);
                    
                    echo '<div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> Transaction Successful! 
                            <a href="/Database Project/dashboard.php">Go to Dashboard</a>
                          </div>';
                    
                    // Show paid stamp
                    echo '<style>.paid-stamp { display: block; }</style>';
                } else {
                    echo '<div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i> Invalid Amount! Please enter sufficient amount.
                          </div>';
                }
            }
        ?>
        
        <div class="receipt-container">
            <div class="paid-stamp">PAID</div>
            
            <div class="receipt-header">
                <div class="logo">
                    <i class="fas fa-parking"></i>
                </div>
                <h2 class="receipt-title">Parking Receipt</h2>
                <p class="receipt-subtitle">Thank you for using our parking service</p>
            </div>
            
            <hr class="receipt-divider">
            
            <div class="receipt-item">
                <span class="receipt-label">Vehicle Type:</span>
                <span class="receipt-value"><?php echo htmlspecialchars($veh_type); ?></span>
            </div>
            
            <div class="receipt-item">
                <span class="receipt-label">Vehicle Make:</span>
                <span class="receipt-value"><?php echo htmlspecialchars($veh_make); ?></span>
            </div>
            
            <div class="receipt-item">
                <span class="receipt-label">Vehicle Number:</span>
                <span class="receipt-value"><?php echo htmlspecialchars($veh_no); ?></span>
            </div>
            
            <div class="receipt-item">
                <span class="receipt-label">Entry Time:</span>
                <span class="receipt-value"><?php echo htmlspecialchars($entry_time); ?></span>
            </div>
            
            <div class="receipt-item">
                <span class="receipt-label">Exit Time:</span>
                <span class="receipt-value"><?php echo htmlspecialchars($exit_time); ?></span>
            </div>
            
            <div class="receipt-item">
                <span class="receipt-label">Hours Parked:</span>
                <span class="receipt-value"><?php echo number_format($total_hours, 2); ?> hours</span>
            </div>
            
            <div class="receipt-total">
                <span class="receipt-total-label">Total Amount:</span>
                <span class="receipt-total-value">Rs. <?php echo htmlspecialchars($total_amount); ?></span>
            </div>
            
            <form method="POST" action="payment.php" class="payment-form">
                <div class="form-group">
                    <label for="entered_amount" class="form-label">Enter Amount (Rs.)</label>
                    <input type="number" name="entered_amount" id="entered_amount" class="form-input" 
                           value="<?php echo htmlspecialchars($entered_amount); ?>" required min="0" step="1">
                </div>
                
                <?php if($entered_amount >= $total_amount && $entered_amount != 0): ?>
                <div class="return-amount">
                    <i class="fas fa-coins"></i> Return Amount: Rs. <?php echo $return_amount; ?>
                </div>
                <?php endif; ?>
                
                <button type="submit" class="btn-submit">
                    <i class="fas fa-credit-card"></i> Process Payment
                </button>
            </form>
        </div>
        <?php
        } else {
            echo '<div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> No parking session found. Please unpark a vehicle first.
                  </div>';
        }
        ?>
    </div>
</body>
</html>