<?php
session_start();

require_once('student.php');

if (isset($_POST['username']) && isset($_POST['password'])) {
    $ime = $_POST['username'];
    $sifra = $_POST['password'];
    $user = Student::login($ime, $sifra);
    if ($user !== null) {
        $_SESSION['username'] = $ime;
        header('Location: ' . 'index.php');
    }
}

?>

<html>

<head>
    <link rel="stylesheet" href="style.css">
</head>
<center>

    <body style="background-color: #DAAD86">


        <form method="post">
            <div class="form-element">
                <label>Username</label>
                <input type="text" name="username" pattern="[a-zA-Z0-9]+" required />
            </div>
            <div class="form-element">
                <label>Password</label>
                <input type="password" name="password" required />
            </div>
            <button type="submit" name="login" value="Login">Login</button>
        </form>
        <form method="post" action="register.php">
        <div class="form-element">
                <label>Jos uvek niste registrovani?</label>
                <br>
                <button type="submit" name="Register" value="Register">Register</button>
            </div>
        </form>

    </body>
</center>

</html>