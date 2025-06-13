<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    // Output the data
    echo "Email: " . $email . "<br>";
    echo "Contact No: " . $password. "<br>" ;
    $charArray = str_split($password);
    echo strlen($password);
    if(($charArray[4] == "-") && (strlen($password) == 12))
    {
        echo "Valid Number";
    }
    else
    {
        echo"Invalid Number";
    }
} else {
    echo "Invalid request method.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Form</title>
</head>
<body>
    <form action="passhash.php" method="post">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="password">Contact_No:</label>
        <input type="TEXT" id="password" name="password" required>
        <br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>