<aside>
  <h2>Categories</h2>
  <ul class="menu">
    <?php
    include 'config.php';
      // Fetch the categories and their guides from the database
      $stmt = $conn->prepare("SELECT c.id, c.name, g.id AS guide_id, g.title FROM categories c LEFT JOIN guides g ON c.id = g.category_id ORDER BY c.id, g.title");
      $stmt->execute();
      $result = $stmt->get_result();

      // Build an array of categories and their guides
      $categories = array();
      while ($row = $result->fetch_assoc()) {
        $category_id = $row['id'];
        $category_name = $row['name'];
        $guide_id = $row['guide_id'];
        $guide_title = $row['title'];

        if (!isset($categories[$category_id])) {
          $categories[$category_id] = array(
            'name' => $category_name,
            'guides' => array()
          );
        }

        if ($guide_id) {
          $categories[$category_id]['guides'][$guide_id] = $guide_title;
        }
      }

      // Print the categories and their guides
      foreach ($categories as $category) {
        $category_name = $category['name'];
        $guides = $category['guides'];

        echo "<li><a href=\"#\">$category_name</a>";
        if (count($guides) > 0) {
          echo "<ul>";
          foreach ($guides as $guide_id => $guide_title) {
            echo "<li><a href=\"guides/guide.php?id=$guide_id\">$guide_title</a></li>";
          }
          echo "</ul>";
        }
        echo "</li>";
      }
    ?>
  </ul>
</aside>
