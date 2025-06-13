<?php
    include('connection.php');
    $user_email ="";
    $user_password ="";
    $user_id = "";
    

    if(isset($_POST['login']))
	{
        $user_email = $_POST['email'];
        $user_password = $_POST['password'];
		$hashedPassword = password_hash($user_password, PASSWORD_DEFAULT);
        $query = "insert into TEST(email,pass) values('$user_email','$hashedPassword')"; 
        $result = $conn->query($query);
        // echo $user_id;
        if(!$result)
        {
            echo "error";
        }
        $query1 = "select* from Test";
        $res = $conn->query($query1);

        if(!$res)
        {
            echo "error";
        }
        while($row = $res->fetch_assoc())
        {
            if (password_verify($user_password, $row['pass'])) {
                echo "Password is valid!";
            } else {
                echo "Invalid password.";
            }
            // echo $row['email']."  ".$row['pass'].'<br>';

        }
        $conn->close();

    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Parking Management System</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css"
    integrity="sha384-dpuaG1suU0eT09tx5plTaGMLBsfDLzUCCUXOY2j/LSvXYuG6Bqs43ALlhIqAJVRb" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Itim&family=M+PLUS+Rounded+1c&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>

        body {
            margin: 0;
            padding: 0;
            background-color: #2c3e50;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: "Itim", serif;
            font-weight: 400;
            font-style: normal;
        }
        .login-container {
            width: 400px;
            background-color: #34495e;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            text-align: center;
            color: white;
        }
        .login-container h1 {
            margin-bottom: 20px;
            font-size: 24px;
        }
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }
        .login-container button {
            background-color: #1abc9c;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        .login-container button:hover {
            background-color: #16a085;
        }
        .login-container a {
            color: #ecf0f1;
            text-decoration: none;
            font-size: 14px;
        }
        .login-container a:hover {
            text-decoration: underline;
        }
        .icon-container {
            font-size: 50px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="icon-container">
            <i class="fa fa-car" style="color: #1abc9c;"></i>
        </div>
        <h1>Login</h1>
        <form action="test.php" method="POST">
            <input type="text" name="email" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>
        <p><a href="/Database Project/register.php">Donot have Account. Register?</a></p>
        <p><a href="/Database Project/Admin_Login.php">Login as Admin</a></p>
    </div>
</body>
</html>
