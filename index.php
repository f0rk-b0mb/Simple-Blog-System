<?php
require_once 'connect.php';
require_once 'header.php';

// Seitennummer und Seitengröße
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$rowsperpage = PAGINATION;
$offset = ($page - 1) * $rowsperpage;

// Gesamtzahl der Beiträge ermitteln
$sql = "SELECT COUNT(*) FROM posts";
$result = mysqli_query($dbcon, $sql);
$r = mysqli_fetch_row($result);
$numrows = $r[0];

$totalpages = ceil($numrows / $rowsperpage);

// Beiträge abrufen
$stmt = $dbcon->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT ?, ?");
$stmt->bind_param("ii", $offset, $rowsperpage);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Mein Blog</title>
    <style>
        /* Hier kommen deine CSS-Stile hin */
    </style>
</head>
<body>
    <h1>Mein Blog</h1>

    <!-- Beiträge anzeigen -->
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <?php
            $id = htmlspecialchars($row['id'], ENT_QUOTES);
            $title = htmlspecialchars($row['title'], ENT_QUOTES);
            $des = htmlspecialchars(substr(strip_tags($row['description']), 0, 100), ENT_QUOTES);
            $slug = htmlspecialchars($row['slug'], ENT_QUOTES);
            $time = htmlspecialchars($row['date'], ENT_QUOTES);
            $permalink = "view.php?id=" . $id;
            ?>

            <div class="w3-panel w3-light-grey w3-card-4">
                <h3><a href="<?= $permalink ?>"><?= $title ?></a></h3>
                <p><?= $des ?></p>
                <div class="w3-text-grey">
                    <a href="<?= $permalink ?>">Read more...</a>
                </div>
                <div class="w3-text-grey"><?= $time ?></div>
            </div>
        <?php endwhile; ?>

        <p>
            <div class="w3-bar w3-center">
                <?php if ($page > 1): ?>
                    <a href="?page=1">&laquo;</a>
                    <?php $prevpage = $page - 1; ?>
                    <a href="?page=<?= $prevpage ?>" class="w3-btn"><</a>
                <?php endif; ?>

                <?php
                $range = 5;
                for ($x = $page - $range; $x < ($page + $range) + 1; $x++):
                    if (($x > 0) && ($x <= $totalpages)):
                        if ($x == $page): ?>
                            <div class="w3-grey w3-button"><?= $x ?></div>
                        <?php else: ?>
                            <a href="?page=<?= $x ?>" class="w3-button w3-border"><?= $x ?></a>
                        <?php endif;
                    endif;
                endfor;
                ?>

                <?php if ($page != $totalpages): ?>
                    <?php $nextpage = $page + 1; ?>
                    <a href="?page=<?= $nextpage ?>" class="w3-button">></a>
                    <a href="?page=<?= $totalpages ?>" class="w3-btn">&raquo;</a>
                <?php endif; ?>
            </div>
        </p>

    <?php else: ?>
        <div class="w3-panel w3-pale-red w3-card-2 w3-border w3-round">No post yet!</div>
    <?php endif; ?>

    <?php include("categories.php"); ?>
    <?php include("footer.php");
