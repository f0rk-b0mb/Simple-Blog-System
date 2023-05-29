<?php
require_once 'connect.php';
require_once 'header.php';

$error = '';

if (isset($_POST['log'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validierung der Eingabe
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        $stmt = $dbcon->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($result->num_rows === 1 && password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username;
            header("Location: admin.php");
            exit;
        } else {
            $error = 'Incorrect username or password.';
        }
    }
}
?>

<h2 class="w3-container w3-grey">Login</h2>

<?php if (!empty($error)): ?>
    <div class='w3-panel w3-pale-red w3-display-container'><?php echo $error; ?></div>
<?php endif; ?>

<form action="" method="POST" class="w3-container w3-padding">
    <label>Username</label>
    <input type="text" name="username" value="<?php echo isset($_POST['username']) ? htmlentities($_POST['username']) : ''; ?>" class="w3-input w3-border">
    <label>Password</label>
    <input type="password" name="password" class="w3-input w3-border">
    <p><input type="submit" name="log" value="Login" class="w3-button w3-grey"></p>
</form>

<?php include("footer.php"); ?>

