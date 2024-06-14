<?php

include 'config.php';

if (!isset($_SESSION['sd_id'])) {
    header("Location: ../index.html");
    exit;
} else {
    $user_id = $_SESSION['sd_id'];
    $sql = mysqli_query($con, "SELECT * FROM `sd_tb` WHERE `SD_id` = '$user_id'");
    if (!$sql) {
        die('Error: ' . mysqli_error($con));
    }
    while ($row = mysqli_fetch_array($sql)) {
        $sd_id = $row['SD_id'];
?>

        <body>
            <main>
                <div class="nav-container">
                    <button onclick="window.location.href='admin-manage-account.php'" class="nav-button active">My
                        Profile</button>

                    <form action="" method="post">
                        <input type="hidden" name="changepasspage" value="on">
                        <button type="submit" class="nav-button">Change Password</button>
                    </form>

                    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" onsubmit="return confirm('Are you sure?')">
                        <button type="submit" name="btnsub" class="nav-button">Reset Account</button>
                    </form>
                </div>

                <!-- FOR PROFILE UPDATE-->
                <div class="profile-update">
                    <button id="edit_btn" value="editAccount">Edit Profile</button><br><br>
                    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data" class="form-profile">
                        <div>
                            <label>First Name</label><br>
                            <input type="text" name="SD_firstname" id="SD_firstname" value="<?php echo $row['SD_firstname']; ?>" disabled required><br>

                            <label>Last Name</label><br>
                            <input type="text" name="SD_lastname" id="SD_lastname" value="<?php echo $row['SD_lastname']; ?>" disabled required><br>

                            <label>Email</label><br>
                            <input type="email" name="SD_email" id="SD_email" value="<?php echo $row['SD_email']; ?>" disabled required><br>

                            <label>Contact Number</label><br>
                            <input type="tel" pattern="[0-9]{11}" name="SD_number" id="SD_number" value="<?php echo $row['SD_number']; ?>" disabled><br>
                            <p class="instructions">Enter your 11-digit phone number (e.g., 09123456789).</p>

                            <button type="submit" name="update_btn" id="update_btn" style="display: none;">Save Changes</button>
                        </div>

                        <!-- PROFILE PICTURE -->
                        <div class="profile-picture-container">
                            <div class="file-input-wrapper">
                                <img src="./profile_pictures/<?php echo $row['SD_pic'];
                                                                $profpic = $row['SD_pic']; ?>" alt="profile picture"><br>
                                <input type="file" name="profile_pic" id="profile_pic" disabled>
                                <label for="profile_pic" class="custom-file-input">Change Photo</label>
                            </div>
                        </div>
                    </form>
                </div>
            </main>
        </body>

        <script>
            document.getElementById('edit_btn').addEventListener('click', function() {
                var inputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="tel"], input[type="password"], input[type="file"]');
                var updateBtn = document.getElementById('update_btn');
                for (var i = 0; i < inputs.length; i++) {
                    inputs[i].disabled = !inputs[i].disabled;
                }
                var anyInputDisabled = Array.from(inputs).some(function(input) {
                    return input.disabled;
                });

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
            $profile_pic = $profpic;
        }

        if (!empty($_POST['SD_lastname']) || !empty($_POST['SD_firstname']) || !empty($_POST['SD_email']) || !empty($_POST['SD_lastname']) || !empty($_POST['SD_number'])) {

            $SD_lastname = $_POST['SD_lastname'];
            $SD_firstname = $_POST['SD_firstname'];
            $SD_email = $_POST['SD_email'];
            $SD_number = $_POST['SD_number'];

            $updateProfileInfo = $con->prepare("UPDATE sd_tb SET SD_pic =?, SD_lastname=?, SD_firstname=?, SD_email=?, SD_number=?");
            $updateProfileInfo->bind_param("sssss", $profile_pic, $SD_lastname, $SD_firstname, $SD_email, $SD_number);
            if ($updateProfileInfo->execute()) {
                echo "<script>alert('Profile updated successfully');
                window.location.replace('admin-manage-account.php');</script>";
            }
            $updateProfileInfo->close();
            $_POST['update_btn'] == false;
            exit;
        }
    } elseif (isset($_POST['btnsub'])) {
        // echo 'hi';
        $deafaultSDValues = array(
            "SD_lastname" => "",
            "Lastname" => "",
            "SD_number" => "",
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
