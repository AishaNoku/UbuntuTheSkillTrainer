<?php
session_start();

include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $user = $conn->real_escape_string($_POST['username']);
    $pass = $_POST['password'];
    $sql = "SELECT * FROM users WHERE username='$user'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($pass, $row['password'])) {
            $_SESSION['username'] = $user;
            $_SESSION['user_id'] = $row['id'];
            
            echo "<h1>Login Successful! Welcome back, $user</h1>";
            header("refresh:2;url=index.php"); 
            
        } else {
            echo "<h1>Incorrect Password</h1>";
            echo "<a href='login.html'>Try Again</a>";
        }
    } else {
        echo "<h1>User not found</h1>";
        echo "<a href='signup.html'>Register first</a>";
    }
    
    $conn->close();
}
?>