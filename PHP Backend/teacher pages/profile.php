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
            <div>
                <form method="post" enctype="multipart/form-data">
                    <div>
                        <img src="./profile_pictures/<?php echo $row['teacher_pic']; $profpic = $row['teacher_pic']; ?>" alt="profile picture">

                        <input type="file" name="profile_pic" id="profile_pic">
                    </div>
                    <div>
                        <input type="text" name="teacher_name" id="teacher_name"  value="<?php echo $row['teacher_name']; ?>" disabled placeholder="name">
                        <input type="text" name="teacher_dept" id="teacher_dept"  value="<?php echo $row['teacher_department']; ?>" disabled placeholder="department">
                        <input type="text" name="teacher_prof" id="teacher_prof"  value="<?php echo $row['teacher_proficency']; ?>" disabled placeholder="proficency">
                    </div>
                    <div>
                        <input type="email" name="teacher_email" id="teacher_email"  value="<?php echo $row['teacher_email']; ?>" disabled placeholder="email">
                        <input type="number" name="teacher_number" id="teacher_number"  value="<?php echo $row['teacher_number']; ?>" disabled placeholder="number">
                        <!-- <input type="text" name="teacher_password" id="teacher_password" disabled value="<?php //echo $row['teacher_password']; 
                                                                                                                ?>"> -->
                    </div>
                    <button type="submit" name="update_btn" id="update_btn" style="display: none;">Update</button>
                </form>
                <button id="edit_btn">Edit</button>

            </div>

        </body>

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
    $sql->execute();
}
?>

<script>
    document.getElementById('edit_btn').addEventListener('click', function() {
        var inputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="number"], input[type="password"]');
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