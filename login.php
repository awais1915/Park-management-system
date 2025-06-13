<?php
    include('connection.php');
    $user_email ="";
    $user_password ="";
    $user_id = "";
    

    if(isset($_POST['login']))
	{
        $user_email = $_POST['email'];
        $user_password = $_POST['password'];
		
        $query = "SELECT * FROM USER"; 
        $result = $conn->query($query);
        $user_id = 0;
        if ($result->num_rows > 0) 
        {
            // Output data of each row
            while ($row = $result->fetch_assoc())
            {
                if($user_email == $row['Email'])
                {
                    $user_id = $row['User_ID'];
                    break;
                }            
            }
        }
        // echo $user_id;
        
        $query = "SELECT * FROM REGISTER"; 
        $result = $conn->query($query);
        $check = 1;
        if ($result->num_rows > 0) 
        {
            // Output data of each row
            while ($row = $result->fetch_assoc())
            {
                // echo $row['Email']." ".$row['Password'];
                if(($user_email == $row['Email']) && ($user_password == $row['Password']))
                {
                    // echo $user_id;
                    session_start();
                    $check = 0;
                    $_SESSION['message'] = $user_id;
                    header('location: dashboard.php');
                    exit();
                }            
            }
            if($check)
            {
                echo('<div class="alert alert-danger" role="alert"> Incorrect password or user name.  Please Register First to Login to the System!.</div>');
            }
        } else {
            echo('<div class="alert alert-danger" role="alert">Incorrect password or user name.  Please Register First to Login to the System!.</div>');
        }
        // Close the connection
        $conn->close();

    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parking Mangment System</title>
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
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, var(--dark), #16213e);
            height: 100vh;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--light);
        }

        .login-container {
            position: relative;
            width: 100%;
            max-width: 420px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            z-index: 10;
            animation: fadeInUp 0.8s ease-out;
        }

        .login-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .logo {
            text-align: center;
            margin-bottom: 1.5rem;
            animation: bounceIn 1s;
        }

        .logo i {
            font-size: 4rem;
            background: linear-gradient(to right, var(--primary), var(--accent));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 5px 15px rgba(67, 97, 238, 0.4));
        }

        h1 {
            text-align: center;
            margin-bottom: 1.5rem;
            font-weight: 600;
            font-size: 2rem;
            background: linear-gradient(to right, var(--light), var(--accent));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .form-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .form-group input {
            width: 100%;
            padding: 15px 20px 15px 45px;
            border: none;
            border-radius: 50px;
            background: rgba(255, 255, 255, 0.1);
            color: var(--light);
            font-size: 1rem;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .form-group input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.15);
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(76, 201, 240, 0.2);
        }

        .form-group input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .form-group i {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--accent);
            font-size: 1.2rem;
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 50px;
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            color: white;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.4);
            margin-top: 10px;
            overflow: hidden;
            position: relative;
            z-index: 1;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(67, 97, 238, 0.6);
        }

        .btn-login:active {
            transform: translateY(1px);
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, var(--secondary), var(--primary));
            z-index: -1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .btn-login:hover::before {
            opacity: 1;
        }

        .links {
            display: flex;
            justify-content: space-between;
            margin-top: 1.5rem;
            font-size: 0.9rem;
        }

        .links a {
            color: var(--accent);
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
        }

        .links a:hover {
            color: white;
        }

        .links a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 1px;
            background: var(--accent);
            transition: width 0.3s ease;
        }

        .links a:hover::after {
            width: 100%;
        }

        /* Parking Animation */
        .parking-animation {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 1;
            overflow: hidden;
        }

        .car {
            position: absolute;
            width: 60px;
            height: 30px;
            background: var(--accent);
            border-radius: 10px;
            animation: driveIn 8s linear infinite;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
        }

        .car::before {
            content: '';
            position: absolute;
            width: 10px;
            height: 10px;
            background: white;
            border-radius: 50%;
            top: -5px;
            left: 10px;
            box-shadow: 30px 0 0 white;
        }

        .car i {
            font-size: 1rem;
        }

        .parking-spot {
            position: absolute;
            width: 70px;
            height: 40px;
            border: 2px dashed rgba(255, 255, 255, 0.3);
            border-radius: 5px;
        }

        .parking-sign {
            position: absolute;
            width: 40px;
            height: 60px;
            background: var(--danger);
            border-radius: 5px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-weight: bold;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
            animation: pulse 2s infinite;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes bounceIn {
            0%, 20%, 40%, 60%, 80%, 100% {
                animation-timing-function: cubic-bezier(0.215, 0.610, 0.355, 1.000);
            }
            0% {
                opacity: 0;
                transform: scale3d(.3, .3, .3);
            }
            20% {
                transform: scale3d(1.1, 1.1, 1.1);
            }
            40% {
                transform: scale3d(.9, .9, .9);
            }
            60% {
                opacity: 1;
                transform: scale3d(1.03, 1.03, 1.03);
            }
            80% {
                transform: scale3d(.97, .97, .97);
            }
            100% {
                opacity: 1;
                transform: scale3d(1, 1, 1);
            }
        }

        @keyframes driveIn {
            0% {
                transform: translateX(-100px) translateY(0);
            }
            20% {
                transform: translateX(20%) translateY(0);
            }
            40% {
                transform: translateX(40%) translateY(50px);
            }
            60% {
                transform: translateX(60%) translateY(100px);
            }
            80% {
                transform: translateX(80%) translateY(50px);
            }
            100% {
                transform: translateX(calc(100vw + 100px)) translateY(0);
            }
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        .floating {
            animation: float 3s ease-in-out infinite;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .login-container {
                padding: 1.5rem;
                margin: 0 15px;
            }
            
            h1 {
                font-size: 1.8rem;
            }
            
            .links {
                flex-direction: column;
                gap: 10px;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <!-- Parking Animation Background -->
    <div class="parking-animation">
        <!-- Parking spots -->
        <div class="parking-spot" style="top: 20%; left: 10%;"></div>
        <div class="parking-spot" style="top: 30%; left: 30%;"></div>
        <div class="parking-spot" style="top: 20%; left: 50%;"></div>
        <div class="parking-spot" style="top: 30%; left: 70%;"></div>
        <div class="parking-spot" style="top: 70%; left: 10%;"></div>
        <div class="parking-spot" style="top: 60%; left: 30%;"></div>
        <div class="parking-spot" style="top: 70%; left: 50%;"></div>
        <div class="parking-spot" style="top: 60%; left: 70%;"></div>
        
        <!-- Parking sign -->
        <div class="parking-sign" style="top: 10%; right: 10%;">P</div>
        
        <!-- Moving cars -->
        <div class="car" style="top: 25%; animation-delay: 0s;">
            <i class="fas fa-car"></i>
        </div>
        <div class="car" style="top: 65%; background: #f59e0b; animation-delay: 2s;">
            <i class="fas fa-car-side"></i>
        </div>
        <div class="car" style="top: 45%; background: #10b981; width: 40px; height: 20px; animation-delay: 4s; animation-duration: 6s;">
            <i class="fas fa-motorcycle"></i>
        </div>
    </div>

    <!-- Login Form -->
    <div class="login-container">
        <div class="logo floating">
            <i class="fas fa-parking"></i>
        </div>
        <h1>Parking Managment System </h1>
        <p style="text-align: center; margin-bottom: 1.5rem; color: rgba(255,255,255,0.7);">Safe Parking</p>
        
        <form action="login.php" method="POST">
            <div class="form-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="form-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" name="login" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>

        <div class="links">
            <a href="register.php"><i class="fas fa-user-plus"></i>Create Account</a>
            <a href="admin_login.php"><i class="fas fa-user-shield"></i> Admin Login</a>
        </div>
    </div>

    <script>
        // Add more dynamic cars
        function addRandomCar() {
            const colors = ['#4361ee', '#f59e0b', '#10b981', '#ef4444', '#4cc9f0'];
            const types = ['car', 'car-side', 'truck', 'motorcycle', 'shuttle-van'];
            const parkingAnimation = document.querySelector('.parking-animation');
            
            const car = document.createElement('div');
            car.className = 'car';
            car.style.top = Math.random() * 80 + 10 + '%';
            car.style.left = '-100px';
            car.style.background = colors[Math.floor(Math.random() * colors.length)];
            car.style.animationDelay = Math.random() * 5 + 's';
            car.style.animationDuration = Math.random() * 4 + 6 + 's';
            
            const icon = document.createElement('i');
            icon.className = 'fas fa-' + types[Math.floor(Math.random() * types.length)];
            car.appendChild(icon);
            
            parkingAnimation.appendChild(car);
        }

        // Add 3 random cars initially
        for (let i = 0; i < 3; i++) {
            setTimeout(addRandomCar, i * 2000);
        }

        // Add a new random car every 5-10 seconds
        setInterval(() => {
            if (Math.random() > 0.3) { // 70% chance to add a new car
                addRandomCar();
            }
        }, 5000);

        // Make the parking sign pulse
        const sign = document.querySelector('.parking-sign');
        setInterval(() => {
            sign.style.transform = 'scale(1.1)';
            setTimeout(() => {
                sign.style.transform = 'scale(1)';
            }, 500);
        }, 2000);

        // Floating effect for login container
        const loginContainer = document.querySelector('.login-container');
        setInterval(() => {
            loginContainer.style.transform = 'translateY(-5px)';
            setTimeout(() => {
                loginContainer.style.transform = 'translateY(0)';
            }, 1500);
        }, 3000);
    </script>
</body>
</html>

