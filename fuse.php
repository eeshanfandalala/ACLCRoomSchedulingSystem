<?php
include 'config.php';

// Sign Up validation
function signupValidation()
{
    $username = $_POST['email_signup'];
    $password = $_POST['password_signup'];
    $confirm_password = $_POST['confirm_password'];
    $username_error = '';
    $password_error = '';
    $confirm_password_error = '';
    $isValid = true;

    if (empty($username)) {
        $username_error = "Enter Username";
        $isValid = false;
    }

    if (empty($password)) {
        $password_error = "Enter Password.";
        $isValid = false;
    } else if (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
        $password_error = "Password must contain at least one Uppercase, Lowercase, and a Special Character";
        $isValid = false;
    } else if (strlen($password) < 8) {
        $password_error = "Password must be at least 8 characters long.";
        $isValid = false;
    }

    if (empty($confirm_password)) {
        $confirm_password_error = "Please Confirm Your Password.";
        $isValid = false;
    } else if ($password !== $confirm_password) {
        $confirm_password_error = 'Passwords do not match';
        $isValid = false;
    }

    return [
        'isValid' => $isValid,
        'username_error' => $username_error,
        'password_error' => $password_error,
        'confirm_password_error' => $confirm_password_error
    ];
}

// Log in Validation
function loginValidation()
{
    $email_login = $_POST['email_login'];
    $password_login = $_POST['password_login'];
    $email_login_error = '';
    $password_login_error = '';
    $isValid = true;

    if (empty($email_login)) {
        $email_login_error = "Enter Username.";
        $isValid = false;
    }

    if (empty($password_login)) {
        $password_login_error = "Enter Password.";
        $isValid = false;
    }

    return [
        'isValid' => $isValid,
        'email_login_error' => $email_login_error,
        'password_login_error' => $password_login_error
    ];
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['signup_submit'])) {
        $signup_result = signupValidation();
        if (!$signup_result['isValid']) {
            echo "<script>alert('".$signup_result['username_error'], $signup_result['password_error'], $signup_result['confirm_password_error']."!'); window.location.href = 'index.html';</script>";
            // echo $signup_result['username_error'], $signup_result['password_error'], $signup_result['confirm_password_error'];
        } else {
            $name = $_POST['username'];
            $password = $_POST['password_signup'];
            $email = $_POST['email_signup'];
            $password = password_hash($password, PASSWORD_DEFAULT);

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
        }

    } elseif (isset($_POST['login_submit'])) {
        $login_result = loginValidation();
        if (!$login_result['isValid']) {
            
            echo $login_result['username_login_error'], $login_result['password_login_error'];
        } else {
            if (isset($_POST['login_submit'])) { 
                echo 'hi';

                $email = $_POST['email_login'];
                $pass = $_POST['password_login'];

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
            }
        }
    }
}
