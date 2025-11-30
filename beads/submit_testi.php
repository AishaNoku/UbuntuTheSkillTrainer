<?php
session_start();
include '../db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $name = htmlspecialchars($_POST['name']);
    $role = htmlspecialchars($_POST['role']);
    $content = htmlspecialchars($_POST['content']);
    $rating = (int)$_POST['rating'];

    $words = explode(" ", $name);
    $initials = "";
    foreach ($words as $w) {
        $initials .= strtoupper($w[0]);
    }
    $initials = substr($initials, 0, 2);

    $stmt = $conn->prepare("INSERT INTO testimonials (name, role, content, rating, initials) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssis", $name, $role, $content, $rating, $initials);

    if ($stmt->execute()) {
        header("Location: index.php?review_done=1");
    } else {
        echo "Error: " . $conn->error;
    }
    
    $stmt->close();
    $conn->close();
}
?>