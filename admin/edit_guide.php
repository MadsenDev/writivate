<!DOCTYPE html>
<html>
  <head>
    <title>Wiki - Edit Guide</title>
    <link rel="icon" type="image/png" href="/public/images/favicon.png">
    <link rel="stylesheet" type="text/css" href="css/admin.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/simplemde@1.11.2/dist/simplemde.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl7/0UzjzpxpwPRzKzggrD17l5ovvhF+kcJ6Y6F5/z" crossorigin="anonymous">
  </head>
  <body>
    <?php
      include '../config.php';
      include '../functions.php';
      include 'admin_sidebar.php';
    ?>

    <main>
      <div class="content">
        <h1>Edit Guide</h1>
        <?php
          $id = $_GET['id'];
          $stmt = $conn->prepare("SELECT * FROM guides WHERE id = ?");
          $stmt->bind_param("i", $id);
          $stmt->execute();
          $result = $stmt->get_result();
          $row = $result->fetch_assoc();
          $title = $row['title'];
          $category_id = $row['category_id'];
          $content = $row['content'];
          
          // Fetch categories and generate options
          $stmt = $conn->prepare("SELECT categories.*, parent.name as parent_name FROM categories LEFT JOIN categories AS parent ON categories.parent_id = parent.id ORDER BY parent_id, name");
          $stmt->execute();
          $result = $stmt->get_result();
          $categoriesArray = $result->fetch_all(MYSQLI_ASSOC);
          $options = generateCategoryOptions($categoriesArray, null, "", $category_id);
        ?>
        <form method="POST" action="update_guide.php">
          <input type="hidden" name="id" value="<?php echo $id; ?>">
          <div class="form-group">
            <label for="guide-title">Title:</label>
            <input type="text" id="guide-title" name="title" class="form-control" value="<?php echo $title; ?>">
          </div>
          <div class="form-group">
            <label for="guide-category">Category:</label>
            <select id="guide-category" name="category_id" class="form-control">
              <option value="">Select a category</option>
              <?php echo $options; ?>
            </select>
          </div>
          <div class="form-group">
            <label for="guide-tags">Tags (comma-separated):</label>
            <?php
              // Fetch tags for the guide
              $stmt = $conn->prepare("SELECT name FROM tags INNER JOIN guide_tags ON tags.id = guide_tags.tag_id WHERE guide_tags.guide_id = ?");
              $stmt->bind_param("i", $id);
              $stmt->execute();
              $tags_result = $stmt->get_result();

              $tags_string = "";
              while ($tag_row = $tags_result->fetch_assoc()) {
                $tags_string .= $tag_row['name'] . ', ';
              }
              $tags_string = rtrim($tags_string, ', ');
            ?>
                        <input type="text" id="guide-tags" name="tags" class="form-control" value="<?php echo $tags_string; ?>">
          </div>
          <div class="form-group">
            <label for="guide-content">Content:</label>
            <textarea id="guide-content" name="content" class="form-control"><?php echo $content; ?></textarea>
          </div>
          <button type="submit" class="btn btn-primary">Update Guide</button>
        </form>
        <script src="https://cdn.jsdelivr.net/npm/simplemde@1.11.2/dist/simplemde.min.js"></script>
        <script>
          var editor = new SimpleMDE({ element: document.getElementById("guide-content") });
        </script>
      </div>
    </main>
  </body>
</html>
