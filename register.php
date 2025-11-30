<?php

include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $pass = $_POST['password'];

    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password) VALUES ('$user', '$email', '$hashed_password')";

    if ($conn->query($sql) === TRUE) {
        
        echo "<h1>Success! Account created. </h1>";
        echo "<p>Redirecting to homepage...</p>";
        
        header("refresh:3;url=index.html");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
}
?>