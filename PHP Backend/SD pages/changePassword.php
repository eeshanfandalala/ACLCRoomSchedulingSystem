<?php
include 'config.php';

// Function to validate the new password
function validateNewPassword($newPass, $confirmPass)
{
    $newPass_error = '';
    $confirmPass_error = '';
    $isValid = true;

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
        $isValid = false;
    } elseif ($confirmPass !== $newPass) {
        $confirmPass_error = 'Passwords do not match';
        $isValid = false;
    }

    return [
        'isValid' => $isValid,
        'newPass_error' => $newPass_error,
        'confirmPass_error' => $confirmPass_error
    ];
}

// Handle password change form submission
if (isset($_POST['subNewPass'])) {
    if (isset($_POST['currentPass']) && !empty($_POST['currentPass'])) {
        $currentPass = $_POST['currentPass'];
        $user_id = $_SESSION['sd_id']; // Assuming the user ID is stored in the session

        $getPass = $con->prepare("SELECT SD_password FROM sd_tb WHERE SD_id = ?");
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
                echo "<script>alert('" . $validPass['newPass_error'] . " " . $validPass['confirmPass_error'] . "')</script>";
            } else {
                $hashedNewPass = password_hash($newPass, PASSWORD_DEFAULT);
                $updatePassword = $con->prepare("UPDATE sd_tb SET SD_password = ? WHERE SD_id = ?");
                $updatePassword->bind_param("si", $hashedNewPass, $user_id);
                if ($updatePassword->execute()) {
                    echo "<script>alert('Password Updated Successfully!');</script>";
                    $_SESSION['page'] = 'off'; // Update the session to include profile page
                    echo "<script>window.location.replace('admin-manage-account.php');</script>"; // Redirect to admin-manage-account.php
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
    echo "<script>window.location.replace('admin-manage-account.php');</script>"; // Redirect to admin-manage-account.php
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

        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" onsubmit="return confirm('Are you sure?')">
            <button type="submit" name="btnsub" class="nav-button">Reset Account</button>
        </form>
    </div>

    <div class="change-password-container">
        <form action="" method="post">
            <div>
                <label>Enter Current Password</label><br>
                <input type="password" name="currentPass" id="currentPass" required value="<?php if (isset($_POST['currentPass']))
                    echo $_POST['currentPass']; ?>" />
                <!-- <input type="hidden" name="changepasspage" value="on"> -->
                <label>Enter New Password</label><br>
                <input type="password" name="newPass" id="newPass" required>

                <label>Confirm New Password</label><br>
                <input type="password" name="confirmPass" id="confirmPass" required>

                <button type="submit" name="subNewPass">Submit</button>
            </div>
    </div>
    </form>
    </div>

</main>