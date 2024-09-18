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
                <li><a href="Register.html">Register/Login</a></li>
            </ul>
        </div>
    </nav>
    
    <div class="register">
        <center><h1>Login</h1></center>
        <form id="log-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-input">
                <input type="text" name="uname" id="uname" class="input" placeholder="User Name">
                <div class="error"></div>
            </div>
            <div class="form-input">
                <input type="password" name="pwd" id="pwd" class="input" placeholder="Password">
                <div class="error"></div>
            </div>
            <center><button type="submit">Login</button></center> <br>
            don't have an account? <a href="Register.php">Register</a> now
        </form>
    </div>

    <script>
      document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('log-form');
    const uname = document.getElementById('uname');
    const pwd = document.getElementById('pwd');

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        validateInputs();
    });

    function setError(element, message) {
        const inputControl = element.parentElement;
        const errorDisplay = inputControl.querySelector('.error');
        errorDisplay.innerText = message;
        inputControl.classList.add('error');
    }

    function setSuccess(element) {
        const inputControl = element.parentElement;
        const errorDisplay = inputControl.querySelector('.error');
        errorDisplay.innerText = '';
        inputControl.classList.remove('error');
    }

    function validateInputs() {
        const unameValue = uname.value.trim();
        const pwdValue = pwd.value.trim();

        if (unameValue === '') {
            setError(uname, 'Username is required');
        } else {
            setSuccess(uname);
        }

        if (pwdValue === '') {
            setError(pwd, 'Password is required');
        } else {
            setSuccess(pwd);
        }

        if (unameValue !== '' && pwdValue !== '') {
            form.submit();
        }
    }
});
    </script>
    
    <?php
    session_start();
    include("config.php");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $uname = $_POST["uname"];
        $pwd = $_POST["pwd"];

        $sql = "SELECT * FROM users WHERE User_Name = ? AND Password = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $uname, $pwd);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) == 1) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $uname;
            header("Location: dashboard.php");
            exit;
        } else {
            echo '<script>alert("Invalid username or password.");</script>';
        }
    }
    ?>
</body>
</html>
