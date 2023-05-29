<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate database connection information
    $db_host = $_POST['db_host'];
    $db_user = $_POST['db_user'];
    $db_pass = $_POST['db_pass'];
    $db_name = $_POST['db_name'];

    $dbcon = @mysqli_connect($db_host, $db_user, $db_pass);

    if (!$dbcon) {
        die("Failed to connect to the database. Please check your database connection information and try again.");
    }

    if (!mysqli_select_db($dbcon, $db_name)) {
        die("Failed to select the database. Please make sure the database exists and the specified user has access to it.");
    }

     // Create the connect.php file and write the database connection details
    	$connect_content = "<?php\n\n";
    	$connect_content .= "ob_start();\n";
    	$connect_content .= "session_start();\n\n";
        $connect_content .= "\$db_host = \"" . $db_host . "\";\n";
        $connect_content .= "\$db_user = \"" . $db_user . "\";\n";
        $connect_content .= "\$db_pass = \"" . $db_pass . "\";\n";
        $connect_content .= "\$db_name = \"" . $db_name . "\";\n\n";
        $connect_content .= "\$dbcon = mysqli_connect(\$db_host, \$db_user, \$db_pass, \$db_name);\n\n";
        $connect_content .= "if (mysqli_connect_errno()) {\n";
        $connect_content .= "    die(\"Failed to connect to MySQL: \" . mysqli_connect_error());\n";
        $connect_content .= "}\n\n";
        $connect_content .= "?>";

        $connect_file = fopen("connect.php", "w");
        fwrite($connect_file, $connect_content);
        fclose($connect_file);

    // Create posts table
    $sql = "CREATE TABLE IF NOT EXISTS posts (
        id INT PRIMARY KEY AUTO_INCREMENT,
        title VARCHAR(255) NOT NULL,
        content TEXT,
        date DATETIME DEFAULT CURRENT_TIMESTAMP,
        slug VARCHAR(255) NOT NULL
    )";
    mysqli_query($dbcon, $sql);

    // Create category table
    $sql = "CREATE TABLE IF NOT EXISTS category (
        id INT PRIMARY KEY AUTO_INCREMENT,
        catname VARCHAR(255) NOT NULL,
        description TEXT
    )";
    mysqli_query($dbcon, $sql);

    // Create admin table
    $sql = "CREATE TABLE IF NOT EXISTS admin (
        id INT PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL
    )";
    mysqli_query($dbcon, $sql);

    // Create admin_header table
    $sql = "CREATE TABLE IF NOT EXISTS admin_header (
        id INT PRIMARY KEY AUTO_INCREMENT,
        content TEXT
    )";
    mysqli_query($dbcon, $sql);

    // Insert first user into admin table
    $adminUsername = $_POST['admin_username'];
    $adminPassword = $_POST['admin_password'];
    $adminEmail = $_POST['admin_email'];
    $hashedPassword = password_hash($adminPassword, PASSWORD_DEFAULT);
    $sql = "INSERT INTO admin (username, password, email) VALUES ('$adminUsername', '$hashedPassword', '$adminEmail')";
    mysqli_query($dbcon, $sql);

    echo "Installation completed successfully!";

    // Redirect to the website
    header("Location: login.php");
    exit();

} else {
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <title>Installation</title>
</head>
<body>
    <h2>Installation</h2>
    <form method="POST">
        <p>
            <label for="db_host">Database Host:</label>
            <input type="text" name="db_host" required>
        </p>
        <p>
            <label for="db_user">Database User:</label>
            <input type="text" name="db_user" required>
        </p>
        <p>
            <label for="db_pass">Database Password:</label>
            <input type="password" name="db_pass">
        </p>
        <p>
            <label for="db_name">Database Name:</label>
            <input type="text" name="db_name" required>
        </p>
        <h3>First User</h3>
        <p>
            <label for="admin_username">Username:</label>
            <input type="text" name="admin_username" required>
        </p>
        <p>
            <label for="admin_password">Password:</label>
            <input type="password" name="admin_password" required>
        </p>
        <p>
            <label for="admin_email">Email:</label>
            <input type="email" name="admin_email" required>
        </p>
        <input type="submit" value="Install">
    </form>
</body>
</html>
<?php } ?>
