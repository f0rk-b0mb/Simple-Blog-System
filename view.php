<?php
require_once 'connect.php';
require_once 'header.php';

// Überprüfen, ob eine gültige ID übergeben wurde
$id = (int)$_GET['id'];
if ($id < 1) {
    header("location: $url_path");
    exit;
}

// Beitragsdaten abrufen
$stmt = $dbcon->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Überprüfen, ob ein gültiger Beitrag gefunden wurde
if ($result->num_rows === 0) {
    header("location: $url_path");
    exit;
}

$row = $result->fetch_assoc();

$id = $row['id'];
$title = $row['title'];
$description = $row['description'];
$author = $row['posted_by'];
$time = $row['date'];

?>

<div class="w3-container w3-grey w3-card-4">
    <h3><?php echo $title; ?></h3>
    <div class="w3-panel w3-leftbar w3-rightbar w3-border w3-light-grey w3-card-4">
        <?php echo $description; ?><br>
        <div class="w3-text-grey">
            Posted by: <?php echo $author; ?><br>
            <?php echo $time; ?>
        </div>
    </div>

    <?php if (isset($_SESSION['username'])): ?>
        <div class="w3-text-green">
            <a href="<?=$url_path?>edit.php?id=<?php echo $row['id']; ?>">[Edit]</a>
        </div>
        <div class="w3-text-red">
            <a href="<?=$url_path?>del.php?id=<?php echo $row['id']; ?>"
               onclick="return confirm('Are you sure you want to delete this post?'); ">[Delete]</a>
        </div>
    <?php endif; ?>

</div>

<?php include("footer.php"); ?>

