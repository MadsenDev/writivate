<?php
include 'config.php';
include 'functions.php';
$site_name = get_setting_value($conn, 'site_name');
function fetch_subcategory_guides($conn, $category_id) {
    $stmt = $conn->prepare("SELECT id, name FROM categories WHERE parent_id = ?");
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $subcategories = $stmt->get_result();

    $guides = [];
    while ($subcategory = $subcategories->fetch_assoc()) {
        $subcategory_id = $subcategory['id'];
        $subcategory_name = $subcategory['name'];

        $stmt = $conn->prepare("SELECT * FROM guides WHERE category_id = ?");
        $stmt->bind_param("i", $subcategory_id);
        $stmt->execute();
        $subcategory_guides = $stmt->get_result();

        while ($guide = $subcategory_guides->fetch_assoc()) {
            $guide['category_name'] = $subcategory_name; // add subcategory name to guide array
            $guides[] = $guide;
        }

        $guides = array_merge($guides, fetch_subcategory_guides($conn, $subcategory_id));
    }

    return $guides;
}

// Fetch content_type_plural, content_type_single, and show_views from the 'settings' table
$stmt = $conn->prepare("SELECT name, value FROM settings WHERE name IN ('content_type_plural', 'content_type_single', 'show_views')");
$stmt->execute();
$result = $stmt->get_result();
$settings = [];
while ($row = $result->fetch_assoc()) {
  $settings[$row['name']] = $row['value'];
}
$content_type_plural = $settings['content_type_plural'];
$content_type_single = $settings['content_type_single'];
?>

<!DOCTYPE html>
<html>
<head>
    <?php include 'head.php'; ?>
</head>
<body>
<?php include 'header.php'; ?>


<main>
<?php include 'sidebar.php'; ?>
    <div class="content">
        <?php
        if (isset($_GET['id'])) {
            $category_id = $_GET['id'];

            // Fetch category details
            $stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
            $stmt->bind_param("i", $category_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $category = $result->fetch_assoc();

                // Display category name
                echo "<h1>Category: {$category['name']}</h1>";

                // Display category description
                if(!empty($category['description'])) {
                    echo "<p>{$category['description']}</p>";
                }

                // Fetch guides within the category and its subcategories
$stmt = $conn->prepare("SELECT * FROM guides WHERE category_id = ?");
$stmt->bind_param("i", $category_id);
$stmt->execute();
$guides_result = $stmt->get_result();
$guides = [];

while ($guide = $guides_result->fetch_assoc()) {
    $guides[] = $guide;
}

$guides = array_merge($guides, fetch_subcategory_guides($conn, $category_id));

echo "<h1>{$content_type_plural}</h1>";

if (count($guides) > 0) {
    echo "<ul>";
    foreach ($guides as $guide) {
        echo "<li><a href=\"/guide.php?id={$guide['id']}\">{$guide['title']}</a>";
if (!empty($guide['category_name'])) {
    echo " (<em><a href='categories.php?id={$guide['category_id']}' style='color: darkgray;'>{$guide['category_name']}</a></em>)";
}
echo "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No guides found in this category.</p>";
}

            } else {
                echo "<p>Category not found.</p>";
            }

        } else {
            echo "<p>Please select a category.</p>";
        }
        ?>
    </div>
</main>

<?php include 'footer.php'; ?>
</body>
</html>