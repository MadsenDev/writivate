<!DOCTYPE html>
<html>
  <head>
    <title>Wiki - Manage Guides</title>
    <link rel="icon" type="image/png" href="/public/images/favicon.png">
    <link rel="stylesheet" type="text/css" href="css/admin.css">
  </head>
  <body>
    <?php
    include 'admin_sidebar.php';

    if (!check_permission($user_rank_id, 'can_create_guide')) {
      die("You don't have permission to access this page.");
    }

    include '../config.php';
    include '../functions.php';

    $category_id = $_GET['id'] ?? null;
    if ($category_id) {
        $stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->bind_param("i", $category_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $category = $result->fetch_assoc();

        if (!$category) {
            die("Category not found.");
        }

        $stmt = $conn->prepare("SELECT guides.*, categories.name as category_name, users.username as author_username FROM guides LEFT JOIN categories ON guides.category_id = categories.id LEFT JOIN users ON guides.creator_id = users.id WHERE guides.category_id = ? ORDER BY id DESC");
        $stmt->bind_param("i", $category_id);
    } else {
        $stmt = $conn->prepare("SELECT guides.*, categories.name as category_name, users.username as author_username FROM guides LEFT JOIN categories ON guides.category_id = categories.id LEFT JOIN users ON guides.creator_id = users.id ORDER BY id DESC");
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if (isset($_GET['delete_guide']) && check_permission($user_rank_id, 'can_delete_guide')) {
      $guide_id = $_GET['guide_id'];

      $stmt = $conn->prepare("DELETE FROM guide_updates WHERE guide_id = ?");
      $stmt->bind_param("i", $guide_id);
      $stmt->execute();

      $stmt = $conn->prepare("DELETE FROM guide_views WHERE guide_id = ?");
      $stmt->bind_param("i", $guide_id);
      $stmt->execute();

      $stmt = $conn->prepare("DELETE FROM guides WHERE id = ?");
      $stmt->bind_param("i", $guide_id);
      $stmt->execute();

      header('Location: manage_guides.php');
    }

    // Fetch all categories
    $stmt = $conn->prepare("SELECT categories.*, parent.name as parent_name FROM categories LEFT JOIN categories AS parent ON categories.parent_id = parent.id ORDER BY parent_id, name");
    $stmt->execute();
    $categories_result = $stmt->get_result();
    $categoriesArray = $categories_result->fetch_all(MYSQLI_ASSOC);
    $options = generateCategoryOptions($categoriesArray, null, "", $category_id);
    ?>

    <main>
      <div class="content">
        <h1>Manage <?php echo htmlspecialchars($content_type_plural); ?> <a href="add_guide.php">Add <?php echo htmlspecialchars($content_type_single); ?></a></h1>

        <label for="category_id">Filter by Category:</label>
        <select id="category_id" name="category_id" onchange="window.location.href='manage_guides.php?id='+this.value">
          <option value="">All Categories</option>
          <?php echo $options; ?>
        </select>
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Title</th>
              <th>Category</th>
              <th>Author</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
              mysqli_data_seek($result, 0);
              while ($row = mysqli_fetch_assoc($result)) {
                $guide_id = $row['id'];
                $guide_title = $row['title'];
                $category_name = $row['category_name'];
                $author_username = $row['author_username'];
            
                // Check if there are translations for this guide
                $translations_stmt = $conn->prepare("SELECT language FROM guide_translations WHERE guide_id = ?");
                $translations_stmt->bind_param("i", $guide_id);
                $translations_stmt->execute();
                $translations_result = $translations_stmt->get_result();
                $translations = [];
                while ($translation_row = $translations_result->fetch_assoc()) {
                    $translations[] = $translation_row['language'];
                }
                $translations_str = !empty($translations) ? implode(', ', $translations) : 'No translations';
            
                echo "<tr>";
                echo "<td>$guide_id</td>";
                echo "<td><a href=\"/guide.php?id=$guide_id\">$guide_title</a></td>";
                echo "<td>$category_name</td>";
                echo "<td>$author_username</td>";
                echo "<td>$translations_str | "; // Display translations here
                if (check_permission($user_rank_id, 'can_edit_guide')) {
                  echo "<a href=\"edit_guide.php?id=$guide_id\">Edit</a> | ";
                }
                echo "<a href=\"view_translations.php?guide_id=$guide_id\">View Translations</a>";
                if (check_permission($user_rank_id, 'can_delete_guide')) {
                  echo " | <a href=\"manage_guides.php?delete_guide=1&guide_id=$guide_id\" onclick=\"return confirm('Are you sure you want to delete this guide?')\">Delete</a>";
                }
                echo "</td>";
                echo "</tr>";
            }
            } else {
              echo "Nothing found.";
            }
            ?>
          </tbody>
        </table>
      </div>
    </main>
  </body>
</html>