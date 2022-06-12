<?php
// 4.3
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "calendar";
$port = "3306";




// takis 3
// $servername = "localhost";
// $username = "takis3";
// $password = "skho@00866";
// $dbname = "atkregis";
// $port = "3306";



// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8");