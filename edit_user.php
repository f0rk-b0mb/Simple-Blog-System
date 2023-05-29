<?php
require_once 'connect.php';
require_once 'header.php';
require_once 'security.php';

// Überprüfen, ob eine Benutzer-ID übergeben wurde
if (isset($_GET['id'])) {
    $userId = mysqli_real_escape_string($dbcon, $_GET['id']);

    // Benutzerdaten abrufen
    $sql = "SELECT * FROM admin WHERE id = '$userId'";
    $result = mysqli_query($dbcon, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $username = htmlspecialchars($row['username'], ENT_QUOTES);
        $email = htmlspecialchars($row['email'], ENT_QUOTES);
    } else {
        echo "<div class='w3-panel w3-pale-red w3-display-container'>User not found.</div>";
        include("footer.php");
        exit();
    }
} else {
    echo "<div class='w3-panel w3-pale-red w3-display-container'>Invalid request.</div>";
    include("footer.php");
    exit();
}

// Überprüfen, ob das Formular abgesendet wurde
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Eingaben aus dem Formular abrufen
    $newUsername = mysqli_real_escape_string($dbcon, $_POST['username']);
    $newEmail = mysqli_real_escape_string($dbcon, $_POST['email']);

    // Überprüfen, ob der Benutzername bereits verwendet wird
    $checkSql = "SELECT * FROM admin WHERE username = '$newUsername' AND id != '$userId'";
    $checkResult = mysqli_query($dbcon, $checkSql);

    if (mysqli_num_rows($checkResult) > 0) {
        echo "<div class='w3-panel w3-pale-red w3-display-container'>Username already exists. Please choose a different username.</div>";
    } else {
        // Benutzer aktualisieren
        $updateSql = "UPDATE admin SET username = '$newUsername', email = '$newEmail' WHERE id = '$userId'";

        if (mysqli_query($dbcon, $updateSql)) {
            echo "<div class='w3-panel w3-green w3-display-container'>User updated successfully.</div>";
            $username = $newUsername;
            $email = $newEmail;
        } else {
            echo "<div class='w3-panel w3-pale-red w3-display-container'>Error updating user.</div>";
        }
    }
}
?>

<h2 class="w3-container w3-grey w3-center">Edit User</h2>

<div class="w3-container">
    <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo $username; ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>

        <input type="submit" value="Save" class="w3-button w3-grey">
    </form>
</div>

<?php include("footer.php"); ?>
