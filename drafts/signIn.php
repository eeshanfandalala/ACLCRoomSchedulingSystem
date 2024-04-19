<?php

include('config.php');



if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $password = password_hash($password, PASSWORD_DEFAULT);
    // $confirm_password = $_POST['confirm_password'];
    $type = $_POST['type'];


    if ($type === "Teacher") {
        $sql = $con->prepare("SELECT `teacher_email` FROM teacher_tb WHERE `teacher_email` = ?"); // Change the sql statement according to the database
        $sql->bind_param("s", $email);
        $sql->execute();
        $sql->store_result();

        if ($sql->num_rows > 0) {
            echo "<script>alert('Email already exists!'); window.location.href = 'index.html';</script>"; // Change location
        } else {
            $sqlInsert = $con->prepare("INSERT INTO `teacher_tb`(`teacher_name`, `teacher_password`, `teacher_email`, `status`) VALUES (?,?,?, 0)"); // Change the sql statement according to the database
            $sqlInsert->bind_param("sss", $name, $password, $email);
            $sqlInsert->execute();
            echo "<script>alert('Success')</script>";
            $sqlInsert->close();
        }
        $sql->close();
    } else if ($type === "Technical") {
        $sql2 = $con->prepare("SELECT `technical_email` FROM technical_tb WHERE `technical_email` = ?"); // Change the sql2 statement according to the database
        $sql2->bind_param("s", $email);
        $sql2->execute();
        $sql2->store_result();

        if ($sql2->num_rows > 0) {
            echo "<script>alert('Email already exists!'); window.location.href = 'index.html';</script>"; // Change location
        } else {
            $sql2Insert = $con->prepare("INSERT INTO `technical_tb`(`technical_name`, `technical_password`, `technical_email`, `status`) VALUES (?,?,?, 0)"); // Change the sql2 statement according to the database
            $sql2Insert->bind_param("sss", $name, $password, $email);
            $sql2Insert->execute();
            echo "<script>alert('Success')</script>";
            $sql2Insert->close();
        }
        $sql2->close();
    }
    // $sql = $con->prepare("SELECT * FROM users_info WHERE username = ?"); // Change the sql statement according to the database
    // $sql->bind_param("s", $username);
    // $sql->execute();
    // $sql->store_result();

    // $sql2 = $con->prepare("SELECT * FROM users_info WHERE email = ?"); // Change the sql statement according to the database
    // $sql2->bind_param("s", $email);
    // $sql2->execute();
    // $sql2->store_result();


    // if ($sql->num_rows > 0) {
    //     echo "<script>alert('Email already exists!'); window.location.href = 'index.html';</script>"; // Change location
    // } else {
    //     $sqlInsert = $con->prepare("INSERT INTO `teacher_tb`(`teacher_name`, `teacher_password`, `teacher_email`) VALUES (?,?,?)"); // Change the sql statement according to the database
    //     $sqlInsert->bind_param("sss", $name, $password, $email);
    //     $sqlInsert->execute();
    //     echo "<script>alert('Success')</script>";
    //     $sqlInsert->close();
    // }
    // $sql->close();
}
