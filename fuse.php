<?php
session_start();

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
    $isValidConfirm = true;

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
        $isValidConfirm = false;
    } else if ($password !== $confirm_password) {
        $confirm_password_error = 'Passwords do not match';
        $isValidConfirm = false;
    }

    return [
        'isValidConfirm' => $isValidConfirm,
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
            echo "<script>alert('" . $signup_result['username_error'], $signup_result['password_error'] . "!'); window.location.href = 'index.html';</script>";
            // echo $signup_result['username_error'], $signup_result['password_error'], $signup_result['confirm_password_error'];
        }else if(!$signup_result['isValidConfirm']){
            echo "<script>alert('" . $signup_result['confirm_password_error'] . "!'); window.location.href = 'index.html';</script>";

        } else {
            $name = $_POST['username'];
            $password = $_POST['password_signup'];
            $email = $_POST['email_signup'];
            $password = password_hash($password, PASSWORD_DEFAULT);
            $deafaultPic = 'user.png';


            $sql = $con->prepare("SELECT `teacher_email` FROM teacher_tb WHERE `teacher_email` = ?");
            $sql->bind_param("s", $email);
            $sql->execute();
            $sql->store_result();

            if ($sql->num_rows > 0) {
                echo "<script>alert('Email already exists!'); window.location.href = 'index.html';</script>";
            } else {
                $sqlInsert = $con->prepare("INSERT INTO `teacher_tb`(`teacher_name`, `teacher_password`, `teacher_email`, `teacher_pic`, `status`) VALUES (?,?,?,?, 0)");
                $sqlInsert->bind_param("ssss", $name, $password, $email, $deafaultPic);
                $sqlInsert->execute();
                echo "<script>alert('Success')</script>";
                echo "<script>window.location.href = 'index.html';</script>";
                $sqlInsert->close();
            }
            $sql->close();
        }
    } else if (isset($_POST['login_submit'])) {
        $login_result = loginValidation();
        if (!$login_result['isValid']) {

            echo $login_result['username_login_error'], $login_result['password_login_error'];
        } else {
            if (isset($_POST['login_submit'])) {


                $email = $_POST['email_login'];
                $pass = $_POST['password_login'];

                $sql = $con->prepare("SELECT 'teacher' AS user_type, `teacher_id`, `status`, `teacher_password` 
                        FROM teacher_tb 
                        WHERE `teacher_email` = ?");
                $sql->bind_param("s", $email);
                $sql->execute();
                $sql->store_result();

                if ($sql->num_rows > 0) {
                    $sql->bind_result($user_type, $user_id, $status, $password);
                    if ($sql->fetch()) {
                        if ($status == 1) {
                            if (password_verify($pass, $password)) {
                                if ($user_type === 'teacher') {
                                    $_SESSION['teacher_id'] = $user_id;
                                    header('Location: user-manage-account.php');
                                    exit; // Always exit after a header redirect
                                } else {
                                    echo "<script>alert('Invalid User Type!'); window.location.href = 'index.html';</script>"; // Change location
                                }
                            } else {
                                echo "<script>alert('Incorrect Password!'); window.location.href = 'index.html';</script>"; // Change location
                            }
                        } else {
                            echo "<script>alert('Account is pending for validation!'); window.location.href = 'index.html';</script>"; // Change location
                        }
                    } else {
                        echo "<script>alert('Incorrect Email!'); window.location.href = 'index.html';</script>"; // Change location
                    }
                } else {
                    // If user is not found in teacher roles, check SD role
                    $sql = $con->prepare("SELECT `SD_id`, `SD_password` 
                            FROM sd_tb 
                            WHERE `SD_email` = ?");
                    $sql->bind_param("s", $email);
                    $sql->execute();
                    $sql->store_result();

                    if ($sql->num_rows > 0) {
                        $sql->bind_result($sd_id, $sd_password);
                        if ($sql->fetch()) {

                            if (password_verify($pass, $sd_password)) {
                                $_SESSION['sd_id'] = $sd_id;
                                header('Location: admin-manage-account.php');
                                exit; // Always exit after a header redirect
                            } else {
                                echo "<script>alert('Incorrect Password!'); window.location.href = 'index.html';</script>"; // Change location
                            }
                        }
                    } else {
                        echo "<script>alert('Incorrect Email!'); window.location.href = 'index.html';</script>"; // Change location
                    }
                }
            }
        }
    }
}
