<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $databasename = "Parking_Management_Database";
    $conn = mysqli_connect($servername,$username,$password,$databasename);
    if(!$conn)
    {
        echo("Connection Failed!".mysqli_connect_error());
    }
    else
    {
        // echo("Connected Successfully");
    }
?>