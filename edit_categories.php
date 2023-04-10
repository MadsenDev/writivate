<!DOCTYPE html>
<html>
  <head>
    <title>Wiki - Categories</title>
    <link rel="stylesheet" type="text/css" href="styles/main.css">
    <link rel="stylesheet" type="text/css" href="styles/header.css">
  </head>
  <body>
    <?php
      session_start();
      include 'header.php';
      include 'config.php';

      // Fetch the user's rank from the database
      $user_rank = '';
      if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("SELECT rank FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
          $row = $result->fetch_assoc();
          $user_rank = $row['rank'];
        }
      }

      if ($user_rank != 'add_edit') {
        die("You don't have permission to access this page.");
      }

      if (isset($_POST['add_category'])) {
        // Add new category
        $category_name = $_POST['category_name'];
        $parent_id = $_POST['parent_id'] ?? null;

        $stmt = $conn->prepare("INSERT INTO categories (name, parent_id) VALUES (?, ?)");
        $stmt->bind_param("si", $category_name, $parent_id);
        $stmt->execute();

        header('Location: categories.php');
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

        header('Location: categories.php');
      }

      // Get all categories
      $stmt = $conn->prepare("SELECT categories.*, parent.name as parent_name FROM categories LEFT JOIN categories AS parent ON categories.parent_id = parent.id");
      $stmt->execute();
      $result = $stmt->get_result();
    ?>
    
    <main>
      <?php include 'sidebar.php'; ?>
      <div class="content">
        <h1>Categories</h1>

        <h2>Add Category</h2>
        <form method="POST">
          <label for="category_name">Category Name:</label>
          <input type="text" id="category_name" name="category_name" required>
          <label for="parent_id">
                      <select id="parent_id" name="parent_id">
            <option value="">Select parent category (optional)</option>
            <?php
              $stmt = $conn->prepare("SELECT * FROM categories");
              $stmt->execute();
              $result = $stmt->get_result();

              while ($row = $result->fetch_assoc()) {
                echo "<option value=\"{$row['id']}\">{$row['name']}</option>";
              }
            ?>
          </select>
          <button type="submit" name="add_category">Add Category</button>
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
          <tbody>
          <?php
  if (mysqli_num_rows($result) > 0) {
    while ($row = $result->fetch_assoc()) {
        $category_id = $row['id'];
        $category_name = $row['name'];
        $parent_name = $row['parent_name'];
        
        // output category data
        echo "<tr>";
        echo "<td>$category_id</td>";
        echo "<td>$category_name</td>";
        echo "<td>$parent_name</td>";
        echo "<td><a href=\"edit_category.php?id=$category_id\">Edit</a></td>";
        echo "<td><form method=\"POST\"><input type=\"hidden\" name=\"category_id\" value=\"$category_id\"><button type=\"submit\" name=\"delete_category\" onclick=\"return confirm('Are you sure you want to delete this category?')\">Delete</button></form></td>";
        echo "</tr>";
    }
} else {
    echo "No categories found.";
}
?>

          </tbody>
        </table>
      </div>
    </main>

    <?php include 'footer.php'; ?>
  </body>
</html>