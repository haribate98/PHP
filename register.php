<?php
session_start();

require_once('student.php');

$message = '';
if (isset($_POST['IME']) && isset($_POST['PREZIME']) && $_POST['STATUS'] && $_POST['BRIND'] && $_POST['SIFRA']) {
    $ime = $_POST['IME'];
    $prezime = $_POST['PREZIME'];
    $status = $_POST['STATUS'];
    $sifra = $_POST['SIFRA'];
    $brind = $_POST['BRIND'];
    if (Student::register($prezime, $ime, $brind, $status, $sifra)) {
        $message = 'Uspešno ste se registrovali!';
        header('Location: '. 'login.php');
    } else {
        $message = 'Već postoji taj korisnik! Promenite korisničko ime/email!';
    }
} else {
    $message = 'Popunite sva polja!';
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
                <label>IME</label>
                <input type="text" name="IME" pattern="[a-zA-Z0-9]+" required />
            </div>
            <div class="form-element">
                <label>PREZIME</label>
                <input type="text" name="PREZIME" pattern="[a-zA-Z0-9]+" required />
            </div>
            <div class="form-element">
                <label>Broj Indeksa</label>
                <input type="text" name="BRIND" pattern="[a-zA-Z0-9]+" required />
            </div>
            <div class="form-element">
                <label>SIFRA</label>
                <input type="text" name="SIFRA" required />
            </div>
            <div class="form-element">
                <label>STATUS</label>
                <input type="text" name="STATUS" required />
            </div>
            <button type="submit" name="login" value="Login">Register</button>
        </form>
    </body>
</center>

</html>