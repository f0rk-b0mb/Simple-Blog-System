<?php
require_once 'connect.php';
require_once 'header.php';
require_once 'security.php';

// Überprüfen, ob eine Kategorie-ID übergeben wurde
if (isset($_GET['id'])) {
    $categoryID = mysqli_real_escape_string($dbcon, $_GET['id']);

    // Überprüfen, ob die Kategorie existiert
    $checkSql = "SELECT * FROM category WHERE id = '$categoryID'";
    $checkResult = mysqli_query($dbcon, $checkSql);

    if (mysqli_num_rows($checkResult) > 0) {
        // Kategorie aus der Datenbank löschen
        $deleteSql = "DELETE FROM category WHERE id = '$categoryID'";

        if (mysqli_query($dbcon, $deleteSql)) {
            echo "<div class='w3-panel w3-green w3-display-container'>Category deleted successfully.</div>";
        } else {
            echo "<div class='w3-panel w3-pale-red w3-display-container'>Error deleting category.</div>";
        }
    } else {
        echo "<div class='w3-panel w3-pale-red w3-display-container'>Category does not exist.</div>";
    }
} else {
    echo "<div class='w3-panel w3-pale-red w3-display-container'>Invalid category ID.</div>";
}

include("footer.php");
?>

