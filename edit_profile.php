<?php
    session_start();
    include('connection.php');

    if (isset($_SESSION['message'])) {
        $user_id = $_SESSION['message'];
        $query = "SELECT * FROM user WHERE User_ID = '$user_id'";
        $result = $conn->query($query);
        $user_data = $result->fetch_assoc();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $age = $_POST['age'];
        $contact_no = $_POST['contact_no'];

        $contact_Array = str_split($contact_no);
        $age = $_POST['age'];
        $check = 0;
        if(($contact_Array[4] == "-") && (strlen($contact_no) == 12))
        {
            $check = 1;
        }

        if($check)
        {
            $update_register = "UPDATE register SET Email='$email' WHERE Reg_ID='$user_id'";
            if($conn->query($update_register))
            {
                $update_query = "UPDATE user SET Name='$name', Email='$email', Age='$age', Contact_Number='$contact_no' WHERE User_ID='$user_id'";
                
                if ($conn->query($update_query)) {
                    $_SESSION['success'] = "Profile updated successfully!";
                    header("Location: dashboard.php");
                } else {
                    $_SESSION['error'] = "Failed to update profile. Please try again.";
                }
            }
        }
        else
        {
            echo('<div style="width: 40%; margin-left: 500px; z-index = 1; position: absolute;" class="alert alert-danger" role="alert">Incorrect Contact Number</div>');
        }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css"
        integrity="sha384-dpuaG1suU0eT09tx5plTaGMLBsfDLzUCCUXOY2j/LSvXYuG6Bqs43ALlhIqAJVRb" crossorigin="anonymous">
    <title>Parking Management System - User Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Itim&family=M+PLUS+Rounded+1c&display=swap" rel="stylesheet">
    <!-- <link rel="stylesheet" href="styles.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Itim", serif;
            font-weight: 400;
            font-style: normal;
        }
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
            padding: 0;
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
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        header h2 {
            color: #2d3e50;
        }
        .form-container {
            background: #f9f9f9;
            border-radius: 8px;
            padding: 30px;
            max-width: 450px;
            margin: auto;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            color: #2d3e50;
        }
        .form-container h3 {
            margin-bottom: 20px;
            color: #2d3e50;
        }
        .form-container label {
            margin-bottom: 10px;
            display: block;
            font-weight: bold;
        }
        .form-container input {
            color: #2d3e50;
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .form-container button {
            background-color: #2d3e50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .form-container button:hover {
            background-color: #1a2433;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>User</h2>
        </div>
        <ul class="nav-links">
            <li><a href="dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="book_parking.php"><i class="fas fa-parking"></i> Book Parking</a></li>
            <li><a href="parked_vehicles.php"><i class="fas fa-car-side"></i> Parked Vehicles</a></li>
            <li><a href="unpark_vehicle.php"><i class="fas fa-car-alt"></i> Un-Park Vehicle</a></li>
            <li><a href="payment_history.php"><i class="fas fa-dollar-sign"></i> Payment History</a></li>
            <li><a href="parking_history.php"><i class="fas fa-list"></i> Parking History</a></li>
            <li><a href="login.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <header>
            <h2>Edit Profile</h2>
        </header>

        <div class="form-container">
            <h3>Update Your Information</h3>
            <form method="POST" action="edit_profile.php">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user_data['Name']); ?>" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_data['Email']); ?>" required>

                <label for="age">Age</label>
                <input type="number" id="age" name="age" value="<?php echo htmlspecialchars($user_data['Age']); ?>" required>

                <label for="contact_no">Contact Number</label>
                <input type="text" id="contact_no" name="contact_no" value="<?php echo htmlspecialchars($user_data['Contact_Number']); ?>" required>

                <button type="submit">Update Profile</button>
            </form>
        </div>
    </div>
</body>

</html>
