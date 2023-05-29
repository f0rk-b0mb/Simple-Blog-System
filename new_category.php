<?php
require_once 'connect.php';
require_once 'header.php';
require_once 'security.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $catname = mysqli_real_escape_string($dbcon, $_POST['catname']);
    $description = mysqli_real_escape_string($dbcon, $_POST['description']);

    // Überprüfung, ob Kategorie bereits existiert
    $checkSql = "SELECT * FROM category WHERE catname = '$catname'";
    $checkResult = mysqli_query($dbcon, $checkSql);

    if (mysqli_num_rows($checkResult) > 0) {
        echo "<div class='w3-panel w3-pale-red w3-display-container'>Category already exists.</div>";
    } else {
        $insertSql = "INSERT INTO category (catname, description) VALUES ('$catname', '$description')";
        if (mysqli_query($dbcon, $insertSql)) {
            echo "<div class='w3-panel w3-green w3-display-container'>Category added successfully.</div>";
        } else {
            echo "<div class='w3-panel w3-pale-red w3-display-container'>Error adding category.</div>";
        }
    }
}

?>

<h2 class="w3-container w3-grey">Create New Category</h2>

<form action="" method="POST" class="w3-container w3-padding">
    <label>Name:</label>
    <input type="text" name="catname" class="w3-input w3-border">
    <label>Description:</label>
    <input type="text" name="description" class="w3-input w3-border">
    <p><input type="submit" value="Add Category" class="w3-button w3-grey"></p>
</form>

<?php
include("footer.php");
