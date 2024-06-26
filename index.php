<!--<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <div class="container">
        <div class="form-container" id="loginContainer"> LOGIN 
            <span id="loginCard"></span>
            <h2>Login</h2>
            <form action="login.php" method="post" onsubmit="return loginValidation()" id="loginform">
                <div>
                    <label for="username_login">Username: </label><br>
                    <input type="text" id="username_login" name="username_login" required><br>
                    <span id="username_login_error" name="username_login_error" class="error"></span>
                </div>
                <div>
                    <label for="password_login">Password: </label><br>
                    <input type="password" id="password_login" name="password_login"><br>
                    <span id="password_login_error" name="password_login_error" class="error"></span>
                </div>

                <button type="submit" name="login_form">Submit</button>

                <input type="submit" value="Submit" name="forgot_form" id="forgot_form">

                <a href="#" onclick="showSignUp()">Sign Up</a>
            </form>
        </div>

        <div class="form-container hidden" id="signupContainer"> SIGN UP 
            <span id="signupCard"></span>
            <h2>Sign Up</h2>
            <form action="signup.php" method="post" onsubmit="return signupValidation()" id="signUpForm">
                <div>
                    <label for="username">Username: </label><br>
                    <input type="text" id="username" name="username"><br>
                    <span id="username_error" class="error"></span>
                </div>
                <div>
                    <label for="email">Email: </label><br>
                    <input type="email" id="email" name="email"><br>
                    <span id="email_error" class="error"></span>
                </div>

                <div>
                    <label for="password">Password: </label><br>
                    <input type="password" id="password" name="password"><br>
                    <span id="password_error" class="error"></span>
                </div>
                <div>
                    <label for="confirm_password">Confirm Password: </label><br>
                    <input type="password" id="confirm_password" name="confirm_password"><br>
                    <span id="confirm_password_error" class="error"></span>
                </div>

                <button type="submit">Submit</button>
            </form>
            <a href="#" onclick="showLogin()">Login</a>
        </div>
    </div>

    <script src="validation.js"></script>
</body>

</html>-->
<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Sign Up</title>
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <div class="container" id="Container">
        <div class="form-container login">
            <form action="fuse.php" method="post">
                <p class="title">CLASSROOM SCHEDULING SYSTEM</p>
                <div>
                    <input id="email_login" name="email_login" type="email" placeholder="Email" required value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?>">
                    <!-- <span id="password_login_error" name="password_login_error" class="error"></span> -->
                </div>
                <div>
                    <input id="password_login" name="password_login" type="password" placeholder="Password" required>

                </div>
                <button type="submit" name="login_submit">Log In</button>

                <p class="text">Don't have an account? <span id="Register">Sign Up</span></p>
            </form>
        </div>

        <div class="form-container sign-up">
            <form action="fuse.php" method="post">
                <p class="title">CLASSROOM SCHEDULING SYSTEM</p>

                <input name="username" type="text" placeholder="Name" required value="<?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : ''; ?>">
                <div>
                    <input id="email_signup" name="email_signup" type="email" placeholder="Email" required value="<?php echo isset($_SESSION['email_signup']) ? htmlspecialchars($_SESSION['email_signup']) : ''; ?>">
                    <!-- <span id="username_error" class="error"></span> -->
                </div>
                <div>
                    <input id="password_signup" name="password_signup" type="password" placeholder="Password"
                        title="Password must contain at least one uppercase letter, one lowercase letter, one digit, and one special character (!@#$%^&*). Minimum length: 8 characters"
                        required value="<?php echo isset($_SESSION['password_signup']) ? htmlspecialchars($_SESSION['password_signup']) : ''; ?>">
                    <!-- <span id="password_error" class="error"></span> -->

                </div>
                <div>
                    <input name="confirm_password" id="confirm_password" type="password" placeholder="Confirm Password"
                        required value="<?php echo isset($_SESSION['confirm_password']) ? htmlspecialchars($_SESSION['confirm_password']) : ''; ?>">
                    <!-- <span id="confirm_password_error" class="error"></span> -->
                </div>


                <button type="submit" name="signup_submit">Sign Up</button>

                <p class="text">Already have an account? <span id="Login">Log in</span></p>
            </form>
        </div>

        <!--Toggle Containers-->
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-right">
                    <img src="media/index/login-aclc-building.png">
                </div>
                <div class="toggle-panel toggle-left">
                    <img src="media/index/signup-aclc-building.png">
                </div>
            </div>
        </div>
    </div>

    <script>
        const container = document.getElementById('Container');
        const registerBtn = document.getElementById('Register');
        const loginBtn = document.getElementById('Login');

        registerBtn.addEventListener('click', (e) => {
            e.preventDefault();
            container.classList.add("active");
        });

        loginBtn.addEventListener('click', (e) => {
            e.preventDefault();
            container.classList.remove("active");
        });

        if (window.location.search.includes('register')) {
            container.classList.add("active");
        }
    </script>
</body>

</html>