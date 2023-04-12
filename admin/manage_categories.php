<?php
function build_category_tree($categories, $parent_id = 0) {
  $tree = [];

  foreach ($categories as $category) {
      if ($category['parent_id'] == $parent_id) {
          $children = build_category_tree($categories, $category['id']);
          if ($children) {
              $category['children'] = $children;
          }
          $tree[] = $category;
      }
  }

  return $tree;
}
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Wiki - Categories</title>
    <link rel="icon" type="image/png" href="/public/images/favicon.png">
    <link rel="stylesheet" type="text/css" href="css/admin.css">
  </head>
  <body>
  <?php include 'admin_sidebar.php'; ?>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      file_put_contents("debug_log.txt", "[" . date("Y-m-d H:i:s") . "] " . "POST request detected on manage_categories.php" . PHP_EOL, FILE_APPEND);
  }  
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
      //session_start();
      include '../config.php';

      // Fetch the user's rank number from the database
$user_rank_number = 0;
if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
  $stmt = $conn->prepare("SELECT rank_number FROM users INNER JOIN ranks ON users.rank_id = ranks.id WHERE users.id = ?");
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_rank_number = $row['rank_number'];
  }
}

if ($user_rank_number < 3) {
  die("You don't have permission to access this page.");
}

      if (isset($_POST['edit_category'])) {
        // Edit category
        $category_id = $_POST['category_id'];
        $category_name = $_POST['category_name'];
        $parent_id = $_POST['parent_id'] ?? null;

        $stmt = $conn->prepare("UPDATE categories SET name = ?, parent_id = ? WHERE id = ?");
        $stmt->bind_param("sii", $category_name, $parent_id, $category_id);
        $stmt->execute();

        header('Location: categories.php');
      }

      if (isset($_POST['delete_category'])) {
        // Delete category
        $category_id = $_POST['category_id'];

        // Check if category has subcategories
        $stmt = $conn->prepare("SELECT * FROM categories WHERE parent_id = ?");
        $stmt->bind_param("i", $category_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
          die("You can't delete this category because it has subcategories.");
        }

        $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->bind_param("i", $category_id);
        $stmt->execute();

        header('Location: manage_categories.php');
      }

      function generateCategoryOptions($categories, $parent_id = null, $indent = "")
{
    $options = "";

    foreach ($categories as $category) {
        if ($category['parent_id'] == $parent_id) {
            $options .= "<option value=\"{$category['id']}\">{$indent}{$category['name']}</option>";
            $options .= generateCategoryOptions($categories, $category['id'], $indent . "&emsp;");
        }
    }

    return $options;
}


      // Get all categories
$stmt = $conn->prepare("SELECT categories.*, parent.name as parent_name FROM categories LEFT JOIN categories AS parent ON categories.parent_id = parent.id ORDER BY parent_id, name");
$stmt->execute();
$result = $stmt->get_result();

// Generate category options
$categoriesArray = $result->fetch_all(MYSQLI_ASSOC);
$options = generateCategoryOptions($categoriesArray);

// Build category tree
$category_tree = build_category_tree($categoriesArray);
    ?>
    
    <main>
      <div class="content">
        <h1>Manage Categories</h1>

        <h2>Add Category</h2>
        <form method="POST" action="add_category.php">
        <label for="category_name">Category Name:</label>
        <input type="text" id="category_name" name="category_name" required>
        <label for="parent_id">
        <select id="parent_id" name="parent_id">
            <option value="">Select parent category (optional)</option>
            <?php echo $options; ?>
        </select>
        <input type="submit" name="add_category" value="Add Category">
    </form>

        <h2>Edit Categories</h2>
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Category Name</th>
              <th>Parent Category</th>
              <th>Action</th>
            </tr>
          </thead>
          <?php
function display_category_row($category, $level = 0) {
    $indent = str_repeat('&nbsp;', $level * 4);
    echo "<tr>";
    echo "<td>{$category['id']}</td>";
    echo "<td>{$indent}{$category['name']}</td>";
    echo "<td>{$category['parent_name']}</td>";
    echo "<td><a href=\"edit_category.php?id={$category['id']}\">Edit</a></td>";
    echo "<td><form method=\"POST\"><input type=\"hidden\" name=\"category_id\" value=\"{$category['id']}\"><button type=\"submit\" name=\"delete_category\" onclick=\"return confirm('Are you sure you want to delete this category?')\">Delete</button></form></td>";
    echo "</tr>";

    if (isset($category['children'])) {
        foreach ($category['children'] as $child) {
            display_category_row($child, $level + 1);
        }
    }
}
?>

          <tbody>
          <?php
    foreach ($category_tree as $category) {
        display_category_row($category);
    }
    ?>
          </tbody>
        </table>
      </div>
    </main>

    <?php include '../footer.php'; ?>
    <script>
document.addEventListener('DOMContentLoaded', function () {
  const addCategoryButton = document.querySelector('button[name="add_category"]');
  const categoryForm = document.querySelector('form[action="add_category.php"]');

  addCategoryButton.addEventListener('click', function (event) {
    event.preventDefault();
    categoryForm.submit();
  });
});
</script>
  </body>
</html>