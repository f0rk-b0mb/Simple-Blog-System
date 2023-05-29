<?php
require_once 'connect.php';
require_once 'header.php';
require_once 'security.php';

// Überprüfen, ob eine Benutzer-ID übergeben wurde
if (isset($_GET['id'])) {
    $userId = mysqli_real_escape_string($dbcon, $_GET['id']);

    // Überprüfen, ob der zu löschende Benutzer existiert
    $checkSql = "SELECT * FROM admin WHERE id = '$userId'";
    $checkResult = mysqli_query($dbcon, $checkSql);

    if (mysqli_num_rows($checkResult) > 0) {
        // Benutzer löschen
        $deleteSql = "DELETE FROM admin WHERE id = '$userId'";

        if (mysqli_query($dbcon, $deleteSql)) {
            echo "<div class='w3-panel w3-green w3-display-container'>User deleted successfully.</div>";
        } else {
            echo "<div class='w3-panel w3-pale-red w3-display-container'>Error deleting user.</div>";
        }
    } else {
        echo "<div class='w3-panel w3-pale-red w3-display-container'>User does not exist.</div>";
    }
} else {
    echo "<div class='w3-panel w3-pale-red w3-display-container'>Invalid request.</div>";
}

include("footer.php");
?>
