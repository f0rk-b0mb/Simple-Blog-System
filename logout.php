<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("location: index.php");
    exit; // Beende die Ausführung nach der Umleitung
} else {
    session_destroy();
    header("location: login.php");
    exit; // Beende die Ausführung nach der Umleitung
}
?>
