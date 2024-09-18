<?php
if($_SERVER["REQUEST_METHOD"] == "POST") {
    include("config.php");
    
    $fname = $lname = $email = $phno = $uname = $pwd = "";

    $fname = trim($_POST["fname"]);
    $lname = trim($_POST["lname"]);
    $email = trim($_POST["email"]);
    $phno = trim($_POST["phno"]);
    $uname = trim($_POST["uname"]);
    $pwd = trim($_POST["pwd"]);

    $check_email_sql = "SELECT * FROM users WHERE Email_ID = ?";
    $check_email_stmt = mysqli_prepare($conn, $check_email_sql);
    mysqli_stmt_bind_param($check_email_stmt, "s", $email);
    mysqli_stmt_execute($check_email_stmt);
    mysqli_stmt_store_result($check_email_stmt);

    $check_phno_sql = "SELECT * FROM users WHERE Phone_no = ?";
    $check_phno_stmt = mysqli_prepare($conn, $check_phno_sql);
    mysqli_stmt_bind_param($check_phno_stmt, "s", $phno);
    mysqli_stmt_execute($check_phno_stmt);
    mysqli_stmt_store_result($check_phno_stmt);

    $check_uname_sql = "SELECT * FROM users WHERE User_Name = ?";
    $check_uname_stmt = mysqli_prepare($conn, $check_uname_sql);
    mysqli_stmt_bind_param($check_uname_stmt, "s", $uname);
    mysqli_stmt_execute($check_uname_stmt);
    mysqli_stmt_store_result($check_uname_stmt);

    if(mysqli_stmt_num_rows($check_email_stmt) > 0){
        echo '<script>alert("This email is already registered. Please use a different email.");</script>';
    } elseif(mysqli_stmt_num_rows($check_phno_stmt) > 0) {
        echo '<script>alert("This phone number is already registered. Please use a different phone number.");</script>';
    } elseif(mysqli_stmt_num_rows($check_uname_stmt) > 0) {
        echo '<script>alert("This username is already taken. Please choose a different username.");</script>';
    } else {
        $insert_sql = "INSERT INTO users (First_Name, Last_Name, Email_ID, Phone_no, User_Name, Password) VALUES (?, ?, ?, ?, ?, ?)";
        
        if($stmt = mysqli_prepare($conn, $insert_sql)){
            mysqli_stmt_bind_param($stmt, "sssiss", $param_fname, $param_lname, $param_email, $param_phno, $param_uname, $param_pwd);
            
            $param_fname = $fname;
            $param_lname = $lname;
            $param_email = $email;
            $param_phno = $phno;
            $param_uname = $uname;
            $param_pwd = $pwd;
            
            if(mysqli_stmt_execute($stmt)){
                header("location: Login.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
        
        mysqli_stmt_close($stmt);
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TODO List</title>
    <link rel="stylesheet" href="TODO.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="form.css">
</head>
<body>
    <nav>
        <div class="title">TODO List</div>
        <div class="navigation">
            <ul>
                <li><a href="Home.html" target="_self">Home</a></li>
                <li><a href="About.html">About</a></li>
                <li><a href="Contact.html" target="_self">Contact</a></li>
                <li><a href="#">Register/Login</a></li>
            </ul>
        </div>
    </nav>
    
    <div class="register">
        <center><h1>Registration</h1></center>
        <form id="reg-form" action="Register.php" method="post">
            <div class="form-input"> 
                <input type="text" name="fname" id="fname" class="input" placeholder="First Name">
                <div class="error"></div>
            </div>
            <div class="form-input">
                <input type="text" name="lname" id="lname" class="input" placeholder="Last Name">
                <div class="error"></div>
            </div>
            <div class="form-input">
                <input type="text" name="email" id="email" class="input" placeholder="Email ID">
                <div class="error"></div>
            </div>
            <div class="form-input">
                <input type="tel" name="phno" id="phno" class="input" placeholder="Mobile Number">
                <div class="error"></div>
            </div>
            <div class="form-input">
                <input type="text" name="uname" id="uname" class="input" placeholder="User Name">
                <div class="error"></div>
            </div>
            <div class="form-input">
                <input type="password" name="pwd" id="pwd" class="input" placeholder="Password">
                <div class="error"></div>
            </div>
            <div class="form-input">
                <input type="password" name="cpwd" id="cpwd" class="input" placeholder="Confirm Password">
                <div class="error"></div>
            </div>
            <center><button type="submit" >Register</button></center> <br>
            already a member? <a href="Login.php">Login</a> now
        </form>
    </div>
    
    <script>
        const form = document.getElementById('reg-form');
        const fname = document.getElementById('fname');
        const lname = document.getElementById('lname');
        const email = document.getElementById('email');
        const phno = document.getElementById('phno');
        const uname = document.getElementById('uname');
        const pwd = document.getElementById('pwd');
        const cpwd = document.getElementById('cpwd');
        form.addEventListener('submit', e => {
          e.preventDefault();
          validateInputs();
        });
        const setError = (element, message) => {
          const inputControl = element.parentElement;
          const errorDisplay = inputControl.querySelector('.error');
          errorDisplay.innerText = message;
          inputControl.classList.add('error');
          inputControl.classList.remove('success')
        }
        const setSuccess = element => {
          const inputControl = element.parentElement;
          const errorDisplay = inputControl.querySelector('.error');
          errorDisplay.innerText = '';
          inputControl.classList.add('success');
          inputControl.classList.remove('error');
        };
        const isValidEmail = email => {
          const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
          return re.test(String(email).toLowerCase());
        }
        const validatePhoneNumber = phoneNumber => {
          const cleanedPhoneNumber = phoneNumber.replace(/\D/g, '');
          if (!cleanedPhoneNumber) {
            return false;
          }
          return cleanedPhoneNumber.length === 10;
        };
        const validateInputs = () => {
          const fnameValue = fname.value.trim();
          const lnameValue = lname.value.trim();
          const emailValue = email.value.trim();
          const phnoValue = phno.value.trim();
          const unameValue = uname.value.trim();
          const pwdValue = pwd.value.trim();
          const cpwdValue = cpwd.value.trim();
          if (fnameValue === '') {
            setError(fname, 'First Name is required');
          } else {
            setSuccess(fname);
          }
          if (lnameValue === '') {
            setError(lname, 'Last Name is required');
          } else {
            setSuccess(lname);
          }
          if (emailValue === '') {
            setError(email, 'Email is required');
          } else if (!isValidEmail(emailValue)) {
            setError(email, 'Provide a valid email address');
          } else {
            setSuccess(email);
          }
          if (phnoValue === '') {
            setError(phno, 'Phone Number is required');
          } else if (!validatePhoneNumber(phnoValue)) {
            setError(phno, 'Provide a valid phone number');
          } else {
            setSuccess(phno);
          }
          if (unameValue === '') {
            setError(uname, 'Username is required');
          } else {
            setSuccess(uname);
          }
          if (pwdValue === '') {
            setError(pwd, 'Password is required');
          } else if (pwdValue.length < 8) {
            setError(pwd, 'Password must be at least 8 characters.')
          } else {
            setSuccess(pwd);
          }
          if (cpwdValue === '') {
            setError(cpwd, 'Confirm your password');
          } else if (cpwdValue !== pwdValue) {
            setError(cpwd, "Passwords don't match");
          } else {
            setSuccess(cpwd);
          }
          if (fnameValue !== '' && lnameValue !== '' && isValidEmail(emailValue) && validatePhoneNumber(phnoValue) && unameValue !== '' && pwdValue !== '' && pwdValue.length >= 8 && cpwdValue !== '' && cpwdValue === pwdValue) {
            form.submit();
          }
        };
      </script>
    </body>
</html>
