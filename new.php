<?php
require_once 'connect.php';
require_once 'header.php';
require_once 'security.php';
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
if (isset($_POST['submit'])) {
    $title = mysqli_real_escape_string($dbcon, $_POST['title']);
    $description = mysqli_real_escape_string($dbcon, $_POST['description']);
    $slug = slug($title);
    $date = date('Y-m-d H:i');
    $posted_by = mysqli_real_escape_string($dbcon, $_SESSION['username']);
    $category_id = mysqli_real_escape_string($dbcon, $_POST['category']);

    $sql = "INSERT INTO posts (title, description, slug, posted_by, date, category_id) 
            VALUES ('$title', '$description', '$slug', '$posted_by', '$date', '$category_id')";
    mysqli_query($dbcon, $sql) or die("Failed to post: " . mysqli_connect_error());

    $permalink = "view.php?id=" . mysqli_insert_id($dbcon);

    printf("Posted successfully. <meta http-equiv='refresh' content='2; url=%s'/>", $permalink);

} else {
    ?>
    <div class="w3-container main-content">
        <div class="w3-card-4">
            <div class="w3-container w3-grey">
                <h2>New Post</h2>
            </div>

            <form class="w3-container" method="POST">

                <p>
                    <label>Title</label>
                    <input type="text" class="w3-input w3-border" name="title" required>
                </p>

                <p>
                    <label>Description</label>
                    <textarea id="description" row="30" cols="50" class="w3-input w3-border" name="description"
                              required></textarea>
                </p>

                <p>
                    <label>Category</label>
                    <select name="category" class="w3-select w3-border" required>
                        <?php
                        $categorySql = "SELECT * FROM category";
                        $categoryResult = mysqli_query($dbcon, $categorySql);

                        if (mysqli_num_rows($categoryResult) < 1) {
                            echo "<option disabled>No categories found</option>";
                        } else {
                            while ($categoryRow = mysqli_fetch_assoc($categoryResult)) {
                                $categoryId = htmlspecialchars($categoryRow['id'], ENT_QUOTES);
                                $categoryName = htmlspecialchars($categoryRow['catname'], ENT_QUOTES);
                                echo "<option value='$categoryId'>$categoryName</option>";
                            }
                        }
                        ?>
                    </select>
                </p>

                <p>
                    <input type="submit" class="w3-button w3-grey w3-round" name="submit" value="Post">
                </p>
            </form>

        </div>
    </div>
    <?php
}

include("footer.php");
?>

