<!DOCTYPE html>
<html>
  <head>
    <title>Wiki - Add Guide</title>
    <link rel="stylesheet" type="text/css" href="styles/main.css">
    <link rel="stylesheet" type="text/css" href="styles/header.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/simplemde@1.11.2/dist/simplemde.min.css">
  </head>
  <body>

  <?php include 'header.php'; ?>

<main>
  <?php include 'sidebar.php'; ?>
  <div class="content">
  <h1>Add Guide</h1>
    <form method="POST" action="save_guide.php">
      <div class="form-group">
        <label for="guide-title">Title:</label>
        <input type="text" id="guide-title" name="title" class="form-control">
      </div>
      <div class="form-group">
        <label for="guide-category">Category:</label>
        <select id="guide-category" name="category" class="form-control">
          <?php
            $dir = 'guides';
            $categories = array('');
            $files = scandir($dir);
            foreach ($files as $file) {
              if ($file != '.' && $file != '..') {
                if (is_dir("$dir/$file")) {
                  array_push($categories, $file);
                }
              }
            }
            foreach ($categories as $category) {
              echo "<option value=\"$category\">$category</option>";
            }
          ?>
        </select>
      </div>
      <div class="form-group">
        <label for="guide-content">Content:</label>
        <textarea id="guide-content" name="content" class="form-control"></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Save Guide</button>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/simplemde@1.11.2/dist/simplemde.min.js"></script>
    <script>
      var editor = new SimpleMDE({ element: document.getElementById("guide-content") });
    </script>
  </div>
</main>

<?php include 'footer.php'; ?>
</body>
</html>