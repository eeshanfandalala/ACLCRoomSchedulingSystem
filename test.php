<?php

include 'config.php';
// if(isset($_POST['sub'])){
//     $pic = $_FILES['pic']['name'];
//     $template = $_FILES['pic']['tmp_name'];
//     $folder = 'profile_pictures/' . $pic;

//     $sql = $con->prepare("UPDATE `teacher_tb` SET `teacher_pic`=?");
//     $sql->bind_param("s", $pic);
//     $sql->execute();

//     if (move_uploaded_file($template, $folder)) {
//         echo "success";
//     } else {
//         echo "error";
//     }
// }




?>

<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="pic" id="pic">
        <input type="submit" name="sub" id="sub">
    </form>

    <div>
        <?php

        // $res = mysqli_query($con, "SELECT `teacher_pic` FROM `teacher_tb` WHERE `teacher_id` = 1");
        // while ($row = mysqli_fetch_array($res)) {


        ?>
        <img src="profile_pictures/<?php //echo $row['teacher_pic'] ?>" alt="">
        <?php // }   ?>
    </div>
</body>

</html> -->






<?php // Can be used in Account Resignation

// $pass = "Admin@123";
// $hashpass = password_hash($pass, PASSWORD_DEFAULT);

// $sql = $con->prepare("UPDATE sd_tb SET SD_password=?");
// $sql->bind_param("s", $hashpass);
// $sql->execute();

?>