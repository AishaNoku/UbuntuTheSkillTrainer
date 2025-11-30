<?php
$servername = "localhost:3307";
$username = "root";
$password = "";
$dbname = "ubuntu_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    $conn = new mysqli("localhost:3307", $username, $password,$dbname);
    die("Connection failed: " . $conn->connect_error);
}
if ($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}
?>