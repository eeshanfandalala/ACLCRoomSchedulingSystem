<?php

include 'config.php';


if ($_SERVER['REQUEST_METHOD'] == "POST") {

    if (isset($_POST['login_form'])) { // --- if the request is for login ---

        $email = $_POST['email'];
        $pass = $_POST['password'];

        //for teacher
        $sql = $con->prepare("SELECT `status`, `teacher_password` FROM teacher_tb WHERE `teacher_email` = ?"); // Change the sql statement according to the database
        $sql->bind_param("s", $email);
        $sql->execute();
        $sql->store_result();

        //for technical
        $sql2 = $con->prepare("SELECT `status`, `technical_password` FROM technical_tb WHERE `technical_email` = ?"); // Change the sql statement according to the database
        $sql2->bind_param("s", $email);
        $sql2->execute();
        $sql2->store_result();

        if ($sql->num_rows > 0) {
            $sql->bind_result($status, $password);
            if ($sql->fetch()) {
                if ($status == 1) {
                    if (password_verify($password, $pass)) {
                        echo "<script> window.location.href = 'profile_teacher.html';</script>"; // Change location //

                    } else {
                        echo "<script>alert('Incorrect Password!'); window.location.href = 'index.html';</script>"; // Change location
                    }
                } else {
                    echo "<script>alert('Account is pending for validation!'); window.location.href = 'index.html';</script>"; // Change location

                }
            } else {
                echo "<script>alert('Incorrect Email!'); window.location.href = 'index.html';</script>"; // Change location
            }
        } else if ($sql2->num_rows > 0) {
            $sql2->bind_result($status, $password);
            if ($sql2->fetch()) {
                if ($status == 1) {
                    if (password_verify($password, $pass)) {
                        echo "<script> window.location.href = 'profile_technical.html';</script>"; // Change location //

                    } else {
                        echo "<script>alert('Incorrect Password!'); window.location.href = 'index.html';</script>"; // Change location
                    }
                } else {
                    echo "<script>alert('Account is pending for validation!'); window.location.href = 'index.html';</script>"; // Change location

                }
            } else {
                echo "<script>alert('Incorrect Email!'); window.location.href = 'index.html';</script>"; // Change location
            }
        }
    } // else if (isset($_POST["forgot_form"])) { // --- if the request is for forgot password  --- Not Finish --- Tiwasa ninyo ---

    //     $username = $_POST['username_login'];

    //     $sql = $con->prepare("SELECT `email` FROM users_info WHERE username = ?"); // Change the sql statement according to the database
    //     $sql->bind_param("s", $username);
    //     $sql->execute();
    //     $sql->store_result();

    //     $email = '';

    //     if ($sql->num_rows > 0) {
    //         $sql->bind_result($email);
    //         if ($sql->fetch()) {
    //             $token = generateRandomString();
    //             $expiration = date('Y-m-d H:i:s', strtotime('+1 hour'));

    //             $stmt = $con->prepare("INSERT INTO reset_password (email, token, expiration) VALUES (?, ?, ?)");
    //             $stmt->bind_param("sss", $email, $token, $expiration);
    //             $stmt->execute();

    //             $reset_link = "http://localhost:3000/reset_password.php?token=$token";
    //             $message = "To reset your password, click the following link: $reset_link"; // Email message
    //             mail($email, "Password Reset", $message); // Send email
    //         }
    //     } else {
    //         echo "<script>alert('Username does not exists!'); window.location.href = 'index.html';</script>"; // Change location
    //     }
    // }
}

// Function to generate random string for token
function generateRandomString($length = 32)
{
    return bin2hex(random_bytes($length));
}
