<!DOCTYPE html>
<html>
  <head>
    <title>Wiki - Add Guide</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/simplemde@1.11.2/dist/simplemde.min.css">
  </head>
  <body>
    <h1>Add Guide</h1>
    <form method="POST" action="save_guide.php">
      <label for="guide-title">Title:</label>
      <input type="text" id="guide-title" name="title"><br>
      <label for="guide-category">Category:</label>
      <select id="guide-category" name="category">
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
      </select><br>
      <label for="guide-content">Content:</label>
      <textarea id="guide-content" name="content"></textarea><br>
      <button type="submit">Save Guide</button>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/simplemde@1.11.2/dist/simplemde.min.js"></script>
    <script>
      var editor = new SimpleMDE({ element: document.getElementById("guide-content") });
    </script>
  </body>
</html>