<?php
require_once 'connect.php';
require_once 'header.php';
require_once 'security.php';

// Überprüfen, ob eine Kategorie-ID übergeben wurde
if (isset($_GET['id'])) {
    $categoryId = mysqli_real_escape_string($dbcon, $_GET['id']);

    // Kategoriedaten abrufen
    $sql = "SELECT * FROM category WHERE id = '$categoryId'";
    $result = mysqli_query($dbcon, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $categoryName = htmlspecialchars($row['catname'], ENT_QUOTES);
        $categoryDescription = htmlspecialchars($row['description'], ENT_QUOTES);
    } else {
        echo "<div class='w3-panel w3-pale-red w3-display-container'>Category not found.</div>";
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
    $newCategoryName = mysqli_real_escape_string($dbcon, $_POST['category_name']);
    $newCategoryDescription = mysqli_real_escape_string($dbcon, $_POST['category_description']);

    // Überprüfen, ob der Kategoriename bereits verwendet wird
    $checkSql = "SELECT * FROM category WHERE catname = '$newCategoryName' AND id != '$categoryId'";
    $checkResult = mysqli_query($dbcon, $checkSql);

    if (mysqli_num_rows($checkResult) > 0) {
        echo "<div class='w3-panel w3-pale-red w3-display-container'>Category name already exists. Please choose a different name.</div>";
    } else {
        // Kategorie aktualisieren
        $updateSql = "UPDATE category SET catname = '$newCategoryName', description = '$newCategoryDescription' WHERE id = '$categoryId'";

        if (mysqli_query($dbcon, $updateSql)) {
            echo "<div class='w3-panel w3-green w3-display-container'>Category updated successfully.</div>";
            $categoryName = $newCategoryName;
            $categoryDescription = $newCategoryDescription;
        } else {
            echo "<div class='w3-panel w3-pale-red w3-display-container'>Error updating category.</div>";
        }
    }
}
?>

<h2 class="w3-container w3-grey w3-center">Edit Category</h2>

<div class="w3-container">
    <form method="POST" action="">
        <label for="category_name">Category Name:</label>
        <input type="text" id="category_name" name="category_name" value="<?php echo $categoryName; ?>" required>

        <label for="category_description">Category Description:</label>
        <textarea id="category_description" name="category_description" required><?php echo $categoryDescription; ?></textarea>

        <input type="submit" value="Save" class="w3-button w3-grey">
    </form>
</div>

<?php include("footer.php"); ?>
