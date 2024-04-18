<?php

include('config.php');

$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];
$password = password_hash($password, PASSWORD_DEFAULT);
// $confirm_password = $_POST['confirm_password'];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $sql = $con->prepare("SELECT * FROM users_info WHERE username = ?"); // Change the sql statement according to the database
    $sql->bind_param("s", $username);
    $sql->execute();
    $sql->store_result();

    $sql2 = $con->prepare("SELECT * FROM users_info WHERE email = ?"); // Change the sql statement according to the database
    $sql2->bind_param("s", $email);
    $sql2->execute();
    $sql2->store_result();


    if ($sql->num_rows > 0) {
        echo "<script>alert('Username already exists!'); window.location.href = 'index.html';</script>"; // Change location
    }else if($sql2->num_rows > 0){
        echo "<script>alert('Email already in use!'); window.location.href = 'index.html';</script>"; // Change location
    } else {
        $sqlInsert = $con->prepare("INSERT INTO `users_info`(`username`, `password`, `email`) VALUES (?,?,?)"); // Change the sql statement according to the database
        $sqlInsert->bind_param("sss", $username, $password, $email);
        $sqlInsert->execute();
        echo "<script>alert('Success')</script>";
        $sqlInsert->close();
    }
    $sql->close();
}
