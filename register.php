
<?php
include('connection.php');
$first_name = "";
$last_name = "";
$contact_no = "";
$email = "";
$password = "";
$age = "";
$error_message = "";

if(isset($_POST['register']))
{
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $name = $first_name. " " . $last_name;
    $email = $_POST['email'];
    $password = $_POST['password'];
    $contact_no = $_POST['contact_number'];
    $age = $_POST['age'];
    
    // Basic validation
    if(empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($contact_no) || empty($age)) {
        $error_message = "All fields are required!";
    } else {
        $check = 0;
        $contact_Array = str_split($contact_no);
        
        // Check contact number format (xxxx-xxxxxxx)
        if(isset($contact_Array[4]) && $contact_Array[4] == "-" && strlen($contact_no) == 12) {
            $check = 1;
        }
        
        // Check for duplicate contact number
        $query_check_cont = "SELECT * FROM user WHERE Contact_Number = '$contact_no'";
        $res_check_cont = $conn->query($query_check_cont);
        
        if($res_check_cont->num_rows > 0) {
            $check = 2;
        }
        
        if($check == 1) {
            // Insert into REGISTER table
            $query = "INSERT INTO REGISTER (User_Name, Email, Password) VALUES ('$name', '$email', '$password')";
            $result = mysqli_query($conn, $query);
            
            if($result) {
                // Insert into USER table
                $query1 = "INSERT INTO USER (Name, Email, Password, Contact_Number, Age) 
                          VALUES ('$name', '$email', '$password', '$contact_no', '$age')";
                $result1 = mysqli_query($conn, $query1);
                
                if($result1) {
                    header('location: login.php');
                    exit();
                } else {
                    $error_message = "Error creating user: ".mysqli_error($conn);
                }
            } else {
                $error_message = "Error registering: ".mysqli_error($conn);
            }
        } else if($check == 2) {
            $error_message = "Duplicate Contact Number";
        } else {
            $error_message = "Incorrect Contact Number Format (use xxxx-xxxxxxx)";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Parking Management</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #1a1a2e, #16213e);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }
        
        .container {
            position: relative;
            width: 400px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            z-index: 10;
            animation: fadeIn 0.8s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255,255,255,0.05), rgba(255,255,255,0.15));
            border-radius: 15px;
            pointer-events: none;
            z-index: -1;
        }
        
        h2 {
            color: #fff;
            text-align: center;
            margin-bottom: 30px;
            font-weight: 600;
            letter-spacing: 1px;
        }
        
        .input-group {
            position: relative;
            margin-bottom: 25px;
        }
        
        .input-group input {
            width: 100%;
            padding: 12px 15px;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 16px;
            outline: none;
            transition: all 0.3s ease;
        }
        
        .input-group input:focus {
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 10px rgba(0, 195, 255, 0.3);
        }
        
        .input-group label {
            position: absolute;
            top: 12px;
            left: 15px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 16px;
            pointer-events: none;
            transition: all 0.3s ease;
        }
        
        .input-group input:focus ~ label,
        .input-group input:valid ~ label {
            top: -20px;
            left: 10px;
            font-size: 12px;
            color: #00c3ff;
            background: linear-gradient(135deg, #1a1a2e, #16213e);
            padding: 0 5px;
        }
        
        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(45deg, #00c3ff, #0084ff);
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }
        
        button:hover {
            background: linear-gradient(45deg, #0084ff, #00c3ff);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 131, 255, 0.3);
        }
        
        .login-link {
            text-align: center;
            margin-top: 20px;
            color: rgba(255, 255, 255, 0.7);
        }
        
        .login-link a {
            color: #00c3ff;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .login-link a:hover {
            text-decoration: underline;
            color: #0084ff;
        }
        
        .message {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
            animation: slideIn 0.5s ease-out;
        }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .success {
            background-color: rgba(46, 204, 113, 0.2);
            color: #2ecc71;
            border: 1px solid #2ecc71;
        }
        
        .error {
            background-color: rgba(231, 76, 60, 0.2);
            color: #e74c3c;
            border: 1px solid #e74c3c;
        }
        
        /* Animated background elements */
        .bg-elements {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }
        
        .bg-elements li {
            position: absolute;
            display: block;
            list-style: none;
            width: 20px;
            height: 20px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: animate 25s linear infinite;
            bottom: -150px;
            border-radius: 50%;
        }
        
        .bg-elements li:nth-child(1) {
            left: 25%;
            width: 80px;
            height: 80px;
            animation-delay: 0s;
        }
        
        .bg-elements li:nth-child(2) {
            left: 10%;
            width: 20px;
            height: 20px;
            animation-delay: 2s;
            animation-duration: 12s;
        }
        
        .bg-elements li:nth-child(3) {
            left: 70%;
            width: 20px;
            height: 20px;
            animation-delay: 4s;
        }
        
        .bg-elements li:nth-child(4) {
            left: 40%;
            width: 60px;
            height: 60px;
            animation-delay: 0s;
            animation-duration: 18s;
        }
        
        .bg-elements li:nth-child(5) {
            left: 65%;
            width: 20px;
            height: 20px;
            animation-delay: 0s;
        }
        
        .bg-elements li:nth-child(6) {
            left: 75%;
            width: 110px;
            height: 110px;
            animation-delay: 3s;
        }
        
        .bg-elements li:nth-child(7) {
            left: 35%;
            width: 150px;
            height: 150px;
            animation-delay: 7s;
        }
        
        .bg-elements li:nth-child(8) {
            left: 50%;
            width: 25px;
            height: 25px;
            animation-delay: 15s;
            animation-duration: 45s;
        }
        
        .bg-elements li:nth-child(9) {
            left: 20%;
            width: 15px;
            height: 15px;
            animation-delay: 2s;
            animation-duration: 35s;
        }
        
        .bg-elements li:nth-child(10) {
            left: 85%;
            width: 150px;
            height: 150px;
            animation-delay: 0s;
            animation-duration: 11s;
        }
        
        @keyframes animate {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 1;
                border-radius: 0;
            }
            100% {
                transform: translateY(-1000px) rotate(720deg);
                opacity: 0;
                border-radius: 50%;
            }
        }
    </style>
</head>
<body>
    <!-- Animated background elements -->
    <ul class="bg-elements">
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
    </ul>
    
    <div class="container">
        <h2>Register</h2>
       
                
        <?php if(!empty($error_message)): ?>
            <div class="message error">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="input-group">
                <input type="text" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" required>
                <label>First Name</label>
            </div>
            
            <div class="input-group">
                <input type="text" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" required>
                <label>Last Name</label>
            </div>
            
            <div class="input-group">
                <input type="number" name="age" value="<?php echo htmlspecialchars($age); ?>" required min="1">
                <label>Age</label>
            </div>
            
            <div class="input-group">
                <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                <label>Email</label>
            </div>
            
            <div class="input-group">
                <input type="password" name="password" required>
                <label>Password</label>
            </div>
            
            <div class="input-group">
                <input type="text" name="contact_number" value="<?php echo htmlspecialchars($contact_no); ?>" required placeholder="Format: xxxx-xxxxxxx">
                <label>Contact Number</label>
            </div>
            
            <button type="submit" name="register">Register</button>
        </form>
        
        <div class="login-link">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>
</body>
</html>