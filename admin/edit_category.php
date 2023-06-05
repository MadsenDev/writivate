<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../config.php';
include '../functions.php';

$category_id = $_GET['id'] ?? null;

if (!$category_id) {
    die("Invalid category ID.");
}

$stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();
$category = $result->fetch_assoc();

if (!$category) {
    die("Category not found.");
}

if (isset($_POST['edit_category'])) {
    $category_name = $_POST['category_name'];
    $category_description = $_POST['category_description'];
    $parent_id = $_POST['parent_id'] ?? null;

    if ($parent_id === "") {
        $stmt = $conn->prepare("UPDATE categories SET name = ?, description = ? WHERE id = ?");
        $stmt->bind_param("ssi", $category_name, $category_description, $category_id);
    } else {
        $stmt = $conn->prepare("UPDATE categories SET name = ?, description = ?, parent_id = ? WHERE id = ?");
        $stmt->bind_param("ssii", $category_name, $category_description, $parent_id, $category_id);
    }
    
    $stmt->execute();
    if ($stmt->error) {
        echo "Error: {$stmt->error}";
    }
    echo "Affected rows: " . $stmt->affected_rows;

    header('Location: manage_categories.php');
}

$stmt = $conn->prepare("SELECT categories.*, parent.name as parent_name FROM categories LEFT JOIN categories AS parent ON categories.parent_id = parent.id ORDER BY parent_id, name");
$stmt->execute();
$result = $stmt->get_result();

// Generate category options
$categoriesArray = $result->fetch_all(MYSQLI_ASSOC);
$options = generateCategoryOptions($categoriesArray, null, "", $category['parent_id']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Wiki - Edit Category</title>
    <link rel="icon" type="image/png" href="/public/images/favicon.png">
    <link rel="stylesheet" type="text/css" href="css/admin.css">
</head>
<body>
    <main>
        <?php include 'admin_sidebar.php'; ?>
        <div class="content">
            <h1>Edit Category</h1>
            <form method="POST">
                <label for="category_name">Category Name:</label>
                <input type="text" id="category_name" name="category_name" value="<?php echo $category['name']; ?>" required>

                <label for="category_description">Description:</label>
                <textarea id="category_description" name="category_description" required><?php echo $category['description']; ?></textarea>

                <label for="parent_id">Parent Category:</label>
                <select id="parent_id" name="parent_id">
                    <option value="">Select parent category (optional)</option>
                    <?php echo $options; ?>
                </select>

                <button type="submit" name="edit_category">Update Category</button>
            </form>
        </div>
    </main>
</body>
</html>