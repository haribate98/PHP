<?php
session_start();

$ime = htmlspecialchars($_SESSION['username']);

setcookie("username", $ime, time() + 60 * 60 * 24 * 365, "/", "", false, true);

?>


<html>

<head>
    <meta charset="Utf-8" />
    <title> Podaci o studentima</title>
    <link rel="stylesheet" href="styleIndex.css">
</head>

<body style="background-color: #83677B">

    <h2 style="font-family: Arial, Helvetica, sans-serif; color:white">
        Unos-azuriranje podataka o studentima
    </h2>

    <p align="left" style="background-color: gray; color: white; font-weight: bold;">
        Ulogovani user:
        <?php echo $_SESSION['username'] ?> </p>
    <form method="post" action="index.php" enctype="multipart/form-data">
        <?php


        if (isset($_SESSION['page_count'])) {
            $_SESSION['page_count'] += 1;
        } else {
            $_SESSION['page_count'] = 1;
        }
        echo 'Pristupili ste:' . $_SESSION['page_count'] . " put ovom sajtu <br/>";

        //Konektovanje na MySQL
        if (!($DB = mysqli_connect("localhost", "user", "root", "StudentiDB")))
            die("Ne moze da se izvrsi konekcija na MYSQL server");
        mysqli_set_charset($DB, "utf8");
        //deklarisanje i incijalizovanje promeljivih
        $BRIND = "";
        $PREZIME = "";
        $IME = "";
        $STATUS = "";
        $SIFRA = "";
        //ako je korisnik kliknuo dugme "Trazi po prezimenu"
        if (isset($_POST['TRAZI'])) {
            $upit = "SELECT * FROM student WHERE prezime LIKE '" . $_POST['PREZIME'] . "'";
            if (!($rezultat = mysqli_query($DB, $upit))) {
                print("Ne moze se izbrsiti upit! <br/>");
                die(mysqli_error($DB));
            }
            if (!($RED = mysqli_fetch_assoc($rezultat))) {
                print("Nema trazenog studenta");
                die(mysqli_error($DB));
            } else {
                $PREZIME = htmlspecialchars($RED['prezime']);
                $IME = htmlspecialchars($RED['ime']);
                $BRIND = htmlspecialchars($RED['broj_indeksa']);
                $SIFRA = htmlspecialchars($RED['sifra']);
                $STATUS = htmlspecialchars($RED['status']);
            }
        } elseif (isset($_POST['TRAZI2'])) {
            //Ako je korisnik kliknuo na dugme "Trazi po broju indeksa"

            $upit2 = "SELECT * FROM student WHERE broj_indeksa LIKE '" . $_POST['BRIND'] . "'";
            if (!($rezultat2 = mysqli_query($DB, $upit2))) {
                print("Ne moze se izvrsiti upit! <br/>");
                die(mysqli_error($DB));
            }
            if (!($RED = mysqli_fetch_assoc($rezultat2))) {
                print("Nema trazenog studenta! <br/>");
            } else {
                $PREZIME = htmlspecialchars($RED['prezime']);
                $IME = htmlspecialchars($RED['ime']);
                $BRIND = htmlspecialchars($RED['broj_indeksa']);
                $SIFRA = htmlspecialchars($RED['sifra']);
                $STATUS = htmlspecialchars($RED['status']);

                echo "<p><img src=\"photos/{$BRIND}\" width=\"400\" alt=\"Photo\" /></p>";
            }
        } elseif (isset($_POST['DODAJ'])) {
            //Ako je korisnik kliknuo na dugme "DODAJ"

            if ((!$_POST['PREZIME']) || (!$_POST['IME']) || (!$_POST['STATUS']) || (!$_POST['BRIND']) || (!$_POST['SIFRA'])) {
                echo "Mora biti uneto prezime, ime, broj indeksa, status i sifra!";
            } else {
                $PREZIME = htmlspecialchars($_POST['PREZIME']);
                $IME = htmlspecialchars($_POST['IME']);
                $BRIND = htmlspecialchars($_POST['BRIND']);
                $SIFRA = htmlspecialchars($_POST['SIFRA']);
                $STATUS = htmlspecialchars($_POST['STATUS']);
                $upitdodaj = "INSERT INTO student(prezime, ime, broj_indeksa, status, sifra) VALUES('{$PREZIME}', '{$IME}',  '{$BRIND}', '{$STATUS}', '{$SIFRA}')";
                if (!($rezultatD = mysqli_query($DB, $upitdodaj))) {
                    print("Ne moze se izvrsiti upit! <br/>");
                    die(mysqli_error($DB));
                }
                //stavljanje broja indeksa za ime fajla koji se sacuva, kad bi trazio po broju indeksa, da pokaze odma sliku
                if (isset($_FILES["photo"]) and $_FILES["photo"]["error"] == 0) {
                    if ($_FILES["photo"]["type"] != "image/jpeg") {
                        echo "<p>samo JPEG fotke!</p>";
                    } elseif (!move_uploaded_file($_FILES["photo"]["tmp_name"], "photos/" . basename($_POST['BRIND']))) {
                        echo "<p>Ima problema sa aploadanjem fajla.</p>" . $_FILES["photo"]["error"];
                    }
                }
                $MESSAGE = "SLOG DODAT";
            }
        } elseif (isset($_POST['AZURIRAJ'])) {
            //Ako je korisnik kliknuo na dugme "AZURIRAJ"
            if ((!$_POST['PREZIME']) || (!$_POST['IME']) || (!$_POST['STATUS']) || (!$_POST['BRIND']) || (!$_POST['SIFRA'])) {
                echo "Mora biti uneto prezime, ime, broj indeksa, status i sifra!";
            } else {
                $PREZIME = htmlspecialchars($_POST['PREZIME']);
                $IME = htmlspecialchars($_POST['IME']);
                $BRIND = htmlspecialchars($_POST['BRIND']);
                $SIFRA = htmlspecialchars($_POST['SIFRA']);
                $STATUS = htmlspecialchars($_POST['STATUS']);
                $upitazuriraj = "UPDATE student SET prezime = '{$PREZIME}', ime = '{$IME}', sifra = '{$SIFRA}', status = '{$STATUS}' WHERE broj_indeksa = '{$BRIND}'";
                if (!($rezultatz = mysqli_query($DB, $upitazuriraj))) {
                    print("Ne moze se izvrsiti AZURIRANJE u tabeli student! <br/>");
                    die(mysqli_error($DB));
                }
                $MESSAGE = "SLOG AZURIRAN";
            }
            $PREZIME = htmlspecialchars($_POST['PREZIME']);
            $IME = htmlspecialchars($_POST['IME']);
            $BRIND = htmlspecialchars($_POST['BRIND']);
            $SIFRA = htmlspecialchars($_POST['SIFRA']);
            $STATUS = htmlspecialchars($_POST['STATUS']);
        } elseif (isset($_POST['OBRISI'])) {
            //Ako je korisnik kliknuo na dugme "OBRISI"
            $upitBrisanje = "DELETE FROM student WHERE broj_indeksa = '{$BRIND}'";

            if (!($rezultatBrisanja = mysqli_query($DB, $upitBrisanje))) {
                print("Ne moze se izbrisiti brisanje <br/>");
                die(mysqli_error($DB));
            }

            //Brisanje selektovanih podataka sa ekrana
            //za slog koji je obrisan

            $PREZIME = "";
            $IME = "";
            $SIFRA =  "";
            $STATUS = "";
            $BRIND = "";
            $MESSAGE = "SLOG OBRISAN";
        }

        $PREZIME = trim($PREZIME);
        $IME = trim($IME);
        $SIFRA = trim($SIFRA);
        $STATUS = trim($STATUS);
        $BRIND = trim($BRIND);
        ?>

        <table>
            <col span="1" align="center">
            <tr>
                <td style="font-weight: bold;color:white"> Broj indeksa:</td>
                <td><input name="BRIND" type="text" size="7" value="<?php echo $BRIND ?>" /></td>
            </tr>
            <tr>
                <td style="font-weight: bold;color:white">Prezime:</td>
                <td><input name="PREZIME" type="text" size="30" value="<?php echo $PREZIME ?>" /></td>
            </tr>
            <tr>
                <td style="font-weight: bold;color:white">Ime:</td>
                <td><input name="IME" type="text" size="30" value="<?php echo $IME ?>" /></td>
            </tr>
            <tr>
                <td style="font-weight: bold;color:white">Status:</td>
                <td>
                    <?php if ($STATUS == "S") { ?>
                        <label>
                            B <input name="STATUS" type="radio" value="B" />
                        </label>
                        <label>
                            S <input name="STATUS" type="radio" value="S" checked="checked" />
                        </label>
                    <?php } else { ?>
                        <label>
                            B <input name="STATUS" type="radio" value="B" checked="checked" />
                        </label>
                        <label>
                            S <input name="STATUS" type="radio" value="S" />
                        </label>
                    <?php } ?>
                </td>
                <td style="font-weight: bold;color:white">SIFRA SMERA</td>
                <td><input name="SIFRA" type="text" size="5" value="<?php echo $SIFRA ?>" /></td>
            </tr>
            <tr>

                <div>
                    <input type="hidden" name="MAX_FILE_SIZE" value="900000" />
                    <input type="text" name="" id="" value="" />
                    <label> </label>
                    <input type="file" name="photo" id="photo" value="" />

                    <input type="submit" name="showPhoto" value="Show Photo" />
                </div>

            </tr>
        </table>
        <br>
        <input type="submit" name="DODAJ" value="DODAJ" style="background-color: #C3073F; color: white; font-weight: bold;" />
        <input type="submit" name="AZURIRAJ" value="AZURIRAJ" style="background-color: #C3073F; color: white; font-weight: bold;" />
        <input type="submit" name="OBRISI" value="OBRISI" style="background-color: #C3073F; color: white; font-weight: bold;" />
        <input type="submit" name="TRAZI" value="TRAZI PO PREZIMENU" style="background-color: #C3073F; color: white; font-weight: bold;" />
        <input type="submit" name="TRAZI2" value="TRAZI PO BROJU INDEKSA" style="background-color: #C3073F; color: white; font-weight: bold;" />

        <!-- <input type="submit" name="IZLAZ" value="LOG OUT" style="background-color: #C3073F; color: white; font-weight: bold;" /> -->

        <hr>
        <?php
        if (isset($MESSAGE)) {

            echo "<br> <br> $MESSAGE";
        }
        ?>
    </form>z

    <form method="post" action="login.php">
        <a href="login.php" name="logout" style="background-color: blue; color: white; font-weight: bold;">ODJAVLJIVANJE</a>
    </form>

    <?php
    if (isset($_FILES["photo"]) and $_FILES["photo"]["error"] == 0) {
        if ($_FILES["photo"]["type"] != "image/jpeg") {
            echo "<p>samo JPEG fotke!</p>";
        } elseif (!move_uploaded_file($_FILES["photo"]["tmp_name"], "photos/" . basename($_POST['BRIND']))) {
            echo "<p>Ima problema sa aploadanjem fajla.</p>" . $_FILES["photo"]["error"];
        } else {
            echo "Hvala na slici uspesno je aploadovana";
    ?>
            <p><img src="photos/<?php echo 55 / 13 ?>" alt="Photo" /></p>
        <?php
        }
    } else {
        switch ($_FILES["photo"]["error"]) {
            case 1:
                $message = "Greska:Prevelika slika za server";
                break;
            case 2:
                $message = "Greska:Prevelika slika za skriptu";
                break;
            case 3:
                $message = "Greska:Verovatno je slaba konekcija sa internetom, pokusajte manji fajl";
                break;
            case 4:
                $message = "Greska:Niste izabrali fajl za ucitavanje";
                break;

            default:
                $message = "Kontaktirajte haribate98@gmail.com za pomoc";
        }
        echo "<p>Ima problem sa ucitavanjem: $message</p>";
    }

    if (isset($_POST['logout'])) {
        ?>
        <div>Odjavljivanjeeee... <?php echo $ime ?></div>
    <?php
        session_destroy();
        session_unset();
    }
    ?>


</body>

</html>