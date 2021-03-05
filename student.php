<?php
class Student
{
    private $PREZIME, $IME, $BRIND, $SIFRA, $STATUS;

    function __construct($p, $i, $b, $si, $st)
    {
        $this->PREZIME = $p;
        $this->IME = $i;
        $this->BRIND = $b;
        $this->SIFRA = $si;
        $this->STATUS = $st;
    }

    public function getPrezime()
    {
        return $this->PREZIME;
    }
    public function getIME()
    {
        return $this->IME;
    }
    public function getBrind()
    {
        return $this->BRIND;
    }
    public function getSifra()
    {
        return $this->SIFRA;
    }
    public function getStatus()
    {
        return $this->STATUS;
    }

    public static function login($imee, $sif)
    {
        $DB = mysqli_connect("localhost", "user", "root", "StudentiDB");
        $upit = "SELECT * FROM student WHERE ime LIKE '" . $imee . "' AND sifra LIKE '".$sif."'";
        if (!($rezultat = mysqli_query($DB, $upit))) {
            print("Ne moze se izvrsiti upit! <br/>");
            die(mysqli_error($DB));
        }
        if (!($user = mysqli_fetch_assoc($rezultat))) {
            print("Nisu tacni podaci");
            die(mysqli_error($DB));
        } else {
            return $user;
        }
    }
    

    public static function register($prz, $im, $bri, $stat, $sifr)
    {
        $DB = mysqli_connect("localhost", "user", "root", "StudentiDB");
        $upit = "INSERT INTO student(prezime, ime, broj_indeksa, status, sifra) VALUES('" . $prz . "', '" . $im . "',  '" . $bri . "', '" . $stat . "', '" . $sifr . "')";
        if (!($rezultat = mysqli_query($DB, $upit))) {
            print("Niste uneli dobre podatke <br/>");
            die(mysqli_error($DB));
            return false;
        } else {
            return true;
        }
    }
}
