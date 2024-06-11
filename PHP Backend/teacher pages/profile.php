<?php

include 'config.php';

if (!isset($_SESSION['teacher_id'])) {
    header("Location: ../index.html");
    exit;
} else {
    $user_id = $_SESSION['teacher_id'];

    $sql = mysqli_query($con, "SELECT * FROM `teacher_tb` WHERE `teacher_id` = '$user_id'");
    while ($row = mysqli_fetch_array($sql)) {

?>

        <body>
            <main>
                <div class="nav-container">
                    <button onclick="window.location.href='user-manage-account.php'" class="nav-button active">My
                        Profile</button>

                    <form action="" method="post">
                        <input type="hidden" name="changepasspage" value="on">
                        <button type="submit" class="nav-button">Change Password</button>
                    </form>
                </div>

                <!-- FOR PROFILE UPDATE-->
                <div class="profile-update">
                    <button id="edit_btn">Edit Profile</button>
                    <form method="post" enctype="multipart/form-data" class="form-profile">
                        <div>
                            <label>Name</label><br>
                            <input type="text" name="teacher_name" id="teacher_name" value="<?php echo $row['teacher_name']; ?>" disabled required><br>

                            <label>Department</label><br>
                            <select name="teacher_dept" id="" required>
                                <?php
                                $fetchdepts = mysqli_query($con, "SELECT department_name FROM department_tb");
                                while ($rowdept = mysqli_fetch_array($fetchdepts)) {
                                ?>
                                    <option value="<?php echo $rowdept['department_name']; ?>" <?php echo $rowdept['department_name'] == $row['teacher_department'] ? 'selected' : ''; ?>><?php echo $rowdept['department_name']; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                            <!-- <input type="text" name="teacher_dept" id="teacher_dept" value="<?php // echo $row['teacher_department']; 
                                                                                                    ?>" disabled required> -->

                            <label>Profeciency</label><br>
                            <input type="text" name="teacher_prof" id="teacher_prof" value="<?php echo $row['teacher_proficency']; ?>" disabled required>
                            <label>Email</label><br>
                            <input type="email" name="teacher_email" id="teacher_email" value="<?php echo $row['teacher_email']; ?>" disabled required>
                            <label>Number</label><br>
                            <input type="number" name="teacher_number" id="teacher_number" value="<?php echo $row['teacher_number']; ?>" disabled required>
                            <!-- <input type="text" name="teacher_password" id="teacher_password" disabled value="<?php //echo $row['teacher_password']; 
                                                                                                                    ?>"> -->

                            <button type="submit" name="update_btn" id="update_btn" style="display: none;">Update</button>
                        </div>

                        <!-- PROFILE PICTURE -->
                        <div class="profile-picture-container">
                            <div class="file-input-wrapper">
                                <img src="./profile_pictures/<?php echo $row['teacher_pic'];
                                                                $profpic = $row['teacher_pic']; ?>" alt="profile picture">
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
                var inputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="number"], input[type="password"], input[type="file"]');
                var updateBtn = document.getElementById('update_btn');
                for (var i = 0; i < inputs.length; i++) {
                    inputs[i].disabled = !inputs[i].disabled;
                }
                // Check if any input is disabled
                var anyInputDisabled = Array.from(inputs).some(function(input) {
                    return input.disabled;
                });

                // Toggle visibility of the update button
                updateBtn.style.display = anyInputDisabled ? 'none' : 'block';
            });
        </script>
<?php
    }
}

if (isset($_POST['update_btn'])) {
    $teacher_name = $_POST['teacher_name'];
    $teacher_dept = $_POST['teacher_dept'];
    $teacher_prof = $_POST['teacher_prof'];
    $teacher_email = $_POST['teacher_email'];
    $teacher_number = $_POST['teacher_number'];

    // Check if a new profile picture is uploaded
    if (!empty($_FILES['profile_pic']['name'])) {
        $profile_pic = $_FILES['profile_pic']['name'];
        $template = $_FILES['profile_pic']['tmp_name'];
        $folder = '../profile_pictures/' . $profile_pic;

        move_uploaded_file($template, $folder);
    } else {
        // If no new picture uploaded, fetch the existing picture name
        $profile_pic = $profpic;
    }

    // Update the database
    $sql = $con->prepare("UPDATE teacher_tb SET teacher_pic = ?, teacher_name = ?, teacher_department=?, teacher_proficency = ?, teacher_email= ?, teacher_number = ? WHERE teacher_id='$user_id'");
    $sql->bind_param("sssssi", $profile_pic, $teacher_name, $teacher_dept, $teacher_prof, $teacher_email, $teacher_number);
    if ($sql->execute()) {
        echo "<script>alert('Profile updated successfully');
                window.location.replace('user-manage-account.php');</script>";
    }
    $sql->close();
    $_POST['update_btn'] == false;
    exit;
}
?>