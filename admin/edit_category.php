<!DOCTYPE html>
<html>
<head>
    <title>Wiki - Edit Category</title>
    <link rel="stylesheet" type="text/css" href="css/admin.css">
</head>
<body>
    <?php
        include '../config.php';

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
            $parent_id = $_POST['parent_id'] ?? null;

            $stmt = $conn->prepare("UPDATE categories SET name = ?, parent_id = ? WHERE id = ?");
            $stmt->bind_param("sii", $category_name, $parent_id, $category_id);
            $stmt->execute();

            header('Location: manage_categories.php');
        }
    ?>

    <main>
        <?php include 'admin_sidebar.php'; ?>
        <div class="content">
            <h1>Edit Category</h1>
            <form method="POST">
                <label for="category_name">Category Name:</label>
                <input type="text" id="category_name" name="category_name" value="<?php echo $category['name']; ?>" required>

                <label for="parent_id">
                    <select id="parent_id" name="parent_id">
                        <option value="">Select parent category (optional)</option>
                        <?php
                            $stmt = $conn->prepare("SELECT * FROM categories WHERE id != ?");
                            $stmt->bind_param("i", $category_id);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            while ($row = $result->fetch_assoc()) {
                                $selected = ($row['id'] == $category['parent_id']) ? "selected" : "";
                                echo "<option value=\"{$row['id']}\" {$selected}>{$row['name']}</option>";
                            }
                        ?>
                    </select>
                </label>

                <button type="submit" name="edit_category">Update Category</button>
            </form>
        </div>
    </main>

    <?php include '../footer.php'; ?>
</body>
</html>