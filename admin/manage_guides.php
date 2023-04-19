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

    // If the user doesn't have permission to access this page
    if (!check_permission($user_rank_id, 'can_create_guide')) {
      die("You don't have permission to access this page.");
    }

    include '../config.php';
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    if (isset($_GET['delete_guide']) && check_permission($user_rank_id, 'can_delete_guide')) {
      // Delete guide
      $guide_id = $_GET['guide_id'];

      // Delete update logs for the guide
      $stmt = $conn->prepare("DELETE FROM guide_updates WHERE guide_id = ?");
      $stmt->bind_param("i", $guide_id);
      $stmt->execute();

      // Delete related views for the guide
      $stmt = $conn->prepare("DELETE FROM guide_views WHERE guide_id = ?");
      $stmt->bind_param("i", $guide_id);
      $stmt->execute();

      // Delete the guide
      $stmt = $conn->prepare("DELETE FROM guides WHERE id = ?");
      $stmt->bind_param("i", $guide_id);
      $stmt->execute();

      header('Location: manage_guides.php');
    }

    // Get all guides
    $stmt = $conn->prepare("SELECT guides.*, categories.name as category_name, users.username as author_username FROM guides LEFT JOIN categories ON guides.category_id = categories.id LEFT JOIN users ON guides.creator_id = users.id ORDER BY id DESC");
    $stmt->execute();
    $result = $stmt->get_result();
    ?>

    <main>
      <div class="content">
        <h1>Manage <?php echo htmlspecialchars($content_type_plural); ?> <a href="add_guide.php">Add <?php echo htmlspecialchars($content_type_single); ?></a></h1>

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
              mysqli_data_seek($result, 0); // Reset the $result pointer
              while ($row = mysqli_fetch_assoc($result)) {
                $guide_id = $row['id'];
                $guide_title = $row['title'];
                $category_name = $row['category_name'];
                $author_username = $row['author_username'];

                // Output guide data
                echo "<tr>";
                echo "<td>$guide_id</td>";
                echo "<td><a href=\"/guide.php?id=$guide_id\">$guide_title</a></td>";
                echo "<td>$category_name</td>";
                echo "<td>$author_username</td>";
                echo "<td>";
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