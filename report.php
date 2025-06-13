<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parking Management System - User Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css"
        integrity="sha384-dpuaG1suU0eT09tx5plTaGMLBsfDLzUCCUXOY2j/LSvXYuG6Bqs43ALlhIqAJVRb" crossorigin="anonymous">

    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #2d3e50;
            color: #fff;
            position: fixed;
        }

        .sidebar-header {
            text-align: center;
            padding: 20px 0;
            font-size: 1.5rem;
            background-color: #1a2433;
        }

        .nav-links {
            list-style: none;
        }

        .nav-links li {
            border-bottom: 1px solid #1a2433;
        }

        .nav-links a {
            display: flex;
            align-items: center;
            text-decoration: none;
            padding: 15px 20px;
            color: #ddd;
            transition: all 0.3s ease;
        }

        .nav-links a:hover,
        .nav-links a.active {
            background-color: #1a2433;
            color: #fff;
        }

        .nav-links i {
            margin-right: 10px;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        header h2 {
            color: #2d3e50;
        }

        .breadcrumbs {
            font-size: 0.9rem;
            color: #666;
        }

        .breadcrumbs a {
            color: #2d3e50;
            text-decoration: none;
        }

        .dashboard {
            display: flex;
             gap: 20px;
        }
        .card {
            margin-bottom: 10px;
            flex: 1;
            padding: 20px;
            border-radius: 8px;
            color: #fff;
            /* text-align: center; */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card1{
            height: 280px;
        }
        .card h2{
            margin: 30px 0 0 20px;
        }
        .card h3{
            margin: 20px;
        }

        .card.blue {
            background-color: #007bff;
        }

        .card.orange {
            background-color: #fd7e14;
        }

        .card.green {
            background-color: #28a745;
        }

        .card a {
            text-decoration: none;
            color: #fff;
            display: inline-block;
            margin-top: 10px;
        }

        footer {
            text-align: center;
            margin-top: 30px;
            font-size: 0.8rem;
            color: #aaa;
        }
        .container{
            /* margin: 30px; */
            width: 55%;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>User</h2>
        </div>
        <ul class="nav-links">
            <li><a href="/Database Project/dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="/Database Project/book_parking.php"><i class="fas fa-parking"></i> Book Parking</a></li>
            <li><a href="/Database Project/parked_vehicles.php"><i class="fas fa-car-side"></i> Parked Vehicles</a></li>
            <li><a href="/Database Project/unpark_vehicle.php"><i class="fas fa-car-alt"></i>Un-Park Car</a></li>
            <li><a href="/Database Project/payment_history.php"><i class="fas fa-dollar-sign"></i> Payment History</a></li>
            <li><a href="/Database Project/parking_history.php"><i class="fas fa-list"></i> Parking History</a></li>
            <li><a href="/Database Project/report.php"><i class="fas fa-chart-bar"></i> Reports</a></li>
            <li><a href="/Database Project/login.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content" >
        <header>
            <h2>Parking Management System</h2>
            <div class="breadcrumbs">
                <a href="#">Home</a> > <span>Un-Park Vehicle</span>
            </div>
        </header>
    </div>
        <div class="container">
            <div style="text-align: center;"><h2>Parking Report</h2></div>
                <table class="table">
                    <thead">
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Vehicle Type</th>
                        <th scope="col">Vehicle Make</th>
                        <th scope="col">Vehicle Number</th>
                        <th scope="col">Parking Fee</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        <?php
                        if (isset($_SESSION['message'])) 
                        {
                            $user_id = $_SESSION['message'];
                            include('connection.php');
                            $count = 1;
                            $query = "select* from vehicle";
                            $result = $conn->query($query);
                            if($result->num_rows>0)
                            {
                                while($row = $result->fetch_assoc())
                                {
                                    if($user_id == $row['User_ID'])
                                    {
                                        echo '<tr>
                                        <td>' . $count. '</td>
                                        <td>' . htmlspecialchars($row['Vehicle_Type']) . '</td>
                                        <td>' . htmlspecialchars($row['Make']) . '</td>
                                        <td>' . htmlspecialchars($row['Vehicle_Number']) . '</td>
                                        <td>' . htmlspecialchars($row['Slot_ID']) . '</td>
                                        </tr>';
                                        $count = $count+1;
                                    }
                            }
                        }
                    }
                        ?>
                    </tbody>
                </table>
        </div>
</body>

</html>