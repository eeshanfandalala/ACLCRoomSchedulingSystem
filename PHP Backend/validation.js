//Sign Up validation
function signupValidation() {
    var username = document.getElementById('username').value;
    var password = document.getElementById('password').value;
    var confirm_password = document.getElementById('confirm_password').value;
    var username_error = document.getElementById('username_error');
    var password_error = document.getElementById('password_error');
    var confirm_password_error = document.getElementById('confirm_password_error');
    var isValid = true;

    username_error.textContent = '';
    password_error.textContent = '';
    confirm_password_error.textContent = '';

    if (username.trim() === "") {
        username_error.textContent = "Enter Username";
        isValid = false;
    }

    if (password.trim() === "") {
        password_error.textContent = "Enter Password.";
        isValid = false;

    } else if (!(/[A-Z]/.test(password)) || !(/[a-z]/.test(password)) || !(/[!@#$%^&*(),.?":{}|<>]/.test(password))) {
        password_error.textContent = "Password must contain at least one Uppercase, Lowercase, and a Special Character";
        isValid = false;
        console.log(password_error);

    } else if (password.length < 8) {
        password_error.textContent = "Password must be at least 8 characters long.";
        isValid = false;
    }

    if (confirm_password.trim() === "") {
        confirm_password_error.textContent = "Please Confirm Your Password.";
        isValid = false;
    } else if (password !== confirm_password) {
        confirm_password_error.textContent = 'Passwords do not match'
        isValid = false;
    }


    return isValid;


}


//Log in Validation
function loginValidation(){
    var username_login = document.getElementById('username_login').value;
    var password_login = document.getElementById('password_login').value;
    var username_login_error = document.getElementById('username_login_error');

    var isValid = true;

    username_login_error.textContent = '';
    password_login_error.textContent = '';

    if (username_login.trim() === "") {
        username_login_error.textContent = "Enter Username.";
        isValid = false;
    }

    if (password_login.trim() === "") {
        password_login_error.textContent = "Enter Password.";
        isValid = false;

    }

    return isValid;
}

// function forgotPassword() {
//     var username_forgot = document.getElementById('username_login').value;
//     // var email = document.getElementById('email').value;/
//     var username_login_error = document.getElementById('username_login_error');
//     // var email_forgot_error = document.getElementById('email_forgot_error');
//     var isValid = true;

//     username_login_error.textContent = '';

//     if (username_forgot.trim() === "") {
//         username_login_error.textContent = "Enter Username.";
//         isValid = false;
//     }

//     return isValid;

// }

