<?php
include 'config.php';

// Function to validate the new password
function validateNewPassword($newPass, $confirmPass)
{
    $newPass_error = '';
    $confirmPass_error = '';
    $isValid = true;
    $isValidConfirmPass = true;

    if (empty($newPass)) {
        $newPass_error = 'Please enter new password';
        $isValid = false;
    } elseif (!preg_match('/[A-Z]/', $newPass) || !preg_match('/[a-z]/', $newPass) || !preg_match('/[0-9]/', $newPass) || !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $newPass)) {
        $newPass_error = 'Password must contain at least one uppercase, lowercase, digit, and special character';
        $isValid = false;
    } elseif (strlen($newPass) < 8) {
        $newPass_error = 'Password must be at least 8 characters long';
        $isValid = false;
    }

    if (empty($confirmPass)) {
        $confirmPass_error = 'Please confirm new password';
        $isValidConfirmPass = false;
    } elseif ($confirmPass !== $newPass) {
        $confirmPass_error = 'Passwords do not match';
        $isValidConfirmPass = false;
    }

    return [
        'isValid' => $isValid,
        'newPass_error' => $newPass_error,
        'isValidConfirmPass' => $isValidConfirmPass,
        'confirmPass_error' => $confirmPass_error
    ];
}

// Handle password change form submission
if (isset($_POST['subNewPass'])) {
    if (isset($_POST['currentPass']) && !empty($_POST['currentPass'])) {
        $currentPass = $_POST['currentPass'];
        $user_id = $_SESSION['teacher_id']; // Assuming the user ID is stored in the session

        $getPass = $con->prepare("SELECT teacher_password FROM teacher_tb WHERE teacher_id = ?");
        $getPass->bind_param("i", $user_id);
        $getPass->execute();
        $getPass->store_result();
        $getPass->bind_result($pass);
        $getPass->fetch();

        if (password_verify($currentPass, $pass)) {
            $newPass = $_POST['newPass'];
            $confirmPass = $_POST['confirmPass'];
            $validPass = validateNewPassword($newPass, $confirmPass);
            if (!$validPass['isValid']) {
                echo "<script>alert('" . $validPass['newPass_error'] . "')</script>";
            }else if(!$validPass['isValidConfirmPass']){
                echo "<script>alert('" . $validPass['confirmPass_error'] . "')</script>";

            } else {
                $hashedNewPass = password_hash($newPass, PASSWORD_DEFAULT);
                $updatePassword = $con->prepare("UPDATE teacher_tb SET teacher_password = ? WHERE teacher_id = ?");
                $updatePassword->bind_param("si", $hashedNewPass, $user_id);
                if ($updatePassword->execute()) {
                    echo "<script>alert('Password Updated Successfully!');</script>";
                    $_SESSION['page'] = 'off'; // Update the session to include profile page
                    echo "<script>window.location.replace('user-manage-account.php');</script>"; // Redirect to admin-manage-account.php
                    exit; // Ensure no further code is executed
                } else {
                    echo "<script>alert('Error updating password. Please try again.')</script>";
                }
            }
        } else {
            echo "<script>alert('Current password is incorrect.')</script>";
        }
        $getPass->close();
    }
}
if (isset($_POST['backtoeditprofile'])) {
    $_SESSION['page'] = 'off';
    echo "<script>window.location.replace('user-manage-account.php');</script>"; // Redirect to user-manage-account.php
    exit; // Ensure no further code is executed
}
?>

<main>
    <div class="nav-container">
        <form action="" method="post">
            <input type="hidden" name="back" value="off">
            <button type="submit" name="backtoeditprofile" class="nav-button">My Profile</button>
        </form>

        <form action="" method="post">
            <input type="hidden" name="changepasspage" value="on">
            <button type="submit" class="nav-button active">Change Password</button>
        </form>

    </div>

    <div class="change-password-container">
        <form action="" method="post">
            <div>
                <label>Enter Current Password</label><br>
                <input type="password" name="currentPass" id="currentPass" required value="<?php if (isset($_POST['currentPass']))
                    echo $_POST['currentPass']; ?>" />

                <label>Enter New Password</label><br>
                <input type="password" name="newPass" id="newPass" required>

                <label>Confirm New Password</label><br>
                <input type="password" name="confirmPass" id="confirmPass" required>
                
                <button type="submit" name="subNewPass">Submit</button>
            </div>
        </form>
    </div>
</main>