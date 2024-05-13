<?php
session_start();
include '../config.php';

if (!isset($_SESSION['technical_id'])) {
    header("Location: ../index.html");
    exit;
} else {
    $user_id = $_SESSION['technical_id'];

    $sql = mysqli_query($con, "SELECT * FROM `technical_tb` WHERE `technical_id` = '$user_id'");
    while ($row = mysqli_fetch_array($sql)) {

?>

        <body>
            <div>
                <form method="post" enctype="multipart/form-data">
                    <div>
                        <img src="../profile_pictures/<?php echo $row['technical_pic']; $profpic = $row['technical_pic']; ?>" alt="profile picture">

                        <input type="file" name="profile_pic" id="profile_pic">
                    </div>
                    <div>
                        <input type="text" name="technical_name" id="technical_name"  value="<?php echo $row['technical_name']; ?>" disabled placeholder="name">
                        <input type="text" name="technical_dept" id="technical_dept"  value="<?php echo $row['technical_department']; ?>" disabled placeholder="department">
                        <input type="text" name="technical_prof" id="technical_prof"  value="<?php echo $row['technical_proficency']; ?>" disabled placeholder="proficency">
                    </div>
                    <div>
                        <input type="email" name="technical_email" id="technical_email"  value="<?php echo $row['technical_email']; ?>" disabled placeholder="email">
                        <input type="number" name="technical_number" id="technical_number"  value="<?php echo $row['technical_number']; ?>" disabled placeholder="number">
                        <!-- <input type="text" name="technical_password" id="technical_password" disabled value="<?php //echo $row['technical_password']; 
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
    $technical_name = $_POST['technical_name'];
    $technical_dept = $_POST['technical_dept'];
    $technical_prof = $_POST['technical_prof'];
    $technical_email = $_POST['technical_email'];
    $technical_number = $_POST['technical_number'];

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
    $sql = $con->prepare("UPDATE technical_tb SET technical_pic = ?, technical_name = ?, technical_department=?, technical_proficency = ?, technical_email= ?, technical_number = ? WHERE technical_id='$user_id'");
    $sql->bind_param("sssssi", $profile_pic, $technical_name, $technical_dept, $technical_prof, $technical_email, $technical_number);
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