<?php
require_once 'connect.php';
require_once 'header.php';
?>

<!DOCTYPE html>
<html>
<head>
    <style>
        /* Hier kommen deine CSS-Stile hin */
        .main-content {
            margin-left: 10%;
            margin-right: 10%;
        }
    </style>
</head>
<body>



<?php
if (isset($_GET['id'])) {
    $category_id = mysqli_real_escape_string($dbcon, $_GET['id']);

    // Fetch category details
    $categorySql = "SELECT * FROM category WHERE id = '$category_id'";
    $categoryResult = mysqli_query($dbcon, $categorySql);
    $categoryRow = mysqli_fetch_assoc($categoryResult);
    $categoryName = htmlspecialchars($categoryRow['catname'], ENT_QUOTES);
    $categoryDescription = htmlspecialchars($categoryRow['description'], ENT_QUOTES);
    ?>
    <div class="w3-container main-content">
        <h2 class="w3-container w3-grey w3-center"><?php echo $categoryName; ?></h2>

        <?php
        $sql = "SELECT * FROM posts WHERE category_id = '$category_id' ORDER BY id DESC";
        $result = mysqli_query($dbcon, $sql);

        if (mysqli_num_rows($result) < 1) {
            echo "No posts found in this category";
        } else {
            echo "<table class='w3-table-all'>";
            echo "<tr class='w3-grey w3-hover-dark-grey'>";
            echo "<th>Title</th>";
            echo "<th>Date</th>";
            echo "</tr>";

            while ($row = mysqli_fetch_assoc($result)) {
                $title = htmlspecialchars($row['title'], ENT_QUOTES);
                $time = htmlspecialchars($row['date'], ENT_QUOTES);

                ?>

                <tr>
                    <td><a href="view.php?id=<?php echo $id; ?>"><?php echo substr($title, 0, 50); ?></a></td>
                    <td><?php echo $time; ?></td>
                </tr>

                <?php
            }
            echo "</table>";
        }
        ?>

    </div>
    <?php
} else {
    echo "Category ID not provided";
}

include("footer.php");
?>

