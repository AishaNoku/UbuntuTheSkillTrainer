<?php
// db_connect.php
$username = "root";
$password = "";
$dbname = "ubuntu_db";

$conn = @new mysqli("localhost", $username, $password, $dbname);

if ($conn->connect_error) {
    $conn = new mysqli("localhost:3307", $username, $password, $dbname);
}
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>