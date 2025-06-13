<?php
session_start();
if(!isset($_SESSION['admin_logged_in'])) {
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
    <title>Parking Management System - Reports</title>
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

        .sidebar.collapsed {
            transform: translateX(-280px);
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

        .toggle-btn {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--accent);
            color: white;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 101;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .toggle-btn:hover {
            transform: translateY(-50%) scale(1.1);
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

        .main-content.expanded {
            margin-left: 0;
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

        /* Search Form */
        .search-container {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 30px;
        }

        .search-container h3 {
            color: var(--accent);
            margin-bottom: 20px;
            font-weight: 500;
        }

        /* Tables */
        .table-container {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
            overflow-x: auto;
        }

        .table-container h2 {
            color: var(--accent);
            margin-bottom: 20px;
            font-weight: 500;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            color: var(--text-primary);
        }

        th {
            background: rgba(76, 201, 240, 0.1);
            color: var(--accent);
            padding: 15px;
            text-align: left;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
        }

        td {
            padding: 12px 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        tr:hover {
            background: rgba(255, 255, 255, 0.03);
        }

        /* Form Styles */
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

        .btn-primary {
            background: var(--primary);
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 500;
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
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.2);
            color: var(--danger);
            border: 1px solid var(--danger);
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
            th, td {
                padding: 10px 8px;
                font-size: 0.8rem;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>Admin </h2>
            <button class="toggle-btn" id="toggleBtn">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <ul class="nav-links">
            <li><a href="/Database Project/Admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="/Database Project/Admin_Users.php"><i class="fas fa-users"></i> Users</a></li>
            <li><a href="/Database Project/Admin_Manage_Parking.php"><i class="fas fa-cogs"></i> Manage Parking</a></li>
            <li><a href="/Database Project/Admin_Find_User.php"><i class="fas fa-user"></i> Find User</a></li>
            <li><a href="/Database Project/Admin_ParkingFee.php"><i class="fas fa-dollar-sign"></i> Rates</a></li>
            <li><a href="/Database Project/Admin_Find_Reports.php" class="active"><i class="fas fa-chart-bar"></i> Reports</a></li>
            <li><a href="/Database Project/login.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <header>
            <div class="header-title">
                <h2>Parking Management System</h2>
            </div>
            <div class="breadcrumbs">
                <a href="/Database Project/Admin.php">Home</a> > <span>Reports</span>
            </div>
        </header>

        <div class="search-container">
            <h3>Search User Reports</h3>
            <form class="row g-3" action="Admin_Find_Reports.php" method="post">
                <div class="col-md-8">
                    <input type="number" class="form-control" id="user_id" name="user_id" placeholder="Enter User ID" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" name="submit" class="btn btn-primary w-100">Search</button>
                </div>
            </form>
        </div>

        <?php
        if(isset($_POST['submit'])) {
            $user_id = $_POST['user_id'];

            $query_find_parked_cars = "SELECT * FROM booking";
            $res_find_parked_cars = $conn->query($query_find_parked_cars);
            $count_parked = 0;                        
            if ($res_find_parked_cars->num_rows > 0) {
                while ($row = $res_find_parked_cars->fetch_assoc()) {
                    if (($user_id == $row['User_ID']) && ($row['Status'] == 'UnPaid')) {
                        $count_parked = $count_parked + 1;
                    }
                }
            }
            
            $query = "SELECT * FROM user";
            $result = $conn->query($query);
            
            if ($result->num_rows > 0) {
                $user_found = false;
                while ($row = $result->fetch_assoc()) {
                    if ($user_id == $row['User_ID']) {
                        $user_found = true;
                        $user_name = $row['Name'];
                        $email = $row['Email'];
                        $age = $row['Age'];
                        $contact_no = $row['Contact_Number'];
                        ?>
                        <div class="table-container">
                            <h2>User Information</h2>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Age</th>
                                        <th>Email</th>
                                        <th>Contact No</th>
                                        <th>Parked Cars</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?php echo htmlspecialchars($user_name); ?></td>
                                        <td><?php echo htmlspecialchars($age); ?></td>
                                        <td><?php echo htmlspecialchars($email); ?></td>
                                        <td><?php echo htmlspecialchars($contact_no); ?></td>
                                        <td><?php echo $count_parked; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <?php
                    }          
                }
                
                $query1 = "SELECT v.Make AS Vehicle_Make, v.Vehicle_Type AS Vehicle_Type, v.Color AS Vehicle_Color, 
                          v.Slot_ID AS Slot_No, v.Vehicle_Number AS Vehicle_Number, p.Amount AS Payment_Amount, 
                          v.User_ID, v.Slot_ID 
                          FROM vehicle v 
                          INNER JOIN payment p ON v.Vehicle_ID = p.Vehicle_ID 
                          WHERE v.User_ID = '$user_id'";
                
                $result = $conn->query($query1);
                if($result->num_rows > 0) {
                    ?>
                    <div class="table-container">
                        <h2>Vehicle Information</h2>
                        <table>
                            <thead>
                                <tr>
                                    <th>Make</th>
                                    <th>Type</th>
                                    <th>Color</th>
                                    <th>Vehicle Number</th>
                                    <th>Amount Paid</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while($row = $result->fetch_assoc()) {
                                    echo '<tr>
                                        <td>' . htmlspecialchars($row['Vehicle_Make']) . '</td>
                                        <td>' . htmlspecialchars($row['Vehicle_Type']) . '</td>
                                        <td>' . htmlspecialchars($row['Vehicle_Color']) . '</td>
                                        <td>' . htmlspecialchars($row['Vehicle_Number']) . '</td>
                                        <td>' . htmlspecialchars($row['Payment_Amount']) . '</td>
                                    </tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                } else if($user_found) {
                    echo '<div class="alert alert-danger">No transactions found for this user.</div>';
                }
                
                if(!$user_found) {
                    echo '<div class="alert alert-danger">User not found.</div>';
                }
            }
        }
        ?>
    </div>

    <script>
        // Toggle sidebar
        const toggleBtn = document.getElementById('toggleBtn');
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.getElementById('mainContent');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            
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
    </script>
</body>
</html>