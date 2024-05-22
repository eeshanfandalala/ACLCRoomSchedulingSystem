<?php
include '../ACLC-College-of-Ormoc-Classroom-Scheduling-System/config.php';
if (!isset($_SESSION['sd_id'])) {
    header("Location: ../index.html");
    exit;
} else {
    $user_id = $_SESSION['sd_id'];
    $sql = mysqli_query($con, "SELECT * FROM `sd_tb` WHERE `SD_id` = '$user_id'");
    while ($row = mysqli_fetch_array($sql)) {
        $sd_id = $row['SD_id'];
        ?>

        <body>

            <!-- FOR PROFILE UPDATE-->
            <div>
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
                    <div>
                        <img src="./profile_pictures/<?php echo $row['SD_pic'];
                        $profpic = $row['SD_pic']; ?>" alt="profile picture">

                        <input type="file" name="profile_pic" id="profile_pic">
                    </div>
                    <div>
                        <input type="text" name="SD_lastname" id="SD_lastname" value="<?php echo $row['SD_lastname']; ?>"
                            disabled placeholder="Last Name">
                        <input type="text" name="SD_firstname" id="SD_firstname" value="<?php echo $row['SD_firstname']; ?>"
                            disabled placeholder="First Name">
                    </div>
                    <div>
                        <input type="email" name="SD_email" id="SD_email" value="<?php echo $row['SD_email']; ?>" disabled
                            placeholder="email">
                        <input type="number" name="SD_number" id="SD_number" value="<?php echo $row['SD_number']; ?>" disabled
                            placeholder="number">
                        <!-- <input type="text" name="teacher_password" id="teacher_password" disabled value="<?php //echo $row['teacher_password']; 
                                ?>"> -->
                    </div>
                    <button type="submit" name="update_btn" id="update_btn" style="display: none;">Update</button>
                </form>
            </div>


            <!-- FOR ACCOUNT RESIGNATION-->
            <link rel="stylesheet" href="/css/modal.css">
            <div>
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" onsubmit="return confirm('sure ka?!')">
                    <button type="submit" name="btnsub">Reset</button>
                </form>
            </div>
        </body>


        <script>
            document.getElementById('edit_btn').addEventListener('click', function () {
                var inputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="number"], input[type="password"]');
                var updateBtn = document.getElementById('update_btn');
                for (var i = 0; i < inputs.length; i++) {
                    inputs[i].disabled = !inputs[i].disabled;
                }
                // Check if any input is disabled
                var anyInputDisabled = Array.from(inputs).some(function (input) {
                    return input.disabled;
                });

                // Toggle visibility of the update button
                updateBtn.style.display = anyInputDisabled ? 'none' : 'block';
            });
        </script>
        <?php
    }
}
if ($_SERVER['REQUEST_METHOD'] === "POST") {

    if (isset($_POST['update_btn'])) {
        if (!empty($_FILES['profile_pic']['name'])) {
            $profile_pic = $_FILES['profile_pic']['name'];
            $template = $_FILES['profile_pic']['tmp_name'];
            $folder = './profile_pictures/' . $profile_pic;

            move_uploaded_file($template, $folder);
        } else {
            // If no new picture uploaded, fetch the existing picture name
            $profile_pic = $profpic;
        }

        $SD_lastname = $_POST['SD_lastname'];
        $SD_firstname = $_POST['SD_firstname'];
        $SD_email = $_POST['SD_email'];
        $SD_number = $_POST['SD_number'];

        $updateProfileInfo = $con->prepare("UPDATE sd_tb SET SD_pic =?, SD_lastname=?, SD_firstname=?, SD_email=?, SD_number=?");
        $updateProfileInfo->bind_param("ssssi", $profile_pic, $SD_lastname, $SD_firstname, $SD_email, $SD_number);
        if ($updateProfileInfo->execute()) {
            echo "<script>alert('Profile updated successfully');
            window.location.replace('admin-manage-account.php');</script>";
        }
        $updateProfileInfo->close();
        $_POST['update_btn'] == false;
        exit;
    } elseif (isset($_POST['btnsub'])) {
        // echo 'hi';
        $deafaultSDValues = array(
            "SD_lastname" => "",
            "Lastname" => "",
            "SD_number" => 0,
            "SD_pic" => "user.png",
            "Email" => "aclcormocadmin@gmail.com",
            "Password" => "Komong2x!",
        );

        $sqlTurncate = $con->prepare("TRUNCATE TABLE sd_tb");
        $sqlTurncate->execute();

        if ($sqlTurncate) {
            $insertDefaultSDVal = $con->prepare("INSERT INTO sd_tb(SD_email, SD_password, SD_pic) VALUES (?,?,?)");
            if ($insertDefaultSDVal) {
                $hashedpassword = password_hash($deafaultSDValues['Password'], PASSWORD_DEFAULT);
                $insertDefaultSDVal->bind_param("sss", $deafaultSDValues['Email'], $hashedpassword, $deafaultSDValues['SD_pic']);
                $insertDefaultSDVal->execute();
                if ($insertDefaultSDVal->affected_rows > 0) {
                    echo "<script>alert('Account has been changed to default successfully you will be logged out!')</script>";
                    if (isset($_SESSION['sd_id'])) {
                        unset($_SESSION['sd_id']);
                        echo "<script>window.location.href = 'index.html'; </script>";
                    }
                } else {
                    echo "<script>alert('Failed to insert default SD values')</script>";
                }
            } else {
                echo "<script>alert('Failed to prepare statement for inserting default SD values')</script>";
            }
        } else {
            echo "<script>alert('Failed to truncate SD Details')</script>";
        }
    }
}
