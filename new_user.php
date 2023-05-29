<?php
require_once 'connect.php';
require_once 'header.php';
require_once 'security.php';

// Überprüfen, ob das Formular abgeschickt wurde
if (isset($_POST['submit'])) {
    // Formulardaten abrufen
    $username = mysqli_real_escape_string($dbcon, $_POST['username']);
    $email = mysqli_real_escape_string($dbcon, $_POST['email']);
    $password = mysqli_real_escape_string($dbcon, $_POST['password']);
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Passwort hashen

    // Überprüfen, ob der Benutzername bereits existiert
    $checkSql = "SELECT * FROM admin WHERE username = '$username'";
    $checkResult = mysqli_query($dbcon, $checkSql);

    if (mysqli_num_rows($checkResult) > 0) {
        echo "<div class='w3-panel w3-pale-red w3-display-container'>Username already exists.</div>";
    } else {
        // Benutzerdaten in die Datenbank einfügen
        $insertSql = "INSERT INTO admin (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";

        if (mysqli_query($dbcon, $insertSql)) {
            echo "<div class='w3-panel w3-green w3-display-container'>User created successfully.</div>";
        } else {
            echo "<div class='w3-panel w3-pale-red w3-display-container'>Error creating user.</div>";
        }
    }
}
?>

<h2 class="w3-container w3-grey w3-center">Create New User</h2>

<form class="w3-container" method="POST" action="">
    <p>
        <label class="w3-text-grey">Username</label>
        <input class="w3-input w3-border w3-grey" type="text" name="username" required>
    </p>
    <p>
        <label class="w3-text-grey">Email</label>
        <input class="w3-input w3-border w3-grey" type="email" name="email" required>
    </p>
    <p>
        <label class="w3-text-grey">Password</label>
        <input class="w3-input w3-border w3-grey" type="password" name="password" required>
    </p>
    <p>
        <button class="w3-btn w3-grey" type="submit" name="submit">Create User</button>
    </p>
</form>

<?php include("footer.php"); ?>

