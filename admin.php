<?php
require_once 'connect.php';
require_once 'header.php';
require_once 'security.php';
?>

<h2 class="w3-container w3-grey w3-center">Admin Dashboard</h2>

<div class="w3-container w3-text-white">
    <p>Welcome <?php echo $_SESSION['username']; ?>,</p>
</div>

<h2 class="w3-container w3-grey w3-center">Blog Posts</h2>

<?php
$sql = "SELECT COUNT(*) FROM posts";
$result = mysqli_query($dbcon, $sql);
$numrows = mysqli_fetch_row($result)[0];
$rowsperpage = PAGINATION;
$totalpages = ceil($numrows / $rowsperpage);
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, min($totalpages, $page));
$offset = ($page - 1) * $rowsperpage;

$sql = "SELECT * FROM posts ORDER BY id DESC LIMIT $offset, $rowsperpage";
$result = mysqli_query($dbcon, $sql);

if (mysqli_num_rows($result) < 1) {
    echo "No post found";
} else {
    echo "<table class='w3-table-all'>";
    echo "<tr class='w3-grey w3-hover-dark-grey'>";
    echo "<th>ID</th>";
    echo "<th>Title</th>";
    echo "<th>Date</th>";
    echo "<th>Action</th>";
    echo "</tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        $id = htmlspecialchars($row['id'], ENT_QUOTES);
        $title = htmlspecialchars($row['title'], ENT_QUOTES);
        $time = htmlspecialchars($row['date'], ENT_QUOTES);
        $slug = htmlspecialchars($row['slug'], ENT_QUOTES);

        $permalink = "p/" . $id . "/" . $slug;
                                                }
        ?>

        <tr>
            <td><?php echo $id; ?></td>
            <td><a href="view.php?id=<?php echo $id; ?>"><?php echo substr($title, 0, 50); ?></a></td>
            <td><?php echo $time; ?></td>
            <td>
                <a href="edit.php?id=<?php echo $id; ?>">Edit</a> |
                <a href="del.php?id=<?php echo $id; ?>"
                   onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
            </td>
        </tr>

        <?php
    }
    echo "</table>";

    // pagination
echo "<p><div class='w3-bar w3-center'>";
if ($page > 1) {
    echo "<a href='?page=1' class='w3-btn'><<</a>";
    $prevpage = $page - 1;
    echo "<a href='?page=$prevpage' class='w3-btn'><</a>";
}
$range = 3;
for ($i = max(1, $page - $range); $i <= min($page + $range, $totalpages); $i++) {
    if ($i == $page) {
        echo "<div class='w3-button w3-grey'> $i</div>";
    } else {
        echo "<a href='?page=$i' class='w3-btn w3-border'>$i</a>";
    }
}
if ($page != $totalpages) {
    $nextpage =  $page + 1;
    echo "<a href='?page=$nextpage' class='w3-btn'>></a>";
    echo "<a href='?page=$totalpages' class='w3-btn'>>></a>";
}
echo "</div></p>";
echo '<p><a href="new.php" class="w3-button w3-grey">Create new post</a></p>';

// Category management
echo '<h2 class="w3-container w3-grey w3-center">Categories</h2>';

$categorySql = "SELECT * FROM category";
$categoryResult = mysqli_query($dbcon, $categorySql);

if (mysqli_num_rows($categoryResult) < 1) {
    echo "No categories found";
} else {
    echo "<table class='w3-table-all'>";
    echo "<tr class='w3-grey w3-hover-dark-grey'>";
    echo "<th>ID</th>";
    echo "<th>Name</th>";
    echo "<th>Description</th>";
    echo "<th>Action</th>";
    echo "</tr>";

    while ($categoryRow = mysqli_fetch_assoc($categoryResult)) {
        $categoryId = htmlspecialchars($categoryRow['id'], ENT_QUOTES);
        $categoryName = htmlspecialchars($categoryRow['catname'], ENT_QUOTES);
        $categoryDescription = htmlspecialchars($categoryRow['description'], ENT_QUOTES);

        ?>

        <tr>
            <td><?php echo $categoryId; ?></td>
            <td><?php echo $categoryName; ?></td>
            <td><?php echo $categoryDescription; ?></td>
            <td>
                <a href="edit_category.php?id=<?php echo $categoryId; ?>">Edit</a> |
                <a href="delete_category.php?id=<?php echo $categoryId; ?>"
                   onclick="return confirm('Are you sure you want to delete this category?')">Delete</a>
            </td>
        </tr>

        <?php
    }
    echo "</table>";
}

echo "<p><a href='new_category.php' class='w3-button w3-grey'>Create new category</a></p>";

// User management
echo '<h2 class="w3-container w3-grey w3-center">User Management</h2>';

$userSql = "SELECT * FROM admin";
$userResult = mysqli_query($dbcon, $userSql);

if (mysqli_num_rows($userResult) < 1) {
    echo "No users found";
} else {
    echo "<table class='w3-table-all'>";
    echo "<tr class='w3-grey w3-hover-dark-grey'>";
    echo "<th>ID</th>";
    echo "<th>Username</th>";
    echo "<th>Email</th>";
    echo "<th>Action</th>";
    echo "</tr>";

    while ($userRow = mysqli_fetch_assoc($userResult)) {
        $userId = htmlspecialchars($userRow['id'], ENT_QUOTES);
        $username = htmlspecialchars($userRow['username'], ENT_QUOTES);
        $email = htmlspecialchars($userRow['email'], ENT_QUOTES);

        ?>

        <tr>
            <td><?php echo $userId; ?></td>
            <td><?php echo $username; ?></td>
            <td><?php echo $email; ?></td>
            <td>
                <a href="edit_user.php?id=<?php echo $userId; ?>">Edit</a> |
                <a href="delete_user.php?id=<?php echo $userId; ?>"
                   onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
            </td>
        </tr>

        <?php
    }
    echo "</table>";
}

echo "<p><a href='new_user.php' class='w3-button w3-grey'>Create new user</a></p>";

include("footer.php");
