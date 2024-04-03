<?php
session_start();

include('config.php');

function sanitize_input($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = sanitize_input($_POST['uername']);
    $password = sanitize_input($_POST['password']);

    $sql = "SELECT * FROM ___ WHERE `username` = '$username'";
    $result = mysqli_query($con, $sql);

    if ($result) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        if (password_verify($row['password'], $password)) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];

            header("location: index.php");
            exit();
        } else {
            $error = "Invalid Password";
        }
    } else {
        $error = "Invalid Username";
    }
    mysqli_close($con);
}





// if ($username && $password != null) {

// } elseif ($username == null) {
//     echo "<script>
//         window.alert('Enter username')
//         </script>";
// } elseif ($password == null) {
//     echo "<script>
//         window.alert('Enter password')
//         </script>";
// }
