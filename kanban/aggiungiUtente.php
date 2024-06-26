<?php
session_start();
if (isset($_SESSION['autenticato']) && $_SESSION['autenticato'] === true) {
    $username = $_COOKIE['username'];
} else {
    header("Location: ../login/login.php");
    exit;
}

require_once '../config.php';

$connection = @mysqli_connect(host, username, password, db_name);
if (!$connection) {
    die("Connessione al database fallita: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $nome = $_POST["nome"];
    $cognome = $_POST["cognome"];
    $password = $_POST["password"];

    $controlloDuplicati = "SELECT username FROM utenti WHERE username = '$username'";
    $risultatoDuplicati = mysqli_query($connection, $controlloDuplicati);

    if (mysqli_num_rows($risultatoDuplicati) > 0) {
        while ($row = mysqli_fetch_assoc($risultatoDuplicati)) {
            ?>
            <h4 class="error">Username già esistente.</h4>
            <?php
        }
    } else {
        $registraUtente = "INSERT INTO utenti (username, nome, cognome, password) VALUES ('$username', '$nome', '$cognome', '$password')";
        
        if (mysqli_query($connection, $registraUtente)) {
            header('Location: utenti.php');
        } else {
            ?>
            <h4 class="error">C'è stato un problema durante la registrazione.</h4>
            <?php
        }
    }
}
mysqli_close($connection);
